<?php

class ZoneMatrix extends \Phalcon\Mvc\Model
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
    protected $to_branch_id;

    /**
     *
     * @var integer
     */
    protected $from_branch_id;

    /**
     *
     * @var integer
     */
    protected $zone_id;

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
     * Method to set the value of field zone_id
     *
     * @param integer $zone_id
     * @return $this
     */
    public function setZoneId($zone_id)
    {
        $this->zone_id = $zone_id;

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
     * Returns the value of field to_branch_id
     *
     * @return integer
     */
    public function getToBranchId()
    {
        return $this->to_branch_id;
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
     * Returns the value of field zone_id
     *
     * @return integer
     */
    public function getZoneId()
    {
        return $this->zone_id;
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
        $this->belongsTo('to_branch_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('from_branch_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('zone_id', 'Zone', 'id', array('alias' => 'Zone'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return ZoneMatrix[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return ZoneMatrix
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
            'to_branch_id' => 'to_branch_id', 
            'from_branch_id' => 'from_branch_id', 
            'zone_id' => 'zone_id', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'to_branch_id' => $this->getToBranchId(),
            'from_branch_id' => $this->getFromBranchId(),
            'zone_id' => $this->getZoneId(),
            'status' => $this->getStatus()
        );
    }

    public function initData($to_branch_id, $from_branch_id, $zone_id){
        $this->setToBranchId($to_branch_id);
        $this->setFromBranchId($from_branch_id);
        $this->setZoneId($zone_id);

        $this->setStatus(Status::ACTIVE);
    }

    public function changeZone($zone_id){
        $this->setZoneId($zone_id);
    }

    public static function fetchById($id){
        return ZoneMatrix::findFirst([
            'id = :id:',
            'bind' => ['id' => $id]
        ]);
    }

    public static function fetchByLink($to_branch_id, $from_branch_id){
        return ZoneMatrix::findFirst([
            '(
            (ZoneMatrix.to_branch_id = :branch_id: AND ZoneMatrix.from_branch_id = :other_branch_id:)
            OR (ZoneMatrix.to_branch_id = :other_branch_id: AND ZoneMatrix.from_branch_id = :branch_id:)
            )',
            'bind' => ['branch_id' => $to_branch_id, 'other_branch_id' => $from_branch_id]
        ]);
    }

    /**
     * @param $matrix_info - [to_branch_id, from_branch_id, zone_id]
     * @return array
     */
    public static function saveMatrix($matrix_info){
        $bad_matrix_info = [];
        foreach ($matrix_info as $item){
            try {
                $cell = ZoneMatrix::fetchByLink($item['to_branch_id'], $item['from_branch_id']);
                if ($cell == false) {
                    $cell = new ZoneMatrix();
                    $cell->initData($item['to_branch_id'], $item['from_branch_id'], $item['zone_id']);
                } else {
                    $cell->setZoneId($item['zone_id']);
                }
                $cell->save();
            } catch (Exception $e) {
                $bad_matrix_info[] = $item;
            }
        }
        return $bad_matrix_info;
    }

    public static function fetchAll($filter_by){
        $obj = new ZoneMatrix();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['ZoneMatrix.*', 'ToBranch.*', 'ToBranchState.*', 'FromBranch.*', 'FromBranchState.*', 'Zone.*'])
            ->from('ZoneMatrix')
            ->innerJoin('FromBranch', 'FromBranch.id = ZoneMatrix.from_branch_id', 'FromBranch')
            ->innerJoin('FromBranchState', 'FromBranchState.id = FromBranch.state_id', 'FromBranchState')
            ->innerJoin('ToBranch', 'ToBranch.id = ZoneMatrix.to_branch_id', 'ToBranch')
            ->innerJoin('ToBranchState', 'ToBranchState.id = ToBranch.state_id', 'ToBranchState')
            ->innerJoin('Zone', 'Zone.id = ZoneMatrix.zone_id');

        $where = [];
        $bind = [];

        if (isset($filter_by['branch_id']) and isset($filter_by['other_branch_id'])){
            $where[] = '(
            (ZoneMatrix.to_branch_id = :branch_id: AND ZoneMatrix.from_branch_id = :other_branch_id:)
            OR (ZoneMatrix.to_branch_id = :other_branch_id: AND ZoneMatrix.from_branch_id = :branch_id:)
            )';
            $bind['branch_id'] = $filter_by['branch_id'];
            $bind['other_branch_id'] = $filter_by['other_branch_id'];
        } else if (isset($filter_by['branch_id'])){
            $where[] = '(ZoneMatrix.to_branch_id = :branch_id: OR ZoneMatrix.from_branch_id = :branch_id:)';
            $bind['branch_id'] = $filter_by['branch_id'];
        }
        if (isset($filter_by['zone_id'])){
            $where[] = 'ZoneMatrix.zone_id = :zone_id:';
            $bind['zone_id'] = $filter_by['zone_id'];
        }

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item){
            $matrix_item  = $item->zoneMatrix->getData();
            $matrix_item['to_branch'] = $item->toBranch->getData();
            $matrix_item['to_branch']['state'] = $item->toBranchState->getData();
            $matrix_item['from_branch'] = $item->fromBranch->getData();
            $matrix_item['from_branch']['state'] = $item->fromBranchState->getData();

            $result[] = $matrix_item;
        }
        return $result;
    }
}
