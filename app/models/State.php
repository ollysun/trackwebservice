<?php

class State extends \Phalcon\Mvc\Model
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
    protected $country_id;

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
     * Method to set the value of field country_id
     *
     * @param integer $country_id
     * @return $this
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field country_id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->country_id;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('active_fg', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('created_by', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('country_id', 'Country', 'id', array('alias' => 'Country'));
        $this->belongsTo('region_id', 'Region', 'id', array('alias' => 'Region'));
    }

    /**
     * @return State[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return State
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
            'country_id' => 'country_id',
            'region_id' => 'region_id',
            'name' => 'name'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'country_id' => $this->getCountryId(),
            'region_id' => $this->getRegionId(),
            'name' => $this->getName()
        );
    }

    public function changeLocation($region_id, $country_id){
        $this->setRegionId($region_id);
        $this->setCountryId($country_id);
    }

    public static function fetchAll($filter_by, $fetch_with){
        $obj = new State();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('State')
            ->orderBy('State.name');

        $where = [];
        $columns = ['State.*'];
        $bind = [];
        if (isset($filter_by['country_id'])){
            $where[] = 'State.country_id = :country_id:';
            $bind['country_id'] = $filter_by['country_id'];
        }else if (isset($filter_by['region_id'])){
            $where[] = 'State.region_id = :region_id:';
            $bind['region_id'] = $filter_by['region_id'];
        }

        if (isset($fetch_with['with_region'])){
            $columns[] = 'Region.*';
            $builder->innerJoin('Region', 'Region.id = State.region_id');
        }

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item){
            $state = [];
            if (!isset($item->state)){
                $state = $item->getData();
            }else{
                $state = $item->state->getData();
                if (isset($fetch_with['with_region'])){
                    $state['region'] = $item->region->getData();
                }
            }
            $result[] = $state;
        }
        return $result;
    }

    public static function fetchOne($state_id){
        return State::findFirst([
            'id = :id:',
            'bind' => ['id' => $state_id]
        ]);
    }
}
