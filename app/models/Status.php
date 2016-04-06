<?php

class Status extends \Phalcon\Mvc\Model
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    const REMOVED = 3;
    const PARCEL_COLLECTED = 4;
    const PARCEL_IN_TRANSIT = 5;
    const PARCEL_DELIVERED = 6;
    const PARCEL_CANCELLED = 7;
    const PARCEL_FOR_SWEEPER = 8;
    const PARCEL_ARRIVAL = 9;
    const PARCEL_FOR_DELIVERY = 10;
    const PARCEL_UNCLEARED = 11;
    const PARCEL_CLEARED = 12;
    const PARCEL_BEING_DELIVERED = 13;
    const TELLER_AWAITING_APPROVAL = 14;
    const TELLER_APPROVED = 15;
    const TELLER_DECLINED = 16;
    const PARCEL_FOR_GROUNDSMAN = 17;
    const MANIFEST_PENDING = 18;
    const MANIFEST_IN_TRANSIT = 19;
    const MANIFEST_RESOLVED = 20;
    const MANIFEST_CANCELLED = 21;
    const MANIFEST_HAS_ISSUE = 22;
    const PARCEL_RETURNED = 23;

    const DELIVERY_ATTEMPTED = 1;
    const RETURNING_TO_ORIGIN = 2;
    const RETURN_READY_FOR_PICKUP = 3;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Address', 'status', array('alias' => 'Address'));
        $this->hasMany('id', 'Admin', 'status', array('alias' => 'Admin'));
        $this->hasMany('id', 'Bank_account', 'status', array('alias' => 'Bank_account'));
        $this->hasMany('id', 'Branch', 'status', array('alias' => 'Branch'));
        $this->hasMany('id', 'Branch_map', 'status', array('alias' => 'Branch_map'));
        $this->hasMany('id', 'Company', 'status', array('alias' => 'Company'));
        $this->hasMany('id', 'Company', 'status', array('alias' => 'Company'));
        $this->hasMany('id', 'Company_user', 'status', array('alias' => 'Company_user'));
        $this->hasMany('id', 'Company_user', 'status', array('alias' => 'Company_user'));
        $this->hasMany('id', 'Corporate_lead', 'status', array('alias' => 'Corporate_lead'));
        $this->hasMany('status_id', 'Country', 'status_id', array('alias' => 'Country'));
        $this->hasMany('id', 'Parcel', 'status', array('alias' => 'Parcel'));
        $this->hasMany('id', 'Parcel_history', 'status', array('alias' => 'Parcel_history'));
        $this->hasMany('id', 'State', 'active_fg', array('alias' => 'State'));
        $this->hasMany('id', 'User', 'status', array('alias' => 'User'));
        $this->hasMany('id', 'User', 'status', array('alias' => 'User'));
    }

    /**
     * @return Status[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Status
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
            'name' => 'name'
        );
    }

}
