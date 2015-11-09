<?php

class WeightBilling extends \Phalcon\Mvc\Model
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
    protected $zone_id;

    /**
     *
     * @var integer
     */
    protected $weight_range_id;

    /**
     *
     * @var double
     */
    protected $base_cost;

    /**
     *
     * @var double
     */
    protected $base_percentage;

    /**
     *
     * @var double
     */
    protected $increment_cost;

    /**
     *
     * @var double
     */
    protected $increment_percentage;

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
     * Method to set the value of field weight_range_id
     *
     * @param integer $weight_range_id
     * @return $this
     */
    public function setWeightRangeId($weight_range_id)
    {
        $this->weight_range_id = $weight_range_id;

        return $this;
    }

    /**
     * Method to set the value of field base_cost
     *
     * @param double $base_cost
     * @return $this
     */
    public function setBaseCost($base_cost)
    {
        $this->base_cost = $base_cost;

        return $this;
    }

    /**
     * Method to set the value of field base_percentage
     *
     * @param double $base_percentage
     * @return $this
     */
    public function setBasePercentage($base_percentage)
    {
        $this->base_percentage = $base_percentage;

        return $this;
    }

    /**
     * Method to set the value of field increment_cost
     *
     * @param double $increment_cost
     * @return $this
     */
    public function setIncrementCost($increment_cost)
    {
        $this->increment_cost = $increment_cost;

        return $this;
    }

    /**
     * Method to set the value of field increment_percentage
     *
     * @param double $increment_percentage
     * @return $this
     */
    public function setIncrementPercentage($increment_percentage)
    {
        $this->increment_percentage = $increment_percentage;

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
     * Returns the value of field zone_id
     *
     * @return integer
     */
    public function getZoneId()
    {
        return $this->zone_id;
    }

    /**
     * Returns the value of field weight_range_id
     *
     * @return integer
     */
    public function getWeightRangeId()
    {
        return $this->weight_range_id;
    }

    /**
     * Returns the value of field base_cost
     *
     * @return double
     */
    public function getBaseCost()
    {
        return $this->base_cost;
    }

    /**
     * Returns the value of field base_percentage
     *
     * @return double
     */
    public function getBasePercentage()
    {
        return $this->base_percentage;
    }

    /**
     * Returns the value of field increment_cost
     *
     * @return double
     */
    public function getIncrementCost()
    {
        return $this->increment_cost;
    }

    /**
     * Returns the value of field increment_percentage
     *
     * @return double
     */
    public function getIncrementPercentage()
    {
        return $this->increment_percentage;
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
        $this->belongsTo('zone_id', 'Zone', 'id', array('alias' => 'Zone'));
        $this->belongsTo('weight_range_id', 'Weight_range', 'id', array('alias' => 'Weight_range'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return WeightBilling[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return WeightBilling
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
            'zone_id' => 'zone_id', 
            'weight_range_id' => 'weight_range_id', 
            'base_cost' => 'base_cost', 
            'base_percentage' => 'base_percentage', 
            'increment_cost' => 'increment_cost', 
            'increment_percentage' => 'increment_percentage', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'zone_id' => $this->getZoneId(),
            'weight_range_id' => $this->getWeightRangeId(),
            'base_cost' => $this->getBaseCost(),
            'base_percentage' => $this->getBasePercentage(),
            'increment_cost' => $this->getIncrementCost(),
            'increment_percentage' => $this->getIncrementPercentage(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($zone_id, $weight_range_id, $base_cost, $base_percentage, $increment_cost, $increment_percentage){
        $this->setZoneId($zone_id);
        $this->setWeightRangeId($weight_range_id);
        $this->setBaseCost($base_cost);
        $this->setBasePercentage($base_percentage);
        $this->setIncrementCost($increment_cost);
        $this->setIncrementPercentage($increment_percentage);
        $this->setStatus(Status::ACTIVE);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
    }

    public function editBilling($base_cost, $base_percentage, $increment_cost, $increment_percentage){
        $this->setBaseCost($base_cost);
        $this->setBasePercentage($base_percentage);
        $this->setIncrementCost($increment_cost);
        $this->setIncrementPercentage($increment_percentage);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function fetchById($weight_billing_id){
        return WeightBilling::findFirst([
            'id = :id:',
            'bind' => ['id' => $weight_billing_id]
        ]);
    }

    public static function fetchByDetails($zone_id, $weight_range_id){
        return WeightBilling::findFirst([
            'zone_id = :zone_id: AND weight_range_id = :weight_range_id:',
            'bind' => ['zone_id' => $zone_id, 'weight_range_id' => $weight_range_id]
        ]);
    }

    public static function fetchOne($id){
        $obj = new WeightBilling();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['WeightBilling.*', 'Zone.*', 'WeightRange.*'])
            ->from('WeightBilling')
            ->innerJoin('Zone', 'Zone.id = WeightBilling.zone_id')
            ->innerJoin('WeightRange', 'WeightRange.id = WeightBilling.weight_range_id')
            ->orderBy('WeightBilling.id')
            ->where('Zone.status = :status: AND WeightRange.status = :status: AND WeightBilling.id = :id:', ['status' => Status::ACTIVE, 'id' => $id]);

        $data = $builder->getQuery()->execute();
        if (count($data) == 0){
            return false;
        }

        $billing = $data[0]->weightBilling->getData();
        $billing['zone'] =  $data[0]->zone->getData();
        $billing['weight_range'] =  $data[0]->weightRange->getData();
        return $billing;
    }

    public static function fetchAll($offset, $count, $filter_by){
        $obj = new WeightBilling();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['WeightBilling.*', 'Zone.*', 'WeightRange.*'])
            ->from('WeightBilling')
            ->innerJoin('Zone', 'Zone.id = WeightBilling.zone_id')
            ->innerJoin('WeightRange', 'WeightRange.id = WeightBilling.weight_range_id')
            ->orderBy('WeightBilling.id');

        $where = ['Zone.status = :status: AND WeightRange.status = :status:'];
        $bind = ['status' => Status::ACTIVE];

        if (!isset($filter_by['send_all'])){
            $builder->limit($count, $offset);
        }
        if (isset($filter_by['zone_id'])){
            $where[] = 'WeightBilling.zone_id = :zone_id:';
            $bind['zone_id'] = $filter_by['zone_id'];
        }
        if (isset($filter_by['weight_range_id'])){
            $where[] = 'WeightBilling.weight_range_id = :weight_range_id:';
            $bind['weight_range_id'] = $filter_by['weight_range_id'];
        }
        if (isset($filter_by['billing_plan_id'])){
            $where[] = 'WeightRange.billing_plan_id = :billing_plan_id:';
            $bind['billing_plan_id'] = $filter_by['billing_plan_id'];
        }

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item){
            $billing = $item->weightBilling->getData();
            $billing['zone'] = $item->zone->getData();
            $billing['weight_range'] = $item->weightRange->getData();

            $result[] = $billing;
        }
        return $result;
    }

    /**
     * @param $from_branch_id
     * @param $to_branch_id
     * @param $weight
     * @param $billing_plan_id
     * @return array|bool
     */
    public static function fetchForCalc($from_branch_id, $to_branch_id, $weight, $billing_plan_id){
        $obj = new WeightBilling();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['WeightBilling.*', 'WeightRange.*'])
            ->from('WeightBilling')
            ->innerJoin('Zone', 'Zone.id = WeightBilling.zone_id')
            ->innerJoin('WeightRange', 'WeightRange.id = WeightBilling.weight_range_id')
            ->innerJoin('ZoneMatrix', 'ZoneMatrix.zone_id = Zone.id');

        $builder->where(
            '
            ((ZoneMatrix.to_branch_id = :branch_id: AND ZoneMatrix.from_branch_id = :other_branch_id:)
            OR (ZoneMatrix.to_branch_id = :other_branch_id: AND ZoneMatrix.from_branch_id = :branch_id:))
            AND (WeightRange.min_weight <= :weight: AND WeightRange.max_weight > :weight:)
            AND WeightRange.billing_plan_id = :billing_plan_id:
            ',
            ['branch_id' => $from_branch_id, 'other_branch_id' => $to_branch_id, 'weight' => $weight, 'billing_plan_id' => $billing_plan_id]
        );

        $data = $builder->getQuery()->execute();

        if (count($data) == 0){
            return false;
        }

        $info['weight_billing'] = $data[0]->weightBilling;
        $info['weight_range'] = $data[0]->weightRange;

        return $info;
    }

    public static function calcBilling($from_branch_id, $to_branch_id, $weight, $billing_plan_id){
        /**
         * @var WeightBilling $weight_billing
         * @var WeightRange $weight_range
         */

        $billing_info = WeightBilling::fetchForCalc($from_branch_id, $to_branch_id, $weight, $billing_plan_id);
        if ($billing_info == false){
            return false;
        }

        $weight_billing = $billing_info['weight_billing'];
        $weight_range = $billing_info['weight_range'];

        $base_billing = round(($weight_billing->getBaseCost() * $weight_billing->getBasePercentage()) + $weight_billing->getBaseCost());

        $increment = ($weight - $weight_range->getMinWeight());
        $increment_steps = ($increment / $weight_range->getIncrementWeight());

        if ($increment_steps > intval($increment_steps)){
            $increment_steps = intval($increment_steps) + 1;
        }

        $incr_billing = round(($increment_steps - 1) * (($weight_billing->getIncrementCost() * $weight_billing->getIncrementPercentage()) + $weight_billing->getIncrementCost()));

        return $base_billing + $incr_billing;
    }
}
