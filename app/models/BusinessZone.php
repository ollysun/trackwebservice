<?php

/**
 * BusinessZone
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2016-12-26, 22:57:52
 */
class BusinessZone extends \Phalcon\Mvc\Model
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
    protected $region_id;

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
     * Method to set the value of field region_id
     *
     * @param integer $region_id
     * @return $this
     */
    public function setRegionId($region_id)
    {
        $this->region_id = $region_id;

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
        $this->name = $name;

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
     * Returns the value of field region_id
     *
     * @return integer
     */
    public function getRegionId()
    {
        return $this->region_id;
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
        $this->belongsTo('region_id', 'Region', 'id', array('alias' => 'Region'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'business_zone';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BusinessZone[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BusinessZone
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
            'region_id' => 'region_id',
            'name' => 'name',
            'description' => 'description',
            'status' => 'status'
        );
    }


    public function getData(){
        return array(
            'id' => $this->getId(),
            'region_id' => $this->getRegionId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus()
        );
    }

    public function init($data){
        $this->setRegionId($data['region_id']);
        $this->setName($data['name']);
        $this->setDescription($data['description']);
        $this->setStatus($data['status']);
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
        $builder->addFrom('BusinessZone', 'BusinessZone');
        $columns = ['BusinessZone.*'];
        $bind = [];
        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        if($fetch_with){
            if($fetch_with['with_region']){
                $builder->leftJoin('Region', 'Region.id = BusinessZone.region_id', 'Region');
                $columns[] = 'Region.*';
            }
        }

        $builder->columns($columns);

        if(isset($filter_by['region_id'])){
            $builder->where("BusinessZone.region_id = :region_id:");
            $bind['region_id'] = $filter_by['region_id'];
        }

        $data = $builder->getQuery()->execute($bind);

        $zones = [];
        foreach ($data as $item) {
            $zones[] = $item->toArray();
        }
        return $zones;
    }

    public static function getAll($offset, $count, $filter_by, $fetch_with, $paginate = false){
        $obj = new BusinessManager();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('BusinessZone')
            ->orderBy('id DESC');

        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        $where = [];
        $bind = [];
        $columns = ['BusinessZone.*'];
        if($fetch_with)
        {
            if($fetch_with['with_region']){
                $columns[] = 'Region.*';
                $builder->innerJoin('Region', 'BusinessZone.region_id = Region.id', 'Region');
            }
        }

        if (!empty($filter_by['region_id'])) {
            $where[] = 'BusinessZone.region_id = :region_id:';
            $bind['region_by'] = $filter_by['region_id'];
        }

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);


        return $data;
    }

    public static function getCount($filter_by = array()){
        $obj = new BusinessManager();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS count')
            ->from('BusinessZone');

        //filters
        $where = [];
        $bind = [];

        if (!empty($filter_by['region_id'])) {
            $where[] = 'BusinessZone.region_id = :region_id:';
            $bind['region_id'] = $filter_by['region_id'];
        }


        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->count);
    }

}