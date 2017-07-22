<?php

use Superflux\Mailer\MailerHandler;

class EmailMessage extends \Phalcon\Mvc\Model
{
    //const DEFAULT_FROM_EMAIL = 'sys@traceandtrack.com';
    const DEFAULT_FROM_EMAIL = 'trackplus@courierplus-ng.com';// 'info@openbulksms.com';
    const DEFAULT_FROM_NAME = 'Track Plus';
    const CORPORATE_LEAD = 'marketing_opportunity';
    const USER_ACCOUNT_CREATION = 'staff_account_creation';
    const COMPANY_USER_ACCOUNT_CREATION = 'company_user_account_creation';
    const COMPANY_API_ACCESS_NOTIFICATION = 'company_api_access_notification';
    const PARCEL_IN_TRANSIT = 'parcel_in_transit';
    const PARCEL_DELIVERED = 'parcel_delivered';
    const RESET_PASSWORD = 'reset_password';
    const WEIGHT_NOT_IN_RANGE = 'weight_not_in_range';
    const BULK_WAYBILL_PRINTING = 'bulk_email_printing';

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $to_email;

    /**
     *
     * @var string
     */
    protected $subject;

    /**
     *
     * @var string
     */
    protected $message;

    /**
     *
     * @var string
     */
    protected $created_date;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var string
     */
    protected $email_message_code;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field to_email
     *
     * @param string $to_email
     * @return $this
     */
    public function setToEmail($to_email)
    {
        $this->to_email = $to_email;

        return $this;
    }

    /**
     * Method to set the value of field subject
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Method to set the value of field message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Method to set the value of field created_date
     *
     * @param string $created_date
     * @return $this
     */
    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set Email Message code
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $email_message_code
     * @return $this
     */
    public function setEmailMessageCode($email_message_code)
    {

        $this->email_message_code = $email_message_code;
        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field to_email
     *
     * @return string
     */
    public function getToEmail()
    {
        return $this->to_email;
    }

    /**
     * Returns the value of field subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Returns the value of field message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns the value of field created_date
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get email message code
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return string
     */
    public function getEmailMessageCode()
    {
        return $this->email_message_code;
    }

    /**
     * @return EmailMessage[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return EmailMessage
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'to_email' => 'to_email',
            'subject' => 'subject',
            'message' => 'message',
            'created_date' => 'created_date',
            'status' => 'status',
            'email_message_code' => 'email_message_code'
        );
    }

    public static function fetchActive($id)
    {
        return EmailMessage::findFirst([
            'id = :id: AND status = :status:',
            'bind' => ['id' => $id, 'status' => Status::ACTIVE]
        ]);
    }



    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $email_message_code
     * @param $msg_params
     * @param $from_name
     * @param null $to_email
     * @param string $from_email
     * @param array $extras
     * @return bool
     */
    public static function send($email_message_code, $msg_params, $from_name, $to_email = null, $from_email = self::DEFAULT_FROM_EMAIL, $extras = [])
    {
        try {
            $email_msg = self::findFirst([
                'email_message_code = :email_message_code: AND status = :status:',
                'bind' => ['email_message_code' => $email_message_code, 'status' => Status::ACTIVE]
            ]);
            if ($email_msg == false) {
                return false;
            }
            $receiver_email = is_null($to_email) ? $email_msg->getToEmail() : $to_email;

            if(isset($extras['cc'])){
                $cc = $extras['cc'];
            }else{
                $cc = [];
            }

            /** @var MailerHandler $mailer */
            $mailer = \Phalcon\Di::getDefault()->getMailer();
            return $mailer->send($email_msg->getMessage(), $email_msg->getMessage(), $email_msg->getSubject(), [$receiver_email => ''], [$from_email => $from_name], $cc, [], $msg_params);
        } catch (Exception $e) {
            Util::slackDebug('Email Not Sent', $e->getMessage());
            return false;
        }
    }

  /**
   * Sends the requires SMS/Email notification for a status as long as
   * the status has it configured in the DB.
   *
   * @param int $parcel_id
   * @param int $status_id
   *
   * @return bool
   */
  public static function sendParcelNotification($parcel_id, $status_id) {
        $status_notification = StatusNotificationMessage::findFirst(['status_id = :status_id:', 'bind' => ['status_id' => $status_id]]);

        if ($status_notification) {
          $parcel = Parcel::findFirstById($parcel_id);
          $sender_data = $parcel->sender->getData();
          $receiver_data = $parcel->receiver->getData();

          $msg_params = [
            'waybill_number' => $parcel->waybill_number,
            'amount_due' => $parcel->amount_due,
            'sender_phone' => $sender_data['phone'],
            'sender_email' => $sender_data['email'],
            'receiver_phone' => $receiver_data['phone'],
            'receiver_email' => $receiver_data['email'],
            'sender_name' => ucfirst($sender_data['firstname']) .' '. ucfirst($sender_data['lastname']),
            'receiver_name' => ucfirst($receiver_data['firstname']) .' '. ucfirst($receiver_data['lastname']),
          ];

          if ($status_notification->getEmailMessage()) {
              try {
                $recipients = [$sender_data['email'], $receiver_data['email']];
                $mailer = \Phalcon\Di::getDefault()->getMailer();
                foreach ($recipients as $recipient) {
                    $mailer->send(
                      $status_notification->getEmailMessage(),
                      $status_notification->getEmailMessage(),
                      $status_notification->getSubject(),
                      [$recipient => ''],
                      [self::DEFAULT_FROM_EMAIL => self::DEFAULT_FROM_NAME],
                      [],
                      [],
                      $msg_params
                  );
                }
              } catch (Exception $e) {
                Util::slackDebug('Email Not Sent', $e->getMessage());
              }
          }

          if ($status_notification->getTextMessage()) {
            $recipients = [$sender_data['phone'], $receiver_data['phone']];
            foreach ($recipients as $recipient) {
              if($recipient){
                try{
                  SmsMessage::send($recipient, 'Courierplus', $status_notification->getTextMessage());
                }
                catch(Exception $e) {
                  Util::slackDebug('SMS Not Sent', $e->getMessage());
                }

              }
            }
          }
        }
    }
}
