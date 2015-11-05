<?php

class OnforwardingCity extends EagerModel
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
    protected $city_id;

    /**
     *
     * @var integer
     */
    protected $onforwarding_charge_id;

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
     * Method to set the value of field city_id
     *
     * @param integer $city_id
     * @return $this
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    /**
     * Method to set the value of field onforwarding_charge_id
     *
     * @param integer $onforwarding_charge_id
     * @return $this
     */
    public function setOnforwardingChargeId($onforwarding_charge_id)
    {
        $this->onforwarding_charge_id = $onforwarding_charge_id;

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
     * Returns the value of field city_id
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Returns the value of field onforwarding_charge_id
     *
     * @return integer
     */
    public function getOnforwardingChargeId()
    {
        return $this->onforwarding_charge_id;
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
        $this->belongsTo('city_id', 'City', 'id', array('alias' => 'City'));
        $this->belongsTo('onforwarding_charge_id', 'Onforwarding_charge', 'id', array('alias' => 'Onforwarding_charge'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return OnforwardingCity[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return OnforwardingCity
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
            'city_id' => 'city_id',
            'onforwarding_charge_id' => 'onforwarding_charge_id',
            'status' => 'status'
        );
    }

    public function initData($city_id, $onforwarding_charge_id)
    {
        $this->setCityId($city_id);
        $this->setOnforwardingChargeId($onforwarding_charge_id);
        $this->setStatus(Status::ACTIVE);
    }

    public static function fetchLink($city_id, $onforwarding_charge_id)
    {
        return self::findFirst([
            'city_id = :city_id: AND onforwarding_charge_id = :onforwarding_charge_id:',
            ['city_id' => $city_id, 'onforwarding_charge_id' => $onforwarding_charge_id]
        ]);
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $billingPlanId
     * @param $count
     * @param $offset
     * @param $fetch_with
     * @param $filter_by
     * @param bool $paginate
     * @return array
     */
    public static function getAll($billingPlanId, $count, $offset, $fetch_with, $filter_by, $paginate = true)
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('OnforwardingCity');
        $columns = ['OnforwardingCity.*'];

        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder->where('OnforwardingCharge.billing_plan_id = :billing_plan_id:', ['billing_plan_id' => $billingPlanId]);

        $builder = self::addFetchCriteria($builder, $filter_by);
        $builder = $builder->columns($columns);

        if($paginate) {
            $builder->limit($count)->offset($offset);
        }

        $result = $builder->getQuery()->execute();

        $cities = [];
        foreach ($result as $data) {
            $city = (property_exists($data, 'onforwardingCity')) ? $data->onforwardingCity->toArray() : $data->toArray();

            $relatedRecords = $obj->loadRelatedModels($data, true);
            $city = array_merge($city, $relatedRecords);

            $cities[] = $city;
        }

        return $cities;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $filter_by
     */
    public static function getTotalCount($filter_by)
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()->from('OnforwardingCity');
        $builder->innerJoin('OnforwardingCharge', 'OnforwardingCharge.id = OnforwardingCity.onforwarding_charge_id');
        $columns = ['COUNT(*) as count'];
        $builder = self::addFetchCriteria($builder, $filter_by);
        $count = $builder->columns($columns)->getQuery()->getSingleResult();
        return $count['count'];
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $builder
     * @param $filter_by
     * @return Model\Query\Builder|Model\Query\BuilderInterface
     */
    private static function addFetchCriteria($builder, $filter_by)
    {
        if (isset($filter_by['billing_plan_id'])) {
            $builder->where('OnforwardingCharge.billing_plan_id = :billing_plan_id:', ['billing_plan_id' => $filter_by['billing_plan_id']]);
        }

        return $builder;
    }

    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getFetchWithMap()
    {
        return [
            [
                'field' => 'city',
                'model_name' => self::class,
                'ref_model_name' => 'City',
                'foreign_key' => 'city_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'onforwarding_charge',
                'model_name' => self::class,
                'ref_model_name' => 'OnforwardingCharge',
                'foreign_key' => 'onforwarding_charge_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'state',
                'model_name' => City::class,
                'ref_model_name' => 'State',
                'foreign_key' => 'state_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'branch',
                'model_name' => City::class,
                'ref_model_name' => Branch::class,
                'foreign_key' => 'branch_id',
                'reference_key' => 'id'
            ],
        ];
    }
}
