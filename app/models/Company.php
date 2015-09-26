<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Company extends \Phalcon\Mvc\Model
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
    protected $name;

    /**
     *
     * @var string
     */
    protected $reg_no;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $phone_number;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var integer
     */
    protected $city_id;

    /**
     *
     * @var double
     */
    protected $credit_limit;

    /**
     *
     * @var double
     */
    protected $discount;

    /**
     *
     * @var integer
     */
    protected $primary_contact_id;

    /**
     *
     * @var integer
     */
    protected $sec_contact_id;

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
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field reg_no
     *
     * @param string $reg_no
     * @return $this
     */
    public function setRegNo($reg_no)
    {
        $this->reg_no = $reg_no;

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
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field phone_number
     *
     * @param string $phone_number
     * @return $this
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Method to set the value of field city_id
     *
     * @param integer $city_id
     * @return $this
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    /**
     * Method to set the value of field credit_limit
     *
     * @param double $credit_limit
     * @return $this
     */
    public function setCreditLimit($credit_limit)
    {
        $this->credit_limit = $credit_limit;

        return $this;
    }

    /**
     * Method to set the value of field discount
     *
     * @param double $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Method to set the value of field primary_contact_id
     *
     * @param integer $primary_contact_id
     * @return $this
     */
    public function setPrimaryContactId($primary_contact_id)
    {
        $this->primary_contact_id = $primary_contact_id;

        return $this;
    }

    /**
     * Method to set the value of field sec_contact_id
     *
     * @param integer $sec_contact_id
     * @return $this
     */
    public function setSecContactId($sec_contact_id)
    {
        $this->sec_contact_id = $sec_contact_id;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field reg_no
     *
     * @return string
     */
    public function getRegNo()
    {
        return $this->reg_no;
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
     * Returns the value of field phone_number
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the value of field city_id
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Returns the value of field credit_limit
     *
     * @return double
     */
    public function getCreditLimit()
    {
        return $this->credit_limit;
    }

    /**
     * Returns the value of field discount
     *
     * @return double
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Returns the value of field primary_contact_id
     *
     * @return integer
     */
    public function getPrimaryContactId()
    {
        return $this->primary_contact_id;
    }

    /**
     * Returns the value of field sec_contact_id
     *
     * @return integer
     */
    public function getSecContactId()
    {
        return $this->sec_contact_id;
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
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Company_user', 'company_id', array('alias' => 'Company_user'));
        $this->hasMany('id', 'Company_user', 'company_id', array('alias' => 'Company_user'));
        $this->belongsTo('created_by', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('modified_by', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('approved_by', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return Company[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Company
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
            'name' => 'name', 
            'reg_no' => 'reg_no', 
            'email' => 'email', 
            'phone_number' => 'phone_number', 
            'address' => 'address', 
            'city_id' => 'city_id', 
            'credit_limit' => 'credit_limit', 
            'discount' => 'discount', 
            'primary_contact_id' => 'primary_contact_id', 
            'sec_contact_id' => 'sec_contact_id', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

}
