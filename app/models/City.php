<?php

class City extends \Phalcon\Mvc\Model
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
    protected $state_id;

    /**
     *
     * @var string
     */
    protected $name;

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
     * Method to set the value of field state_id
     *
     * @param integer $state_id
     * @return $this
     */
    public function setStateId($state_id)
    {
        $this->state_id = $state_id;

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
     * Returns the value of field state_id
     *
     * @return integer
     */
    public function getStateId()
    {
        return $this->state_id;
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
        $this->belongsTo('state_id', 'State', 'id', array('alias' => 'State'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return City[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return City
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
            'state_id' => 'state_id', 
            'name' => 'name', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'state_id' => $this->getStateId(),
            'name' => $this->getName(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($state_id, $name){
        $this->setStateId($state_id);
        $this->setName($name);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::ACTIVE);
    }

    public function edit($state_id, $name){
        $this->setStateId($state_id);
        $this->setName($name);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeStatus($status){
        $this->setStatus($status);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function isExisting($state_id, $name, $city_id = null){
        $bind = ['state_id' => $state_id, 'name' => Text::removeExtraSpaces(strtolower($name))];
        $id_condition = ($city_id == null) ? '' : ' AND id != :id:';

        if ($city_id != null) {$bind['id'] = $city_id;}

        $city = City::findFirst([
            'state_id = :state_id: AND name =:name: ' . $id_condition,
            'bind' => $bind
        ]);

        return $city != false;
    }

    public static function fetchActive($city_id){
        return City::findFirst([
            'id = :id: AND status = :status:',
            'bind' => ['id' => $city_id, 'status' => Status::ACTIVE]
        ]);
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with){
        $obj = new City();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('City')
            ->limit($count, $offset)
            ->orderBy('City.name');

        $where = [];
        $bind = [];
        $columns = ['City.*'];

        if (isset($filter_by['state_id'])){
            $where[] = 'City.state_id = :state_id:';
            $bind['state_id'] = $filter_by['state_id'];
        }
        if (isset($filter_by['country_id'])){
            $where[] = 'State.country_id = :country_id:';
            $bind['country_id'] = $filter_by['country_id'];
        }
        if (isset($filter_by['status'])){
            $where[] = 'City.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        if (isset($fetch_with['with_state']) or isset($fetch_with['with_country'])){
            $columns[] = 'State.*';
            $builder->innerJoin('State', 'State.id = City.state_id');
        }

        if (isset($fetch_with['with_region'])){
            $columns[] = 'Region.*';
            $builder->innerJoin('Region', 'Region.id = State.region_id');
        }

        if (isset($fetch_with['with_country'])){
            $columns[] = 'Country.*';
            $builder->innerJoin('Country', 'Country.id = State.country_id');
        }

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item){
            $city = [];
            if (!isset($item->city)){
                $city = $item->getData();
            }else{
                $city = $item->city->getData();
                if (isset($fetch_with['with_country'])){
                    $city['country'] = $item->country->getData();
                }
                if (isset($fetch_with['with_state'])){
                    $city['state'] = $item->state->getData();
                }
                if (isset($fetch_with['with_region'])){
                    $city['region'] = $item->region->getData();
                }
            }
            $result[] = $city;
        }

        return $result;
    }

    public static function fetchOne($city_id, $fetch_with){
        $obj = new City();
        $builder = $obj->getModelsManager()->createBuilder()
            ->where('City.id = :id: AND status = :status:', ['id' => $city_id, 'status' => Status::ACTIVE])
            ->from('City');

        $columns = ['City.*'];

        if (isset($fetch_with['with_state']) or isset($fetch_with['with_country'])){
            $columns[] = 'State.*';
            $builder->innerJoin('State', 'State.id = City.state_id');
        }

        if (isset($fetch_with['with_region'])){
            $columns[] = 'Region.*';
            $builder->innerJoin('Region', 'Region.id = State.region_id');
        }

        if (isset($fetch_with['with_country'])){
            $columns[] = 'Country.*';
            $builder->innerJoin('Country', 'Country.id = State.country_id');
        }

        $builder->columns($columns);
        $data = $builder->getQuery()->execute();

        if (count($data) == 0){
            return false;
        }

        $city = [];
        if (!isset($data[0]->city)){
            $city = $data[0]->getData();
        }else{
            $city = $data[0]->city->getData();
            if (isset($fetch_with['with_country'])){
                $city['country'] = $data[0]->country->getData();
            }
            if (isset($fetch_with['with_state'])){
                $city['state'] = $data[0]->state->getData();
            }
            if (isset($fetch_with['with_region'])){
                $city['region'] = $data[0]->region->getData();
            }
        }

        return $city;
    }
}
