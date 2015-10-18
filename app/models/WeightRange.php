<?php

class WeightRange extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var double
     */
    protected $min_weight;

    /**
     *
     * @var double
     */
    protected $max_weight;

    /**
     *
     * @var double
     */
    protected $increment_weight;

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
     * Method to set the value of field min_weight
     *
     * @param double $min_weight
     * @return $this
     */
    public function setMinWeight($min_weight)
    {
        $this->min_weight = $min_weight;

        return $this;
    }

    /**
     * Method to set the value of field max_weight
     *
     * @param double $max_weight
     * @return $this
     */
    public function setMaxWeight($max_weight)
    {
        $this->max_weight = $max_weight;

        return $this;
    }

    /**
     * Method to set the value of field increment_weight
     *
     * @param double $increment_weight
     * @return $this
     */
    public function setIncrementWeight($increment_weight)
    {
        $this->increment_weight = $increment_weight;

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
     * Returns the value of field min_weight
     *
     * @return double
     */
    public function getMinWeight()
    {
        return $this->min_weight;
    }

    /**
     * Returns the value of field max_weight
     *
     * @return double
     */
    public function getMaxWeight()
    {
        return $this->max_weight;
    }

    /**
     * Returns the value of field increment_weight
     *
     * @return double
     */
    public function getIncrementWeight()
    {
        return $this->increment_weight;
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
        $this->hasMany('id', 'Weight_billing', 'weight_range_id', array('alias' => 'Weight_billing'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return WeightRange[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return WeightRange
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
            'min_weight' => 'min_weight', 
            'max_weight' => 'max_weight', 
            'increment_weight' => 'increment_weight', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'min_weight' => $this->getMinWeight(),
            'max_weight' => $this->getMaxWeight(),
            'increment_weight' => $this->getIncrementWeight(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($min_weight, $max_weight, $increment_weight){
        $this->setMinWeight($min_weight);
        $this->setMaxWeight($max_weight);
        $this->setIncrementWeight($increment_weight);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::ACTIVE);
    }

    public function edit($min_weight, $max_weight, $increment_weight){
        $this->setMinWeight($min_weight);
        $this->setMaxWeight($max_weight);
        $this->setIncrementWeight($increment_weight);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeStatus($status){
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function fetchById($id){
        return WeightRange::findFirst([
            'id = :id:',
            'bind' => ['id' => $id]
        ]);
    }

    public static function fetchAll($offset, $count, $filter_by){
        $obj = new WeightRange();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('WeightRange')
            ->limit($count, $offset)
            ->orderBy('WeightRange.id');

        $where = [];
        $bind = [];

        if (isset($filter_by['status'])){$where[] = 'WeightRange.status = :status:';$bind['status'] = $filter_by['status'];}
        if (isset($filter_by['min_weight'])){$where[] = 'WeightRange.min_weight >= :min_weight:';$bind['min_weight'] = $filter_by['min_weight'];}
        if (isset($filter_by['max_weight'])){$where[] = 'WeightRange.max_weight <= :max_weight:';$bind['max_weight'] = $filter_by['max_weight'];}

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);
        return $data->toArray();
    }

    public static function getIntersectingRange($min_weight, $max_weight, $id = null){
        $min_weight = floatval($min_weight);
        $max_weight = floatval($max_weight);

        $bind = ['min_weight' => $min_weight, 'max_weight' => $max_weight];
        if ($id != null){ $bind['id'] = $id; }

        $id_condition = ($id == null) ? '' : ' AND id != :id:';

        return WeightRange::findFirst([
            '
            ((min_weight <  :min_weight: AND max_weight > :min_weight:)
            OR (min_weight <  :max_weight: AND max_weight > :max_weight:)
            OR (min_weight >=  :min_weight: AND max_weight <= :max_weight:))
            ' . $id_condition,
            'bind' => $bind
        ]);
    }
}
