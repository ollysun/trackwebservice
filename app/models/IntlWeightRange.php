<?php

/**
 * IntlWeightRange
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2016-12-27, 16:53:35
 */
class IntlWeightRange extends \Phalcon\Mvc\Model
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
     * @var double
     */
    protected $increment;

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
     * @return float
     */
    public function getIncrement()
    {
        return $this->increment;
    }

    /**
     * @param float $increment
     */
    public function setIncrement($increment)
    {
        $this->increment = $increment;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'IntlTariff', 'intl_weight_range_id', array('alias' => 'IntlTariff'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'intl_weight_range';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return IntlWeightRange[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return IntlWeightRange
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
            'min_weight' => 'min_weight',
            'max_weight' => 'max_weight',
            'increment' => 'increment'
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
    public static function fetchAll($offset, $count, $paginate = false){
        $builder = new \Phalcon\Mvc\Model\Query\Builder();
        $builder->addFrom('IntlWeightRange', 'IntlWeightRange');
        $columns = ['IntlWeightRange.*'];
        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        $builder->columns($columns);


        $data = $builder->getQuery()->execute();

        $weight_ranges = [];
        foreach ($data as $item) {
            $weight_ranges[] = $item->toArray();
        }
        return $weight_ranges;
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

}
