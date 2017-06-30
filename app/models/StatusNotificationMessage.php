<?php

class StatusNotificationMessage extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $status_id;

    /**
     *
     * @var text
     */
    protected $email_message;

    /**
     *
     * @var string
     */
    protected $text_message;


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
     * Method to set the value of field status_id
     *
     * @param integer $status_id
     * @return $this
     */
    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;

        return $this;
    }

    /**
     * Method to set the value of field email_message
     *
     * @param text $email_message
     * @return $this
     */
    public function setEmailMessage($email_message)
    {
        $this->email_message = $email_message;

        return $this;
    }

    /**
     * Method to set the value of field text_message
     *
     * @param string $text_message
     * @return $this
     */
    public function setTextMessage($text_message)
    {
        $this->text_message = $text_message;

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
     * Returns the value of field status_id
     *
     * @return integer
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * Returns the value of field email_message
     *
     * @return text
     */
    public function getEmailMessage()
    {
        return $this->email_message;
    }

    /**
     * Returns the value of field text_message
     *
     * @return string
     */
    public function getTextMessage()
    {
        return $this->text_message;
    }


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('status_id', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return StatusNotificationMessage[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return StatusNotificationMessage
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
            'status_id' => 'status_id',
            'email_message' => 'email_message',
            'text_message' => 'text_message',

        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'status_id' => $this->getStatusId(),
            'email_message' => $this->getEmailMessage(),
            'text_message' => $this->getTextMessage(),
        );
    }


    public static function fetchOne($status_id)
    {
        return StatusNotificationMessage::findFirst([
            'status_id = :status_id:',
            'bind' => ['status_id' => $status_id]
        ]);
    }
}
