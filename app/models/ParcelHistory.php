<?php

class ParcelHistory extends \Phalcon\Mvc\Model
{
    const MSG_FOR_SWEEPER = 'Parcel ready for sweeping';
    const MSG_FOR_DELIVERY = 'Parcel ready for delivery';
    const MSG_FOR_ARRIVAL = 'Parcel is in arrival';
    const MSG_IN_TRANSIT = 'Parcel is in transit';
    const MSG_BEING_DELIVERED = 'Parcel ready for delivery';
    const MSG_DELIVERED = 'Parcel delivered';
    const MSG_CANCELLED = 'Parcel cancelled';
    const MSG_ASSIGNED_TO_GROUNDSMAN = 'Parcel assigned to the groundsman';

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
    protected $from_branch_id;

    /**
     *
     * @var integer
     */
    protected $to_branch_id;

    /**
     *
     * @var integer
     */
    protected $admin_id;

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
     * @param integer $from_branch_id
     * @return $this
     */
    public function setFromBranchId($from_branch_id)
    {
        $this->from_branch_id = $from_branch_id;

        return $this;
    }

    /**
     * Method to set the value of field branch_id
     *
     * @param integer $to_branch_id
     * @return $this
     */
    public function setToBranchId($to_branch_id)
    {
        $this->to_branch_id = $to_branch_id;

        return $this;
    }

    /**
     * Method to set the value of field admin_id
     *
     * @param integer $admin_id
     * @return $this
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;

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
    public function getFromBranchId()
    {
        return $this->from_branch_id;
    }

    /**
     * Returns the value of field branch_id
     *
     * @return integer
     */
    public function getToBranchId()
    {
        return $this->to_branch_id;
    }

    /**
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
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
        $this->belongsTo('admin_id', 'Admin', 'id', array('alias' => 'Admin'));
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
            'from_branch_id' => 'from_branch_id',
            'to_branch_id' => 'to_branch_id',
            'admin_id' => 'admin_id',
            'status' => 'status',
            'created_date' => 'created_date',
            'description' => 'description'
        );
    }

    public function initData($parcel_id, $from_branch_id, $description, $admin_id, $status, $to_branch_id){
        $this->setParcelId($parcel_id);
        $this->setFromBranchId($from_branch_id);
        $this->setToBranchId($to_branch_id);
        $this->setDescription($description);
        $this->setAdminId($admin_id);

        $this->setStatus($status);
        $this->setCreatedDate(date('Y-m-d H:i:s'));
    }

}
