<?php

/**
 * BmCentre
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2017-01-20, 14:49:43
 */
class BmCentre extends \Phalcon\Mvc\Model
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
    protected $staff_id;

    /**
     *
     * @var integer
     */
    protected $branch_id;

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
     * Method to set the value of field staff_id
     *
     * @param string $staff_id
     * @return $this
     */
    public function setStaffId($staff_id)
    {
        $this->staff_id = $staff_id;

        return $this;
    }

    /**
     * Method to set the value of field branch_id
     *
     * @param integer $branch_id
     * @return $this
     */
    public function setBranchId($branch_id)
    {
        $this->branch_id = $branch_id;

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
     * Returns the value of field staff_id
     *
     * @return string
     */
    public function getStaffId()
    {
        return $this->staff_id;
    }

    /**
     * Returns the value of field branch_id
     *
     * @return integer
     */
    public function getBranchId()
    {
        return $this->branch_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('staff_id', 'Admin', 'staff_id', array('alias' => 'Admin'));
        $this->belongsTo('branch_id', 'Branch', 'id', array('alias' => 'Branch'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'bm_centre';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BmCentre[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BmCentre
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
            'staff_id' => 'staff_id',
            'branch_id' => 'branch_id'
        );
    }

    public function init($data){
        $this->setBranchId($data['branch_id']);
        $this->setStaffId($data['staff_id']);
    }

    public function getData(){
        return array('id' => $this->getId(), 'branch_id' => $this->getBranchId(), 'staff_id' => $this->getStaffId());
    }

    /**
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @param bool|false $paginate bhh
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with, $paginate = false){
        $builder = new \Phalcon\Mvc\Model\Query\Builder();
        $builder->addFrom('BmCentre');
        $columns = ['BmCentre.*'];
        $bind = [];
        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        $builder->columns($columns);

        if(isset($filter_by['branch_id'])){
            $builder->where("BmCentre.branch_id = :branch_id:");
            $bind['branch_id'] = $filter_by['branch_id'];
        }
        if(isset($filter_by['staff_id'])){
            $builder->where("BmCentre.staff_id = :staff_id:");
            $bind['staff_id'] = $filter_by['staff_id'];
        }

        if(isset($fetch_with['with_branch'])){
            $columns[] = 'Branch.*';
            $builder->innerJoin('Branch', 'Branch.id = BmCentre.branch_id');
        }
        if(!empty($fetch_with['with_staff'])){
            $builder->innerJoin('Admin', 'Admin.staff_id = BmCentre.staff_id', 'Admin');
        }

        $builder->columns($columns);
        $data = $builder->getQuery()->execute($bind);

        $zones = [];
        foreach ($data as $item) {
            $zones[] = $item->toArray();
        }
        return $zones;
    }

}
