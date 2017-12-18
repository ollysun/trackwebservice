<?php

class HeldParcel extends \Phalcon\Mvc\Model
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
    protected $manifest_id;

    /**
     *
     * @var integer
     */
    protected $parcel_id;

    /**
     *
     * @var integer
     */
    protected $held_by_id;

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
     * Method to set the value of field manifest_id
     *
     * @param integer $manifest_id
     * @return $this
     */
    public function setManifestId($manifest_id)
    {
        $this->manifest_id = $manifest_id;

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
     * Method to set the value of field held_by_id
     *
     * @param integer $held_by_id
     * @return $this
     */
    public function setHeldById($held_by_id)
    {
        $this->held_by_id = $held_by_id;

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
     * Returns the value of field manifest_id
     *
     * @return integer
     */
    public function getManifestId()
    {
        return $this->manifest_id;
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
     * Returns the value of field held_by_id
     *
     * @return integer
     */
    public function getHeldById()
    {
        return $this->held_by_id;
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
        $this->belongsTo('manifest_id', 'Manifest', 'id', array('alias' => 'Manifest'));
        $this->belongsTo('parcel_id', 'Parcel', 'id', array('alias' => 'Parcel'));
        $this->belongsTo('held_by_id', 'Admin', 'id', array('alias' => 'Admin'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return HeldParcel[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return HeldParcel
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
            'manifest_id' => 'manifest_id',
            'parcel_id' => 'parcel_id', 
            'held_by_id' => 'held_by_id', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'manifest_id' => $this->getManifestId(),
            'parcel_id' => $this->getParcelId(),
            'held_by_id' => $this->getHeldById(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($parcel_id, $held_by_id, $manifest_id){
        $this->setManifestId($manifest_id);
        $this->setParcelId($parcel_id);
        $this->setHeldById($held_by_id);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::PARCEL_UNCLEARED);
    }

    public function clear(){
        $this->setStatus(Status::PARCEL_CLEARED);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function clearedForMovement($parcel_id){
        $check = HeldParcel::findFirst([
            'parcel_id = :parcel_id: AND status = :status:',
            'bind' => ['parcel_id' => $parcel_id, 'status' => Status::PARCEL_UNCLEARED]
        ]);

        return $check == false;
    }

    public static function fetchUncleared($parcel_id, $held_by_id = -1){
        if($held_by_id > 0){
            return HeldParcel::findFirst([
                'parcel_id = :parcel_id: AND held_by_id = :held_by_id: AND status = :status:',
                'bind' => ['parcel_id' => $parcel_id, 'held_by_id' => $held_by_id, 'status' => Status::PARCEL_UNCLEARED]
            ]);
        }else{
            return HeldParcel::findFirst([
                'parcel_id = :parcel_id: AND status = :status:',
                'bind' => ['parcel_id' => $parcel_id, 'status' => Status::PARCEL_UNCLEARED]
            ]);
        }

    }

    /**
     * Gets parcels associated with a manifest
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $manifestId
     * @return array
     */
    public static function fetchManifestParcels($manifestId)
    {
        // Get Columns for parcels table
        $parcelColumns = (new Parcel())->columnMap();
        $columns = [];

        foreach($parcelColumns as $column) {
            $columns[] = 'Parcel.' . $column;
        }

        // Destination
        $columns[] = 'ToBranch.name AS destination_name';
        $columns[] = 'ToBranch.code AS destination_code';

        // Shipper
        $columns[] = 'Shipper.firstname AS shipper_firstname';
        $columns[] = 'Shipper.lastname AS shipper_lastname';

        // Receiver
        $columns[] = 'Receiver.firstname AS receiver_firstname';
        $columns[] = 'Receiver.lastname AS receiver_lastname';

        return HeldParcel::query()
            ->columns($columns)
            ->where('manifest_id = :manifest_id: AND Parcel.is_visible = :is_visible:')
            ->bind(['manifest_id' => $manifestId, 'is_visible' => 1])
            ->innerJoin('Parcel')
            ->leftJoin('ToBranch', 'Parcel.to_branch_id = ToBranch.id')
            ->leftJoin('User', 'Parcel.sender_id = Shipper.id', 'Shipper')
            ->leftJoin('User', 'Parcel.receiver_id = Receiver.id', 'Receiver')
            ->execute()
            ->toArray();
    }
}
