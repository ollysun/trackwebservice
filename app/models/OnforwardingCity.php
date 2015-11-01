<?php

class OnforwardingCity extends \Phalcon\Mvc\Model
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
    protected $city_id;

    /**
     *
     * @var integer
     */
    protected $onforwarding_charge_id;

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
     * Method to set the value of field onforwarding_charge_id
     *
     * @param integer $onforwarding_charge_id
     * @return $this
     */
    public function setOnforwardingChargeId($onforwarding_charge_id)
    {
        $this->onforwarding_charge_id = $onforwarding_charge_id;

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
     * Returns the value of field city_id
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Returns the value of field onforwarding_charge_id
     *
     * @return integer
     */
    public function getOnforwardingChargeId()
    {
        return $this->onforwarding_charge_id;
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
        $this->belongsTo('city_id', 'City', 'id', array('alias' => 'City'));
        $this->belongsTo('onforwarding_charge_id', 'Onforwarding_charge', 'id', array('alias' => 'Onforwarding_charge'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return OnforwardingCity[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return OnforwardingCity
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
            'city_id' => 'city_id', 
            'onforwarding_charge_id' => 'onforwarding_charge_id', 
            'status' => 'status'
        );
    }

    public function initData($city_id, $onforwarding_charge_id)
    {
        $this->setCityId($city_id);
        $this->setOnforwardingChargeId($onforwarding_charge_id);
        $this->setStatus(Status::ACTIVE);
    }

    public static function fetchLink($city_id, $onforwarding_charge_id)
    {
        return self::findFirst([
            'city_id = :city_id: AND onforwarding_charge_id = :onforwarding_charge_id:',
            ['city_id' => $city_id, 'onforwarding_charge_id' => $onforwarding_charge_id]
        ]);
    }
}
