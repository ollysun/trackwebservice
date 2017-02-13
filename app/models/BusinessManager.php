<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 10/30/2016
 * Time: 11:45 AM
 */
class BusinessManager extends Phalcon\Mvc\Model
{
    protected $staff_id;

    protected $region_id;

    protected $name;

    protected $region_name;

    protected $status;

    protected $business_zone_id;

    public function getBusinessZoneId(){
        return $this->business_zone_id;
    }

    public function setBusinessZoneId($business_zone_id){
        $this->setBusinessZoneId($business_zone_id);
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStaffId()
    {
        return $this->staff_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRegionName()
    {
        return $this->region_name;
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setRegionName($region_name)
    {
        $this->region_name = $region_name;

        return $this;
    }

    public function setStaffId($staff_id)
    {
        $this->staff_id = $staff_id;

        return $this;
    }

    public function setRegionId($region_id)
    {
        $this->region_id = $region_id;

        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('region_id', 'Region', 'id', array('alias' => 'Region'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('staff_id', 'Admin', 'staff_id', array('alias' => 'Admin'));
    }

    public function getData(){
        return array(
            'region_id' => $this->getRegionId(),
            'staff_id' => $this->getStaffId(),
            'name' => $this->getName(),
            'region_name' => $this->getRegionName(),
            'status' => $this->getStatus(),
            'business_zone_id' => $this->getBusinessZoneId()
        );
    }

    public function init($data){
        $this->setStaffId($data['staff_id']);
        $this->setRegionId($data['region_id']);
        $this->setName($data['name']);
        $this->setRegionName($data['region_name']);
        $this->setStatus($data['status']);
        $this->setBusinessZoneId($data['business_zone_id']);
    }

    public static function getAll($offset, $count, $filter_by, $paginate = false){
        $obj = new BusinessManager();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('BusinessManager')
            ->orderBy('id DESC');

        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        $where = [];
        $bind = [];
        $columns = ['BusinessManager.*'];

        if (!empty($filter_by['region_id'])) {
            $where[] = 'BusinessManager.region_id = :region_id:';
            $bind['region_id'] = $filter_by['region_id'];
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
            ->from('BusinessManager');

        //filters
        $where = [];
        $bind = [];

        if (!empty($filter_by['region_id'])) {
            $where[] = 'BusinessManager.region_id = :region_id:';
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