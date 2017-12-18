<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 10/15/2016
 * Time: 5:50 PM
 */
class ParcelImportJob extends BaseJob
{
    public function execute(){
        try{

            /*print ' reading emails... \n\r ';

            $this->readMail();

            print ' all done \n\r ';*/



            print ' new reading emails... \n\r ';
            /*// 4. argument is the directory into which attachments are to be saved:
            $mailbox = new PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}INBOX', 'some@gmail.com', '*********', __DIR__);

            // Read all messaged into an array:
            $mailsIds = $mailbox->searchMailbox('ALL');
            if(!$mailsIds) {
                die('Mailbox is empty');
            }*/

            $reader = new Email_reader();


            print "$reader->msg_cnt red \n ";

            print 'extracting... \n ';

            $reader->extract();

            print "$reader->msg_cnt emails extracted \n ";
            //add a new job
            $worker = new ParcelImportWorker();

            $worker->addJob('{"date":"'.date('YMDHIS').'", "created_by":"321"}');
        }catch (\Exception $ec){
            Util::slackDebug('Imported parcels', $ec->getMessage());
            return false;
        }
        return true;
    }

    function readMail(){
        // Connect to gmail
        $imapPath = '{webmail.courierplus-ng.com:143/imap/notls}INBOX';
        $username = 'kangarootracker@courierplus-ng.com';
        $password = 'Tracker1';

        // try to connect
        $inbox = imap_open($imapPath,$username,$password) or die('Cannot connect to server: ' . imap_last_error());

        /* ALL - return all messages matching the rest of the criteria
         ANSWERED - match messages with the \\ANSWERED flag set
         BCC "string" - match messages with "string" in the Bcc: field
         BEFORE "date" - match messages with Date: before "date"
         BODY "string" - match messages with "string" in the body of the message
         CC "string" - match messages with "string" in the Cc: field
         DELETED - match deleted messages
         FLAGGED - match messages with the \\FLAGGED (sometimes referred to as Important or Urgent) flag set
         FROM "string" - match messages with "string" in the From: field
         KEYWORD "string" - match messages with "string" as a keyword
         NEW - match new messages
         OLD - match old messages
         ON "date" - match messages with Date: matching "date"
         RECENT - match messages with the \\RECENT flag set
         SEEN - match messages that have been read (the \\SEEN flag is set)
         SINCE "date" - match messages with Date: after "date"
         SUBJECT "string" - match messages with "string" in the Subject:
         TEXT "string" - match messages with text "string"
         TO "string" - match messages with "string" in the To:
         UNANSWERED - match messages that have not been answered
         UNDELETED - match messages that are not deleted
         UNFLAGGED - match messages that are not flagged
         UNKEYWORD "string" - match messages that do not have the keyword "string"
         UNSEEN - match messages which have not been read yet*/

        // search and get unseen emails, function will return email ids
        $emails = imap_search($inbox,'UNSEEN');

        foreach($emails as $mail) {

            $message = array(
                'index'     => $mail,
                'header'    => imap_headerinfo($inbox,$mail),
                'body'      => imap_body($inbox, $mail, FT_PEEK),
                'structure' => imap_fetchstructure($inbox,$mail)
            );
            ImportedParcelHistory::addFromEmail($message);

        }

        // colse the connection
        imap_expunge($inbox);
        imap_close($inbox);
    }
}

class Email_reader {

    // imap server connection
    public $conn;

    // inbox storage and inbox message count
    private $inbox;
    public $msg_cnt;

    // email login credentials
    private $server = 'webmail.courierplus-ng.com';
    private $user   = 'kangarootracker@courierplus-ng.com';
    private $pass   = 'Tracker1';
    private $port   = 143; // adjust according to server settings

    // connect to the server and get the inbox emails
    function __construct() {
        $this->connect();
        $this->inbox();
    }

    // close the server connection
    function close() {
        $this->inbox = array();
        $this->msg_cnt = 0;

        imap_expunge($this->conn);
        imap_close($this->conn);
    }

    // open the server connection
    // the imap_open function parameters will need to be changed for the particular server
    // these are laid out to connect to a Dreamhost IMAP server
    function connect() {
        $this->conn = @imap_open('{'.$this->server.'/notls}', $this->user, $this->pass, OP_SILENT);
    }

    public function extract(){
        //$emails = [];
        $total_messages = $this->msg_cnt;
        for($i = 1; $i <= $total_messages; $i++){
            print " Extracting message $i \r\n ";
            //$emails[] = $reader->get($i);
            ImportedParcelHistory::addFromEmail($this->get($i));
            //move the message to processed
            print " Deleting message $i \r\n ";
            $this->delete($i);
        }

        $this->close();
        //dd($emails);
    }

    function delete($message_index){
        imap_delete($this->conn, $message_index);
    }
    // move the message to a new folder
    function move($msg_index, $folder='INBOX.Processed') {
        // move on server
        imap_mail_move($this->conn, $msg_index, $folder);
        imap_expunge($this->conn);

        // re-read the inbox
        $this->inbox();
    }

    // get a specific message (1 = first email, 2 = second email, etc.)
    function get($msg_index=NULL) {
        if (count($this->inbox) <= 0) {
            return array();
        }
        elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
            return $this->inbox[$msg_index];
        }

        return $this->inbox[0];
    }

    // read the inbox
    function inbox() {
        /*$emails = imap_search($this->conn,'UNSEEN');
        if(!is_array($emails)){
            print "No new email to process \n\r";
            var_dump($emails);
            die();
        }*/
        $this->msg_cnt = imap_num_msg($this->conn);// count($emails);

        $in = array();
        /*$i = 1;
        foreach ($emails as $email) {
            $in[] = array(
                'index'     => $email,
                'header'    => imap_headerinfo($this->conn, $email),
                'body'      => imap_body($this->conn, $email),
                'structure' => imap_fetchstructure($this->conn, $email)
            );
        }*/

        for($i = 1; $i <= $this->msg_cnt; $i++) {
            $in[] = array(
                'index'     => $i,
                'header'    => imap_headerinfo($this->conn, $i),
                'body'      => imap_body($this->conn, $i),
                'structure' => imap_fetchstructure($this->conn, $i)
            );
        }

        $this->inbox = $in;
    }

    function number(){
        return $this->msg_cnt;
    }

}