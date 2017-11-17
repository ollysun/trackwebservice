<?php

/**
 * TransitInfo
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2016-07-16, 11:37:45
 */
class TransitInfo extends \Phalcon\Mvc\Model
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
    protected $start_date_time;

    /**
     *
     * @var string
     */
    protected $end_date_time;

    /**
     *
     * @var integer
     */
    protected $held_by;

    /**
     *
     * @var integer
     */
    protected $admin;

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
    protected $transit_time;

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
     * Method to set the value of field start_date_time
     *
     * @param string $start_date_time
     * @return $this
     */
    public function setStartDateTime($start_date_time)
    {
        $this->start_date_time = $start_date_time;

        return $this;
    }

    /**
     * Method to set the value of field end_date_time
     *
     * @param string $end_date_time
     * @return $this
     */
    public function setEndDateTime($end_date_time)
    {
        $this->end_date_time = $end_date_time;

        return $this;
    }

    /**
     * Method to set the value of field held_by
     *
     * @param integer $held_by
     * @return $this
     */
    public function setHeldBy($held_by)
    {
        $this->held_by = $held_by;

        return $this;
    }

    /**
     * Method to set the value of field admin
     *
     * @param integer $admin
     * @return $this
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Method to set the value of field from_branch_id
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
     * Method to set the value of field to_branch_id
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
     * Method to set the value of field transit_time
     *
     * @param integer $transit_time
     * @return $this
     */
    public function setTransitTime($transit_time)
    {
        $this->transit_time = $transit_time;

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
     * Returns the value of field start_date_time
     *
     * @return string
     */
    public function getStartDateTime()
    {
        return $this->start_date_time;
    }

    /**
     * Returns the value of field end_date_time
     *
     * @return string
     */
    public function getEndDateTime()
    {
        return $this->end_date_time;
    }

    /**
     * Returns the value of field held_by
     *
     * @return integer
     */
    public function getHeldBy()
    {
        return $this->held_by;
    }

    /**
     * Returns the value of field admin
     *
     * @return integer
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Returns the value of field from_branch_id
     *
     * @return integer
     */
    public function getFromBranchId()
    {
        return $this->from_branch_id;
    }

    /**
     * Returns the value of field to_branch_id
     *
     * @return integer
     */
    public function getToBranchId()
    {
        return $this->to_branch_id;
    }

    /**
     * Returns the value of field transit_time
     *
     * @return integer
     */
    public function getTransitTime()
    {
        return $this->transit_time;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('parcel_id', 'Parcel', 'id', array('alias' => 'Parcel'));
        $this->belongsTo('held_by', 'Admin', 'id', array('alias' => 'Admin'));
        $this->belongsTo('from_branch_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('to_branch_id', 'Branch', 'id', array('alias' => 'Branch'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'transit_info';
    }

    public function init($parcel_id, $held_by, $admin, $from_id, $to_id, $transit_time){
        $this->setParcelId($parcel_id)
            ->setStartDateTime(date('Y-m-d H:i:s'))
            ->setHeldBy($held_by)
            ->setAdmin($admin)
            ->setFromBranchId($from_id)
            ->setToBranchId($to_id)
            ->setTransitTime($transit_time);
        return $this;
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
            'start_date_time' => 'start_date_time',
            'end_date_time' => 'end_date_time',
            'held_by' => 'held_by',
            'admin' => 'admin',
            'from_branch_id' => 'from_branch_id',
            'to_branch_id' => 'to_branch_id',
            'transit_time' => 'transit_time'
        );
    }

    public static function MarkAsArrived($parcel_id, $from_branch_id, $to_branch_id){
        $transit_info = self::findFirst(array(
            "parcel_id = '$parcel_id' AND from_branch_id = '$from_branch_id' AND to_branch_id = '$to_branch_id'",
            "order" => "id")
        );
        if($transit_info){
            $transit_info->setEndDateTime(date("Y-m-d H:i:s"));
            $transit_info->save();
        }
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TransitInfo[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TransitInfo
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'parcel_id' => $this->getParcelId(),
            'start_date_time' => $this->getStartDateTime(),
            'end_date_time' => $this->getEndDateTime(),
            'held_by' => $this->getHeldBy(),
            'admin' => $this->getAdmin(),
            'from_branch_id' => $this->getFromBranchId(),
            'to_branch_id' => $this->getToBranchId(),
            'transit_time' => $this->getTransitTime()
        );
    }


    public static function fetchAll($filter_by){
        $obj = new TransitInfo();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['TransitInfo.*', 'Parcel.*', 'ToBranch.*', 'FromBranch.*', 'Holder.*'])
            ->from('TransitInfo')
            ->innerJoin('Parcel', 'Parcel.id = TransitInfo.parcel_id', 'Parcel')
            ->innerJoin('FromBranch', 'FromBranch.id = TransitInfo.from_branch_id', 'FromBranch')
            ->innerJoin('ToBranch', 'ToBranch.id = TransitInfo.to_branch_id', 'ToBranch')
            ->innerJoin('Holder', 'Holder.id = TransitInfo.held_by', 'Holder');

        $where = [];
        $bind = [];

        if (!empty($filter_by['branch_id']) and !empty($filter_by['other_branch_id'])){
            $where[] = '(
            (TransitInfo.to_branch_id = :branch_id: AND TransitInfo.from_branch_id = :other_branch_id:)
            OR (TransitInfo.to_branch_id = :other_branch_id: AND TransitInfo.from_branch_id = :branch_id:)
            )';
            $bind['branch_id'] = $filter_by['branch_id'];
            $bind['other_branch_id'] = $filter_by['other_branch_id'];
        } else if (!empty($filter_by['branch_id'])){
            $where[] = '(TransitInfo.from_branch_id = :branch_id:)';
            $bind['branch_id'] = $filter_by['branch_id'];
        } else if (!empty($filter_by['other_branch_id'])){
            $where[] = '(TransitInfo.to_branch_id = :branch_id: )';
            $bind['branch_id'] = $filter_by['other_branch_id'];
        }

        if (!empty($filter_by['held_by'])){
            $where[] = '(TransitInfo.held_by = :held_by: )';
            $bind['held_by'] = $filter_by['held_by'];
        }

        if (!empty($filter_by['start_date'])) {
            $where[] = 'TransitInfo.start_date_time >= :start_date:';
            $start_date = (new DateTime($filter_by['start_date']))->format('y/m/d');
            $bind['start_date'] = $start_date;
        }

        if (!empty($filter_by['end_date'])) {
            $end_date = new DateTime($filter_by['end_date']);
            $end_date = $end_date->add(new DateInterval('P1D'));
            $where[] = 'TransitInfo.start_date_time < :end_date:';
            $bind['end_date'] = $end_date->format('y/m/d');
        }

        //SELECT created_date, modified_date, TIMESTAMPDIFF(HOUR, created_date, modified_date)  as `difference` FROM parcel;

        $where[] = "(TransitInfo.transit_time < FIND_HOUR_DIFFERENCE(TransitInfo.start_date_time, IFNULL(TransitInfo.end_date_time, NOW())))";

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item){
            $transit_time_time  = $item->transitInfo->getData();
            $transit_time_time['parcel'] = $item->parcel->getData();
            $transit_time_time['to_branch'] = $item->toBranch->getData();
            $transit_time_time['from_branch'] = $item->fromBranch->getData();
            $transit_time_time['holder'] = $item->holder->getData();

            $result[] = $transit_time_time;
        }
        return $result;
    }
}
