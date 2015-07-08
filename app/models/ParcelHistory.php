<?php

class ParcelHistory extends \Phalcon\Mvc\Model
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
    protected $parcel_id;

    /**
     *
     * @var integer
     */
    protected $branch_id;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var string
     */
    protected $created_date;

    /**
     *
     * @var string
     */
    protected $description;

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
     * @param integer $parcel_id
     * @return $this
     */
    public function setParcelId($parcel_id)
    {
        $this->parcel_id = $parcel_id;

        return $this;
    }

    /**
     * Method to set the value of field branch_id
     *
     * @param integer $branch_id
     * @return $this
     */
    public function setBranchId($branch_id)
    {
        $this->branch_id = $branch_id;

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
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * @return integer
     */
    public function getParcelId()
    {
        return $this->parcel_id;
    }

    /**
     * Returns the value of field branch_id
     *
     * @return integer
     */
    public function getBranchId()
    {
        return $this->branch_id;
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
     * Returns the value of field created_date
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('parcel_id', 'Parcel', 'id', array('alias' => 'Parcel'));
        $this->belongsTo('branch_id', 'Branch', 'id', array('alias' => 'Branch'));
    }

    /**
     * @return ParcelHistory[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return ParcelHistory
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
            'parcel_id' => 'parcel_id', 
            'branch_id' => 'branch_id', 
            'status' => 'status', 
            'created_date' => 'created_date', 
            'description' => 'description'
        );
    }

    public function initData($parcel_id, $branch_id, $description){
        $this->setParcelId($parcel_id);
        $this->setBranchId($branch_id);
        $this->setDescription($description);

        $this->setStatus(Status::ACTIVE);
        $this->setCreatedDate(date('Y-m-d H:i:s'));
    }

}
