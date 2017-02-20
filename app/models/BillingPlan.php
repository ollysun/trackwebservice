<?php

class BillingPlan extends \Phalcon\Mvc\Model
{
    const TYPE_WEIGHT = 1;
    const TYPE_ON_FORWARDING = 2;
    const TYPE_NUMBER = 3;
    const TYPE_WEIGHT_AND_ON_FORWARDING = 4;

    const DEFAULT_ON_FORWARDING_PLAN = 2;
    const DEFAULT_WEIGHT_RANGE_PLAN = 2600; // 2600;// 2565;

    public static function getDefaultBillingPlan(){
        return getenv('DEFAULT_WEIGHT_RANGE_PLAN') !== false ? getenv('DEFAULT_WEIGHT_RANGE_PLAN') : self::DEFAULT_WEIGHT_RANGE_PLAN;
    }

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $company_id;

    /**
     *
     * @var integer
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     * @var double
     */
    protected $discount;

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
     * Method to set the value of field company_id
     *
     * @param integer $company_id
     * @return $this
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;

        return $this;
    }

    /**
     * Method to set the value of field type
     *
     * @param integer $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

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

    public function setDiscount($discount)
    {
        $this->discount = $discount;
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
     * Returns the value of field company_id
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * Returns the value of field type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
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

    public function getDiscount()
    {
        return $this->discount;
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
        $this->belongsTo('company_id', 'Company', 'id', array('alias' => 'Company'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return BillingPlan[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return BillingPlan
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
            'company_id' => 'company_id',
            'type' => 'type',
            'name' => 'name',
            'discount' => 'discount',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'status' => 'status'
        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'type' => $this->getType(),
            'name' => $this->getName(),
            'discount' => $this->getDiscount(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $name
     * @param $type
     * @param $discount
     * @param null $company_id - null if it is a default plan
     */
    public function initData($name, $type, $company_id = null, $discount = 0)
    {
        $this->setName($name);
        $this->setType($type);
        if($company_id && $company_id > 0){
            $this->setCompanyId($company_id);
        }
        $this->setDiscount($discount);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::ACTIVE);
    }

    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $name
     * @param $status
     * @param $discount
     */
    public function edit($name, $status, $discount = 0)
    {
        $this->setName($name);
        $this->setStatus($status);
        $this->setDiscount($discount);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $status
     */
    public function changeStatus($status)
    {
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    /**
     * Changes the status of billing plan belonging to company with companyId
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $companyId
     * @param $status
     * @return
     */
    public static function changeMultipleStatus($companyId, $status)
    {
        $model = new self();
        $connection = $model->getWriteConnection();
        $status = $connection->update($model->getSource(), ['status'], [$status], "company_id = $companyId");
        return $status;
    }


    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $name
     * @return bool
     */
    public function hasSameName($name)
    {
        $name = Text::removeExtraSpaces(strtolower($name));
        return $this->getName() == $name;
    }

    public static function fetchByName($name, $company_id, $id = null)
    {
        $name = Text::removeExtraSpaces(strtolower($name));
        $bind = ['name' => $name, 'company_id' => $company_id];
        $id_condition = ($id == null) ? '' : ' AND id != :id:';
        if ($id != null) {
            $bind['id'] = $id;
        }

        return BillingPlan::findFirst([
            'name = :name: AND company_id = :company_id:' . $id_condition,
            'bind' => $bind
        ]);
    }

    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $id
     * @return BillingPlan
     */
    public static function fetchById($id)
    {
        return BillingPlan::findFirst([
            'id = :id:',
            'bind' => ['id' => $id]
        ]);
    }

    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $id
     * @return bool
     */
    public static function fetchOne($id)
    {
        $obj = new BillingPlan();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['BillingPlan.*', 'Company.*'])
            ->from('BillingPlan')
            ->leftJoin('Company', 'Company.id = BillingPlan.company_id')
            ->where('BillingPlan.id = :id:', ['id' => $id]);

        $data = $builder->getQuery()->getSingleResult();

        if (empty($data)) {
            return false;
        }

        $result = $data->billingPlan->getData();

        $billing_company = $data->billingPlan->getCompanyId();
        $result['company'] = (empty($billing_company)) ? null : $data->company->getData();

        return $result;
    }

    /**
     * Prepares the where conditions and the bind params for the fetching queries
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $filter_by
     * @return array
     */
    public static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];
        if (isset($filter_by['company_id'])) {
            if (!is_null($filter_by['company_id'])) {
                $where[] = 'BillingPlan.company_id = :company_id:';
                $bind['company_id'] = $filter_by['company_id'];
            }
        }

        if (isset($filter_by['company_only'])) {
            $where[] = 'BillingPlan.company_id IS NOT NULL';
        }

        if (isset($filter_by['company_name'])) {
            $where[] = 'Company.name LIKE :name:';
            $bind['name'] = '%' . strtolower(trim($filter_by['company_name'])) . '%';
        }

        if (isset($filter_by['plan_name'])) {
            $where[] = 'name LIKE :plan_name:';
            $bind['plan_name'] = '%' . strtolower(trim($filter_by['plan_name'])) . '%';
        }


        if (isset($filter_by['type'])) {
            $where[] = 'BillingPlan.type = :type:';
            $bind['type'] = $filter_by['type'];
        }

        if (isset($filter_by['status'])) {
            $where[] = 'BillingPlan.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        return ['where' => $where, 'bind' => $bind];
    }

    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with)
    {
        $obj = new BillingPlan();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('BillingPlan')
            ->orderBy('BillingPlan.name');

        if (!isset($filter_by['no_paginate'])) {
            $builder->limit($count, $offset);
        }

        $columns = ['BillingPlan.*'];

        //filters
        $filter_cond = self::filterConditions($filter_by);
        if (isset($filter_by['company_name']) || isset($fetch_with['with_company'])) {
            $builder->leftJoin('Company', 'Company.id = BillingPlan.company_id');
        }
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        //model hydration
        if (isset($fetch_with['with_company'])) {
            $columns[] = 'Company.*';
        }

        if(isset($fetch_with['linked_companies_count'])){
            $columns[] = 'COUNT(CompanyBillingPlan.company_id) AS linked_companies_count';
            $builder->leftJoin('CompanyBillingPlan', 'BillingPlan.id = CompanyBillingPlan.billing_plan_id');

            $builder->groupBy('BillingPlan.id');
        }

        $builder->where(join(' AND ', $where), $bind);

        $builder->columns($columns);


        $data = $builder->getQuery()->execute();
        $result = [];
        foreach ($data as $item) {
            $plan = [];
            if (!isset($item->billingPlan)) {
                $plan = $item->getData();
            } else {
                $plan = $item->billingPlan->getData();
                $billing_company = $item->billingPlan->getCompanyId();
                if (isset($fetch_with['with_company'])) {
                    $plan['company'] = (empty($billing_company)) ? null : $item->company->getData();
                }
            }

            if(isset($fetch_with['linked_companies_count'])){
                $plan['linked_companies_count'] = $item->linked_companies_count;
            }
            $result[] = $plan;
        }

        return $result;
    }

    /**
     * @author Abdul-Rahman Shitu <rahman@cottacush.com>
     * @param $filter_by
     * @return int|null
     */
    public static function fetchCount($filter_by)
    {
        $obj = new BillingPlan();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS plan_count')
            ->from('BillingPlan');

        if (isset($filter_by['company_name'])) {
            $builder->leftJoin('Company', 'Company.id = BillingPlan.company_id');
        }
        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where), $bind);
        $data = $builder->getQuery()->getSingleResult();

        if (empty($data)) {
            return null;
        }

        return intval($data->plan_count);
    }

    /**
     * Clones default billing
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $newBillingPlanId
     * @return bool
     */
    public static function cloneDefaultBilling($newBillingPlanId)
    {
        $obj = new BillingPlan();
        $obj->getWriteConnection()->execute("CALL PopulateWeight($newBillingPlanId)");
        $obj->getWriteConnection()->execute("CALL PopulateOnforwarding($newBillingPlanId)");
    }

    /**
     * Update Onforwarding charges to zero
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function resetOnforwardingChargesToZero()
    {
        $connection = (new self())->getWriteConnection();
        $status = $connection->update('onforwarding_charge', ['amount', 'percentage', 'modified_date'], [0, 0, Util::getCurrentDateTime()], "billing_plan_id = " . $this->getId());
        return $status;
    }


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $baseBillingPlanId
     * @param $newBillingPlan
     * @return bool
     */
    public function cloneBillingPlan($baseBillingPlanId, $newBillingPlan)
    {
        $result = true;
        $this->clonePricingAndWeightRanges($baseBillingPlanId, $newBillingPlan->getId());
        //if using weight default, use onforwarding default
        $chargeToUseId = $baseBillingPlanId == 1?2:$baseBillingPlanId;
        $onForwardingCharges = OnforwardingCharge::findByBillingPlanId($chargeToUseId);
        foreach ($onForwardingCharges as $onForwardingCharge) {
            /**
             * @var OnforwardingCharge $onForwardingCharge
             */
            $newOnforwardingCharge = new OnforwardingCharge();
            $newOnforwardingCharge->initData($onForwardingCharge->getName(), $onForwardingCharge->getCode(), $onForwardingCharge->getDescription(), $onForwardingCharge->getAmount(), $onForwardingCharge->getPercentage(), $newBillingPlan->getId());
            $result = $newOnforwardingCharge->save();
            if(!$result){
                return $result;
            }
            $onforwardingChargeId = $newOnforwardingCharge->getId();
            $onforwardingCities = OnforwardingCity::findByOnforwardingChargeId($onForwardingCharge->getId());
            foreach ($onforwardingCities as $onforwardingCity) {
                /**
                 * @var OnforwardingCity $onforwardingCity
                 */
                $newOnforwardingCity = new OnforwardingCity();
                $newOnforwardingCity->initData($onforwardingCity->getCityId(), $onforwardingChargeId);
                $result = $newOnforwardingCity->save();
                if(!$result){
                    return $result;
                }
            }
        }
        return $result;
    }


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $baseBillingPlanId
     * @param $newBillingPlanId
     */
    public function clonePricingAndWeightRanges($baseBillingPlanId, $newBillingPlanId)
    {
        $weightRanges = WeightRange::findByBillingPlanId($baseBillingPlanId);
        foreach ($weightRanges as $weightRange) {
            $newWeightRange = new WeightRange();
            /**
             * @var WeightRange $weightRange
             */
            $newWeightRange->initData($weightRange->getMinWeight(), $weightRange->getMaxWeight(), $weightRange->getIncrementWeight(), $newBillingPlanId);
            $newWeightRange->save();
            $weightBilings = WeightBilling::findByWeightRangeId($weightRange->getId());
            foreach ($weightBilings as $weightBilling) {
                $newWeightBilling = new WeightBilling();
                /**
                 * @var WeightBilling $weightBilling
                 */
                $newWeightBilling->initData($weightBilling->getZoneId(), $newWeightRange->getId(), $weightBilling->getBaseCost(),
                    $weightBilling->getBasePercentage(), $weightBilling->getIncrementCost(), $weightBilling->getBasePercentage());
                $newWeightBilling->save();
            }
        }
    }
}
