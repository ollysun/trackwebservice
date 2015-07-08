<?php

class Address extends \Phalcon\Mvc\Model
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
    protected $owner_id;

    /**
     *
     * @var integer
     */
    protected $owner_type;

    /**
     *
     * @var string
     */
    protected $street_address1;

    /**
     *
     * @var string
     */
    protected $street_address2;

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
    protected $state_id;

    /**
     *
     * @var string
     */
    protected $city;

    /**
     *
     * @var integer
     */
    protected $country_id;

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
     * Method to set the value of field owner_id
     *
     * @param integer $owner_id
     * @return $this
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    /**
     * Method to set the value of field owner_type
     *
     * @param integer $owner_type
     * @return $this
     */
    public function setOwnerType($owner_type)
    {
        $this->owner_type = $owner_type;

        return $this;
    }

    /**
     * Method to set the value of field street_address1
     *
     * @param string $street_address1
     * @return $this
     */
    public function setStreetAddress1($street_address1)
    {
        $this->street_address1 = Text::removeExtraSpaces($street_address1);

        return $this;
    }

    /**
     * Method to set the value of field street_address2
     *
     * @param string $street_address2
     * @return $this
     */
    public function setStreetAddress2($street_address2)
    {
        $this->street_address2 = Text::removeExtraSpaces($street_address2);

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
     * Method to set the value of field state_id
     *
     * @param integer $state_id
     * @return $this
     */
    public function setStateId($state_id)
    {
        $this->state_id = $state_id;

        return $this;
    }

    /**
     * Method to set the value of field city
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = trim($city);

        return $this;
    }

    /**
     * Method to set the value of field country_id
     *
     * @param integer $country_id
     * @return $this
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;

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
     * Returns the value of field owner_id
     *
     * @return integer
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * Returns the value of field owner_type
     *
     * @return integer
     */
    public function getOwnerType()
    {
        return $this->owner_type;
    }

    /**
     * Returns the value of field street_address1
     *
     * @return string
     */
    public function getStreetAddress1()
    {
        return $this->street_address1;
    }

    /**
     * Returns the value of field street_address2
     *
     * @return string
     */
    public function getStreetAddress2()
    {
        return $this->street_address2;
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
     * Returns the value of field state_id
     *
     * @return integer
     */
    public function getStateId()
    {
        return $this->state_id;
    }

    /**
     * Returns the value of field city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Returns the value of field country_id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->country_id;
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
        $this->hasMany('id', 'Parcel', 'sender_address_id', array('alias' => 'Parcel'));
        $this->hasMany('id', 'Parcel', 'receiver_address_id', array('alias' => 'Parcel'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('country_id', 'Country', 'id', array('alias' => 'Country'));
    }

    /**
     * @return Address[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Address
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
            'owner_id' => 'owner_id', 
            'owner_type' => 'owner_type', 
            'street_address1' => 'street_address1', 
            'street_address2' => 'street_address2', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'state_id' => 'state_id', 
            'city' => 'city', 
            'country_id' => 'country_id', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'owner_id' => $this->getOwnerId(),
            'owner_type' => $this->getOwnerType(),
            'street_address1' => $this->getStreetAddress1(),
            'street_address2' => $this->getStreetAddress2(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'state_id' => $this->getStateId(),
            'city' => $this->getCity(),
            'country_id' => $this->getCountryId(),
            'status' => $this->getStatus()
        );
    }

    public function initData($owner_id, $owner_type, $street_address1, $street_address2, $state_id, $country_id, $city, $is_existing=false){
        $this->setOwnerId($owner_id);
        $this->setOwnerType($owner_type);
        $this->setStreetAddress1($street_address1);
        $this->setStreetAddress2($street_address2);
        $this->setStateId($state_id);
        $this->setCountryId($country_id);
        $this->setCity($city);

        $now = date('Y-m-d H:i:s');
        if (!$is_existing){
            $this->setCreatedDate($now);
            $this->setStatus(Status::ACTIVE);
        }
        $this->setModifiedDate($now);
    }

    public function edit($street_address1, $street_address2, $state_id, $country_id, $city){
        $this->setStreetAddress1($street_address1);
        $this->setStreetAddress2($street_address2);
        $this->setStateId($state_id);
        $this->setCountryId($country_id);
        $this->setCity($city);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeStatus($status){
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function fetchActive($id, $owner_id, $owner_type){
        return Address::findFirst([
            'id = :id: AND owner_id = :owner_id: AND owner_type = :owner_type:',
            'bind' => ['owner_id' => $owner_id, 'owner_type' => $owner_type, 'id' => $id]
        ]);
    }

    public static function fetchAll($offset, $count, $filter_by = array(), $fetch_with = array()){
        $obj = new Address();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('Address.*')
            ->from('Address')
            ->limit($count, $offset);

        $bind = [];
        $where = ['Address.status = '.Status::ACTIVE];

        if (isset($filter_by['owner_id'])) {
            $where[] = 'owner_id = :owner_id:';
            $bind['owner_id'] = $filter_by['owner_id'];
        }

        if (isset($filter_by['owner_type'])) {
            $where[] = 'owner_type = :owner_type:';
            $bind['owner_type'] = $filter_by['owner_type'];
        }

        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach($data as $item){
            $result[] = $item->getData();
        }
        return $result;
    }

    public static function fetchById($id){
        return Address::findFirst(array(
            'id = :id:',
            'bind' => ['id' => $id]
        ));
    }
}
