<?php

use Phalcon\Exception;

class Zone extends \Phalcon\Mvc\Model
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
    protected $name;

    /**
     *
     * @var string
     */
    protected $code;

    /**
     *
     * @var string
     */
    protected $description;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
        $this->hasMany('id', 'WeightBilling', 'zone_id', array('alias' => 'WeightBilling'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return Zone[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Zone
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
            'name' => 'name', 
            'code' => 'code', 
            'description' => 'description', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'description' => $this->getDescription(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function hasSameName($name){
        $name = Text::removeExtraSpaces(strtolower($name));
        return $this->getName() == $name;
    }

    public function hasSameCode($code){
        $code = Text::removeExtraSpaces(strtoupper($code));
        return $this->getCode() == $code;
    }

    public function initData($name, $code, $description){
        $this->setName($name);
        $this->setCode($code);
        $this->setDescription($description);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::ACTIVE);
    }

    public function edit($name, $code, $description){
        $this->setName($name);
        $this->setCode($code);
        $this->setDescription($description);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeStatus($status){
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function fetchById($zone_id){
        return Zone::findFirst([
            'id = :id:',
            'bind' => ['id' => $zone_id]
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

        return Zone::findFirst([
            '(name = :name: OR code = :code:)' . $id_condition,
            'bind' => $bind
        ]);
    }

    public static function fetchByCode($code){
        $code = Text::removeExtraSpaces(strtoupper($code));

        return Zone::findFirst([
            'code = :code:',
            'bind' => ['code' => $code]
        ]);
    }

    public static function fetchAll($offset, $count, $filter_by){
        $obj = new Zone();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Zone')
            ->limit($count, $offset);

        $where = [];
        $bind = [];

        if (isset($filter_by['status'])){
            $where[] = 'Zone.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);
        return $data->toArray();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $from_branch_id
     * @param $to_branch_id
     * @param $weight
     * @param $billing_plan_id
     * @param $city_id
     * @param $onforwarding_billing_plan_id
     * @param $company_id
     * @param $shipping_type
     * @return bool|float|int
     * @throws Exception
     */
    public static function calculateBilling(
        $from_branch_id,
        $to_branch_id,
        $weight,
        $billing_plan_id,
        $city_id,
        $onforwarding_billing_plan_id, $company_id = null, $shipping_type = null
    ) {
        $billing_plan = BillingPlan::findFirst($billing_plan_id);

        if (!$billing_plan) {
            throw new Exception(ResponseMessage::INVALID_WEIGHT_BILLING_PLAN);
        }

        $calc_weight_billing = WeightBilling::calcBilling(
            $from_branch_id,
            $to_branch_id,
            $weight,
            $billing_plan_id
        );

        if ($calc_weight_billing == false) {
            if (!WeightBilling::zoneMappingExists($from_branch_id, $to_branch_id)) {
                throw new Exception(ResponseMessage::ZONE_MAPPING_ORIGIN_DESTINATION_NOT_EXIST);
            }

            if (WeightBilling::weightExceedsRange($weight, $billing_plan_id)) {
                if (!is_null($billing_plan->getCompanyId())) {
                    return 0;
                }
                throw new Exception(ResponseMessage::WEIGHT_EXCEEDS_SET_LIMIT);
            }

            throw new Exception(ResponseMessage::WEIGHT_BILLING_DOES_NOT_EXIST_FOR_ZONE);
        }

        $onforwarding_charge = OnforwardingCharge::fetchByCity($city_id, $onforwarding_billing_plan_id);
        if ($onforwarding_charge == false) {
            throw new Exception(ResponseMessage::CALC_BILLING_ONFORWARDING);
        }

        $final_billing = $calc_weight_billing + $onforwarding_charge->getAmount();

        //apply discount from the billing plan and company
        $discount_percentage = $billing_plan->getDiscount();
        if($company_id){
            $company = Company::fetchOne(['id' => $company_id], []);
            if($company){
                $discount_percentage += $company['discount'];
            }
        }
        $discount = ($discount_percentage/100) * $final_billing;

        $net_amount = $final_billing - $discount;


        return $net_amount;
    }


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $from_branch_id
     * @param $to_branch_id
     * @param $weight
     * @param $billing_plan_id
     * @param $city_id
     * @param $onforwarding_billing_plan_id
     * @return bool|array
     * @throws Exception
     */
    public static function getQuote(
        $from_branch_id,
        $to_branch_id,
        $weight,
        $billing_plan_id,
        $city_id,
        $onforwarding_billing_plan_id,
        $company_id = null
    ) {
        $billing_plan = BillingPlan::findFirst($billing_plan_id);

        if (!$billing_plan) {
            throw new Exception(ResponseMessage::INVALID_WEIGHT_BILLING_PLAN);
        }

        $calc_weight_billing = WeightBilling::calcBilling(
            $from_branch_id,
            $to_branch_id,
            $weight,
            $billing_plan_id
        );

        if ($calc_weight_billing == false) {
            if (!WeightBilling::zoneMappingExists($from_branch_id, $to_branch_id)) {
                throw new Exception(ResponseMessage::ZONE_MAPPING_ORIGIN_DESTINATION_NOT_EXIST);
            }

            if (WeightBilling::weightExceedsRange($weight, $billing_plan_id)) {
                if (!is_null($billing_plan->getCompanyId())) {
                    return 0;
                }
                throw new Exception(ResponseMessage::WEIGHT_EXCEEDS_SET_LIMIT);
            }

            throw new Exception(ResponseMessage::WEIGHT_BILLING_DOES_NOT_EXIST_FOR_ZONE);
        }

        $onforwarding_charge = OnforwardingCharge::fetchByCity($city_id, $onforwarding_billing_plan_id);
        if ($onforwarding_charge == false) {
            throw new Exception(ResponseMessage::CALC_BILLING_ONFORWARDING);
        }

        $final_billing = $calc_weight_billing + $onforwarding_charge->getAmount();

        //apply discount from the billing plan and company then calc vat for the tranx
        $discount_percentage = $billing_plan->getDiscount();
        if($company_id){
            $company = Company::fetchOne(['id' => $company_id], []);
            if($company){
                $discount_percentage += $company['discount'];
            }
        }
        $pp = ($discount_percentage/100);
        $discount = ($discount_percentage/100) * $final_billing;

        $gross_amount = $final_billing - $discount;

        $vat = (0.05 * $gross_amount);

        $amount_due = $gross_amount + $vat;

        return ['total_amount' => $final_billing, 'discount' => $discount,
            'gross_amount' => $gross_amount, 'vat' => $vat, 'amount_due' => $amount_due,
            'discount_percentage' => $discount_percentage, 'pp' => $pp];
    }
}
