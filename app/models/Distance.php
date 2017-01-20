<?php

/**
 * Distance
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2017-01-05, 06:13:44
 */
class Distance extends \Phalcon\Mvc\Model
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
    protected $lenght;

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
     * Method to set the value of field lenght
     *
     * @param integer $lenght
     * @return $this
     */
    public function setLenght($lenght)
    {
        $this->lenght = $lenght;

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
     * Returns the value of field lenght
     *
     * @return integer
     */
    public function getLenght()
    {
        return $this->lenght;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
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
        return 'distance';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Distance[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Distance
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
            'from_branch_id' => 'from_branch_id',
            'to_branch_id' => 'to_branch_id',
            'lenght' => 'lenght'
        );
    }


    public function initData($from_branch_id, $to_branch_id, $length){
        $this->setFromBranchId($from_branch_id)
            ->setToBranchId($to_branch_id)
            ->setLenght($length);
    }

    public function getData(){
        return [
            'id' => $this->getId(),
            'to_branch_id' => $this->getToBranchId(),
            'from_branch_id' => $this->getFromBranchId(),
            'length' => $this->getLenght()
        ];
    }


    public static function fetchById($id){
        return Distance::findFirst([
            'id = :id:',
            'bind' => ['id' => $id]
        ]);
    }

    public static function fetchByLink($to_branch_id, $from_branch_id){
        return Distance::findFirst([
            '(
            (Distance.to_branch_id = :branch_id: AND Distance.from_branch_id = :other_branch_id:)
            OR (Distance.to_branch_id = :other_branch_id: AND Distance.from_branch_id = :branch_id:)
            )',
            'bind' => ['branch_id' => $to_branch_id, 'other_branch_id' => $from_branch_id]
        ]);
    }

    /**
     * @param $distance_info - [to_branch_id, from_branch_id, zone_id]
     * @return array
     */
    public static function saveDistance($distance_info){
        $bad_distance_info = [];

        foreach ($distance_info as $item) {
            try {
                $cell = Distance::fetchByLink($item['to_branch_id'], $item['from_branch_id']);
                if ($cell == false) {
                    $cell = new Distance();
                    $cell->initData($item['to_branch_id'], $item['from_branch_id'], $item['length']);
                } else {
                    $cell->setLenght($item['length']);
                }
                $cell->save();
            } catch (Exception $e) {
                $bad_distance_info[] = $item;
            }
        }
        return $bad_distance_info;
    }

    public static function fetchAll($filter_by){
        $obj = new Distance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Distance.*', 'ToBranch.*', 'ToBranchState.*', 'FromBranch.*', 'FromBranchState.*'])
            ->from('Distance')
            ->innerJoin('FromBranch', 'FromBranch.id = Distance.from_branch_id', 'FromBranch')
            ->innerJoin('FromBranchState', 'FromBranchState.id = FromBranch.state_id', 'FromBranchState')
            ->innerJoin('ToBranch', 'ToBranch.id = Distance.to_branch_id', 'ToBranch')
            ->innerJoin('ToBranchState', 'ToBranchState.id = ToBranch.state_id', 'ToBranchState');

        $where = [];
        $bind = [];

        if (isset($filter_by['branch_id']) and isset($filter_by['other_branch_id'])){
            $where[] = '(
            (Distance.to_branch_id = :branch_id: AND Distance.from_branch_id = :other_branch_id:)
            OR (Distance.to_branch_id = :other_branch_id: AND Distance.from_branch_id = :branch_id:)
            )';
            $bind['branch_id'] = $filter_by['branch_id'];
            $bind['other_branch_id'] = $filter_by['other_branch_id'];
        } else if (isset($filter_by['branch_id'])){
            $where[] = '(Distance.to_branch_id = :branch_id: OR Distance.from_branch_id = :branch_id:)';
            $bind['branch_id'] = $filter_by['branch_id'];
        }

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item){
            $distance  = $item->distance->getData();
            $distance['to_branch'] = $item->toBranch->getData();
            $distance['to_branch']['state'] = $item->toBranchState->getData();
            $distance['from_branch'] = $item->fromBranch->getData();
            $distance['from_branch']['state'] = $item->fromBranchState->getData();

            $result[] = $distance;
        }
        return $result;
    }

}