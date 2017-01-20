<?php

/**
 * ReturnedShipmentTellerParcel
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2017-01-09, 16:16:48
 */
class RtdTellerParcel extends \Phalcon\Mvc\Model
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
    protected $parcel_id;

    /**
     *
     * @var string
     */
    protected $teller_id;

    /**
     *
     * @var string
     */
    protected $created_date;

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
     * Method to set the value of field parcel_id
     *
     * @param string $parcel_id
     * @return $this
     */
    public function setParcelId($parcel_id)
    {
        $this->parcel_id = $parcel_id;

        return $this;
    }

    /**
     * Method to set the value of field teller_id
     *
     * @param string $teller_id
     * @return $this
     */
    public function setTellerId($teller_id)
    {
        $this->teller_id = $teller_id;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field parcel_id
     *
     * @return string
     */
    public function getParcelId()
    {
        return $this->parcel_id;
    }

    /**
     * Returns the value of field teller_id
     *
     * @return string
     */
    public function getTellerId()
    {
        return $this->teller_id;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('teller_id', 'RtdTeller', 'id', array('alias' => 'RtdTeller'));
        $this->belongsTo('parcel_id', 'Parcel', 'id', array('alias' => 'Parcel'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'returned_shipment_teller_parcel';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RtdTellerParcel[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RtdTellerParcel
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'parcel_id' => 'parcel_id',
            'teller_id' => 'teller_id',
            'created_date' => 'created_date'
        );
    }


    public function initData($teller_id, $parcel_id){
        $this->setTellerId($teller_id);
        $this->setParcelId($parcel_id);
        $this->setCreatedDate(date('Y-m-d H:i:s'));
    }

    public static function getParcelTeller($parcel_id){
        return RtdTellerParcel::findFirst([
            'parcel_id = :parcel_id:',
            'bind' => ['parcel_id' => trim($parcel_id)]
        ]);
    }

}