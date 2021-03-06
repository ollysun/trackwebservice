<?php

class OnforwardingCharge extends \Phalcon\Mvc\Model
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
    protected $billing_plan_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var string
     */
    protected $code;

    /**
     *
     * @var double
     */
    protected $amount;

    /**
     *
     * @var double
     */
    protected $percentage;

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
     * Method to set the value of field billing_plan_id
     *
     * @param integer $billing_plan_id
     * @return $this
     */
    public function setBillingPlanId($billing_plan_id)
    {
        $this->billing_plan_id = $billing_plan_id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = Text::removeExtraSpaces(strtolower($name));

        return $this;
    }

    public function setPercentage($percentage){
        $this->percentage = $percentage;
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
        $this->description = Text::removeExtraSpaces($description);

        return $this;
    }

    /**
     * Method to set the value of field code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = Text::removeExtraSpaces(strtoupper($code));

        return $this;
    }

    /**
     * Method to set the value of field amount
     *
     * @param double $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

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
     * Returns the value of field billing_plan_id
     *
     * @return integer
     */
    public function getBillingPlanId()
    {
        return $this->billing_plan_id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Returns the value of field code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the value of field amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    public function getPercentage(){
        return $this->percentage;
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
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return OnforwardingCharge[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return OnforwardingCharge
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
            'billing_plan_id' => 'billing_plan_id',
            'name' => 'name',
            'description' => 'description',
            'code' => 'code',
            'amount' => 'amount',
            'percentage' => 'percentage',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'billing_plan_id' => $this->getBillingPlanId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'code' => $this->getCode(),
            'amount' => $this->getAmount(),
            'percentage' => $this->getPercentage(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($name, $code, $description, $amount, $percentage, $billing_plan_id){
        $this->setBillingPlanId($billing_plan_id);
        $this->setName($name);
        $this->setDescription($description);
        $this->setCode($code);
        $this->setAmount($amount);
        $this->setPercentage($percentage);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::ACTIVE);
    }

    public function edit($name, $code, $description, $amount, $percentage){
        $this->setName($name);
        $this->setDescription($description);
        $this->setCode($code);
        $this->setAmount($amount);
        $this->setPercentage($percentage);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeStatus($status){
        $this->setStatus($status);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function hasSameName($name){
        $name = Text::removeExtraSpaces(strtolower($name));
        return $this->getName() == $name;
    }

    public function hasSameCode($code){
        $code = Text::removeExtraSpaces(strtoupper($code));
        return $this->getCode() == $code;
    }

    public static function fetchById($id){
        return OnforwardingCharge::findFirst([
            'id = :id:',
            'bind' => ['id' => $id]
        ]);
    }

    public static function fetchByDetails($name, $code, $id = null){
        $name = Text::removeExtraSpaces(strtolower($name));
        $code = Text::removeExtraSpaces(strtoupper($code));

        $bind = ['name' => $name, 'code' => $code];
        $id_condition = ($id == null) ? '' : ' AND id != :id:';
        if ($id != null){
            $bind['id'] = $id;
        }

        return OnforwardingCharge::findFirst([
            '(name = :name: OR code = :code:)' . $id_condition,
            'bind' => $bind
        ]);
    }

    public static function fetchByCode($code){
        $code = Text::removeExtraSpaces(strtoupper($code));

        return OnforwardingCharge::findFirst([
            'code = :code:',
            'bind' => ['code' => $code]
        ]);
    }

    public static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];

        if (isset($filter_by['status'])){
            $where[] = 'OnforwardingCharge.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        if (isset($filter_by['billing_plan_id'])){
            $where[] = 'OnforwardingCharge.billing_plan_id = :billing_plan_id:';
            $bind['billing_plan_id'] = $filter_by['billing_plan_id'];
        }

        return ['where' => $where, 'bind' => $bind];
    }

    public static function fetchAll($offset, $count, $filter_by){
        $obj = new OnforwardingCharge();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('OnforwardingCharge');

        if (!isset($filter_by['send_all'])) {
            $builder->limit($count, $offset);
        }

        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);
        return $data->toArray();
    }

    public static function chargeCount($filter_by){
        $obj = new OnforwardingCharge();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS charge_count')
            ->from('OnforwardingCharge');

        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->charge_count);
    }

    /**
     * @param $city_id
     * @param $billing_plan_id
     * @return OnforwardingCharge
     */
    public static function fetchByCity($city_id, $billing_plan_id){
        $obj = new OnforwardingCharge();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('OnforwardingCharge')
            ->innerJoin('OnforwardingCity', 'OnforwardingCity.onforwarding_charge_id = OnforwardingCharge.id')
            ->where('OnforwardingCity.city_id = :city_id: AND OnforwardingCharge.billing_plan_id = :billing_plan_id:',
                ['city_id' => $city_id, 'billing_plan_id' => $billing_plan_id]
            );

        return $builder->getQuery()->getSingleResult();
    }
}
