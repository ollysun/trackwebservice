<?php

/**
 * IntlZone
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2016-12-27, 16:53:18
 */
class IntlZone extends \Phalcon\Mvc\Model
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
    protected $code;

    /**
     *
     * @var string
     */
    protected $description;

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
     * Method to set the value of field code
     *
     * @param integer $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

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
        $this->description = $description;

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
     * Returns the value of field code
     *
     * @return integer
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'IntlTariff', 'intl_zone_id', array('alias' => 'IntlTariff'));
        $this->hasMany('id', 'IntlZoneCountryMap', 'zone_id', array('alias' => 'IntlZoneCountryMap'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'intl_zone';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return IntlZone[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return IntlZone
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
            'code' => 'code',
            'description' => 'description'
        );
    }


    /**
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @param bool|false $paginate
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with, $paginate = false){
        $builder = new \Phalcon\Mvc\Model\Query\Builder();
        $builder->addFrom('IntlZone', 'IntlZone');
        $columns = ['IntlZone.*'];
        $bind = [];
        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        $builder->columns($columns);

        if(isset($filter_by['name'])){
            $builder->where("IntlZone.name like %:name:%");
            $bind['name'] = $filter_by['name'];
        }

        $data = $builder->getQuery()->execute($bind);

        $zones = [];
        foreach ($data as $item) {
            $zones[] = $item->toArray();
        }
        return $zones;
    }

    public static function getCount($filter_by = array()){
        $obj = new BusinessManager();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS count')
            ->from('IntlZone');

        //filters
        $where = [];
        $bind = [];

        if (!empty($filter_by['name'])) {
            $where[] = 'IntlZone.name like %:name:%';
            $bind['name'] = $filter_by['name'];
        }


        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->count);
    }


    public static function calculateBilling($weight, $country_id, $parcel_type_id){
        /** @var Country $country */
        $country = Country::findFirstById($country_id);
        if(!$country){
            return ['success' => false, 'message' => 'Invalid country id'];
        }

        if($parcel_type_id == ShippingType::INTL_ECONOMY_EXPRESS){
            //if the country is in the special countries, use its special price
            if($special_intl_tariff = IntlSpecialCountryTariff::findFirst(['country_id = :country_id:',
                'bind' => ['country_id' => $country_id]])){
                return ['success' => true, 'amount' => $special_intl_tariff->getPrice()];
            }else{
                return ['message' => 'Transit Tariff not available for the selected country', 'success' => false];
            }
        }


        /** @var IntlZoneCountryMap $zone */
        $zone_map = IntlZoneCountryMap::findFirst(['conditions' => 'country_id = :country_id:', 'bind' => ['country_id' => $country_id]]);
        if(!$zone_map){
            return ['success' => false, 'message' => 'Country not mapped'];
        }

        /** @var ParcelType $parcel_type */
        $parcel_type = ShippingType::findFirstById($parcel_type_id);
        if(!$parcel_type){
            return ['success' => false, 'message' => 'Invalid parcel type '];
        }


        $weight_range = IntlWeightRange::findFirst(['conditions' =>
            ':weight: between min_weight AND max_weight', 'bind' => ['weight' => $weight]]);
        if(!$weight_range){
            return ['success' => false, 'message' => 'Weight not in range'];
        }

        $tariff = IntlTariff::findFirst(['conditions' => 'zone_id = :zone_id: AND parcel_type_id = :parcel_type_id:
        AND weight_range_id = :weight_range_id:',
            'bind' => ['zone_id' => $zone_map->getZoneId(), 'parcel_type_id' => $parcel_type_id,
                'weight_range_id' => $weight_range->getId()]]);

        $amount = $tariff->getBaseAmount();

        if(!$tariff){
            //calculate extra
            //$sum = Robots::maximum(array("type='mechanical'", 'column' => 'id'));
            // A raw SQL statement
            $zone_id = $zone_map->getZoneId();
            $sql = "select tariff.base_amount, tariff.weight_range_id, intl_weight_range.max_weight, intl_weight_range.min_weight
                    from intl_tariff as tariff
                    join intl_weight_range on tariff.weight_range_id = intl_weight_range.id
                    where tariff.zone_id = $zone_id AND tariff.parcel_type_id = $parcel_type_id AND tariff.base_amount = (select max(base_amount)
                    from intl_tariff where zone_id = $zone_id AND parcel_type_id = $parcel_type_id)";

            // Base model
            $max_tariff_obj = new IntlZone();

            // Execute the query
            $max_tariff = $max_tariff_obj->getReadConnection()->query($sql, null);
            $max_weight = $max_tariff['min_weight'];
            if($weight < $max_weight)
                return ['success' => false, 'message' => 'Tariff not found'];
            $diff = $weight - $max_weight;
            //get the zone extras
            $zone_extra = IntlExtraKg::findFirst(['zone_id = :zone_id: AND parcel_id = :parcel_id:', 'bind' =>[
                'zone_id' => $zone_id, 'parcel_type_id' => $parcel_type_id
            ]]);
            if(!$zone_extra)
                return ['success' => false, 'message' => 'Tariff not found'];
            //calculate the extra amount
            $extra_amount = $zone_extra['base_amount'] * $diff;
            $amount = $max_tariff['base_amount'] + $extra_amount;
            //return  ['success' => true, 'amount' => $max_tariff['base_amount'] + $extra_amount];
        }

        $fsc = 0.15 * $amount;
        $amount = $amount + $fsc;
        return  ['success' => true, 'amount' => $amount];
    }

}
