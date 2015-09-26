<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class UserAuth extends \Phalcon\Mvc\Model
{
    const ENTITY_TYPE_ADMIN = 1;
    const ENTITY_TYPE_CORPORATE = 2;

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $password;

    /**
     *
     * @var integer
     */
    protected $entity_type;

    /**
     *
     * @var string
     */
    protected $created_date;

    /**
     *
     * @var string
     */
    protected $modified_date;

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
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = strtolower(trim($email));

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        /**
         * @var \Phalcon\Security $security
         */
        $security = $this->getDI()->getSecurity();
        $this->password = $security->hash($password);

        return $this;
    }

    /**
     * Method to set the value of field entity_type
     *
     * @param integer $entity_type
     * @return $this
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;

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
     * Method to set the value of field modified_date
     *
     * @param string $modified_date
     * @return $this
     */
    public function setModifiedDate($modified_date)
    {
        $this->modified_date = $modified_date;

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
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field entity_type
     *
     * @return integer
     */
    public function getEntityType()
    {
        return $this->entity_type;
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
     * Returns the value of field modified_date
     *
     * @return string
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return UserAuth[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return UserAuth
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
            'email' => 'email',
            'password' => 'password',
            'entity_type' => 'entity_type',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'status' => 'status'
        );
    }

    public function initData($email, $password, $entity_type){
        $this->setEntityType($entity_type);
        $this->setEmail($email);
        $this->setPassword($password);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::INACTIVE);
    }

    public function getData(){
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'entity_type' => $this->getEntityType(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        ];
    }

    public static function fetchByEmail($email){
        return UserAuth::findFirst([
            'email = :email:',
            'bind' => ['email' => $email]
        ]);
    }

    public function changePassword($password){
        $this->setPassword($password);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
        $this->setStatus(Status::ACTIVE);
    }

    public function changeStatus($status){
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }
}
