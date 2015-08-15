<?php

class EmailMessage extends \Phalcon\Mvc\Model
{
    const DEFAULT_FROM_EMAIL = 'sys@traceandtrack.com';

    const CORPORATE_LEAD = 1;
    const USER_ACCOUNT_CREATION = 2;
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
            'status' => 'status'
        );
    }

    public static function fetchActive($id){
        return EmailMessage::findFirst([
            'id = :id: AND status = :status:',
            'bind' => ['id' => $id, 'status' => Status::ACTIVE]
        ]);
    }

    public static function send($id, $msg_params, $from_name, $from_email=self::DEFAULT_FROM_EMAIL, $to_email=null){
        try {
            $email_msg = self::fetchActive($id);
            if ($email_msg == false) {
                return false;
            }
            $receiver_email = is_null($to_email) ? $email_msg->getToEmail(): $to_email;

            return Emailer::send(
                $receiver_email,
                $email_msg->getSubject(),
                $email_msg->getMessage(),
                $from_email,
                $from_name,
                $msg_params
            );
        } catch (Exception $e) {
        }
        return false;
    }


}
