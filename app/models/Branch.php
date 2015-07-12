<?php

class Branch extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    protected $branch_type;

    /**
     *
     * @var string
     */
    protected $address;

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
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field branch_type
     *
     * @param integer $branch_type
     * @return $this
     */
    public function setBranchType($branch_type)
    {
        $this->branch_type = $branch_type;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * Returns the value of field branch_type
     *
     * @return integer
     */
    public function getBranchType()
    {
        return $this->branch_type;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
        $this->hasMany('id', 'Admin', 'branch_id', array('alias' => 'Admin'));
        $this->hasMany('id', 'Branch_map', 'child_id', array('alias' => 'Branch_map'));
        $this->hasMany('id', 'Branch_map', 'parent_id', array('alias' => 'Branch_map'));
        $this->hasMany('id', 'Parcel_history', 'branch_id', array('alias' => 'Parcel_history'));
        $this->belongsTo('branch_type', 'Branch_type', 'id', array('alias' => 'Branch_type'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return Branch[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Branch
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
            'branch_type' => 'branch_type', 
            'address' => 'address', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'branch_type' => $this->getBranchType(),
            'address' => $this->getAddress(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    /**
     * @param $branch_id
     * @return null | Branch
     */
    public static function getParentById($branch_id){
        $obj = new Branch();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('Branch.*')
            ->from('Branch')
            ->innerJoin('BranchMap', 'BranchMap.parent_id = Branch.id')
            ->where('BranchMap.child_id = :branch_id:');

        $data = $builder->getQuery()->execute(['branch_id' => $branch_id]);

        if (count($data) == 0){
            return null;
        }

        return $data[0];
    }

    public static function fetchById($branch_id){
        return Branch::findFirst(array(
            'id = :id:',
            'bind' => ['id' => $branch_id]
        ));
    }
}
