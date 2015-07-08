<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $firstname;

    /**
     *
     * @var string
     */
    protected $lastname;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $phone;

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
    protected $modified_date;

    /**
     *
     * @var string
     */
    protected $password;

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
     * Method to set the value of field firstname
     *
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = strtolower(Text::removeExtraSpaces($firstname));

        return $this;
    }

    /**
     * Method to set the value of field lastname
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = strtolower(Text::removeExtraSpaces($lastname));

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

        if ($email != null) {
            $this->email = strtolower(trim($email));
        }else{
            $this->email = $email;
        }

        return $this;
    }

    /**
     * Method to set the value of field phone
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = trim($phone);

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
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        if ($password != null) {
            /**
             * @var \Phalcon\Security $security
             */
            $security = $this->getDI()->getSecurity();
            $this->password = $security->hash($password);
        }else{
            $this->password = $password;
        }

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
     * Returns the value of field firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Returns the value of field lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
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
     * Returns the value of field phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Returns the value of field modified_date
     *
     * @return string
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Company', 'created_by', array('alias' => 'Company'));
        $this->hasMany('id', 'Company', 'modified_by', array('alias' => 'Company'));
        $this->hasMany('id', 'Company', 'approved_by', array('alias' => 'Company'));
        $this->hasMany('id', 'CompanyUser', 'user_id', array('alias' => 'CompanyUser'));
        $this->hasMany('id', 'CorporateLead', 'user_id', array('alias' => 'CorporateLead'));
        $this->hasMany('id', 'Parcel', 'sender_id', array('alias' => 'Parcel'));
        $this->hasMany('id', 'Parcel', 'receiver_id', array('alias' => 'Parcel'));
        $this->hasMany('id', 'State', 'created_by', array('alias' => 'State'));
        $this->belongsTo('user_type_id', 'Ref_user_type', 'user_type_id', array('alias' => 'Ref_user_type'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return User[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return User
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
            'firstname' => 'firstname', 
            'lastname' => 'lastname', 
            'email' => 'email', 
            'phone' => 'phone', 
            'created_date' => 'created_date', 
            'status' => 'status', 
            'modified_date' => 'modified_date', 
            'password' => 'password'
        );
    }

    public function initData($firstname, $lastname, $phone, $email=null, $password=null, $is_existing=false){
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setPhone($phone);
        $this->setEmail($email);

        $now = date('Y-m-d H:i:s');
        if (!$is_existing){
            $this->setCreatedDate($now);
            $this->setStatus(Status::INACTIVE);
            $this->setPassword($password);
        }
        $this->setModifiedDate($now);
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

    public function editDetails($firstname, $lastname, $email){
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setEmail($email);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'created_date' => $this->getCreatedDate(),
            'status' => $this->getStatus(),
            'modified_date' => $this->getModifiedDate()
        );
    }

    public static function fetchByPhone($phone){
        return User::findFirst(array(
            'phone = :phone:',
            'bind' => ['phone' => trim($phone)]
        ));
    }

    public static function fetchByEmail($email){
        return User::findFirst(array(
            'email = :email:',
            'bind' => ['email' => trim($email)]
        ));
    }

    public static function fetchById($id){
        return User::findFirst(array(
            'id = :id:',
            'bind' => ['id' => $id]
        ));
    }
}
