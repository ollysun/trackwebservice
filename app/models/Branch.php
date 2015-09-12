<?php
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Branch extends \Phalcon\Mvc\Model
{
    const HUB_CODE_PREFIX = 'hub';
    const EC_CODE_PREFIX = 'ec';
    const OTHERS_PREFIX = 'x';
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
     * @var string
     */
    protected $code;

    /**
     *
     * @var integer
     */
    protected $branch_type;

    /**
     *
     * @var integer
     */
    protected $state_id;

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
        $this->name = Text::removeExtraSpaces(strtolower($name));

        return $this;
    }

    /**
     * Method to set the value of field code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = strtolower(trim($code));

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
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = Text::removeExtraSpaces($address);

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
     * Returns the value of field code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
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
     * Returns the value of field state_id
     *
     * @return integer
     */
    public function getStateId()
    {
        return $this->state_id;
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
            'code' => 'code',
            'branch_type' => 'branch_type',
            'state_id' => 'state_id',
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
            'code' => $this->getCode(),
            'branch_type' => $this->getBranchType(),
            'state_id' => $this->getStateId(),
            'address' => $this->getAddress(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($name, $branch_type, $state_id, $address, $status){
        $this->setName($name);
        $this->setCode(uniqid());
        $this->setBranchType($branch_type);
        $this->setStateId($state_id);
        $this->setAddress($address);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus($status);
    }

    public function generateCode(){
        $code = '';

        switch($this->branch_type){
            case BranchType::EC:
                $code = self::EC_CODE_PREFIX;
                break;
            case BranchType::HUB:
                $code = self::HUB_CODE_PREFIX;
                break;
            default:
                $code = self::OTHERS_PREFIX;
        }

        $this->setCode($code . str_pad($this->getId(), 3, '0', STR_PAD_LEFT));
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function saveBranch($hub_id=null){
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            if ($this->save()){
                $this->generateCode();
                if ($this->save()){
                    $check = true;
                    if ($hub_id != null and $this->getBranchType() == BranchType::EC){
                        $branch_map = new BranchMap();
                        $branch_map->setTransaction($transaction);
                        $branch_map->initData($this->getId(), $hub_id);
                        $check = ($branch_map->save());
                    }

                    if ($check){
                        $transactionManager->commit();
                        return true;
                    }
                }
            }
        }catch(Exception $e){

        }
        $transactionManager->rollback();
        return false;
    }

    public function editDetails($name, $state_id, $address){
        $this->setName($name);
        $this->setStateId($state_id);
        $this->setAddress($address);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeStatus($status){
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
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

    public static function fetchAllEC($hub_id){
        $obj = new Branch();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Branch.*', 'State.*'])
            ->from('Branch')
            ->innerJoin('State', 'Branch.state_id = State.id')
            ->innerJoin('BranchMap', 'BranchMap.child_id = Branch.id')
            ->where('BranchMap.parent_id = :hub_id: AND Branch.branch_type = :branch_type: AND Branch.status = :status:')
            ->orderBy('Branch.name');

        $data = $builder->getQuery()->execute(['hub_id' => $hub_id, 'branch_type' => BranchType::EC, 'status' => Status::ACTIVE]);

        $result = [];
        foreach($data as $item){
            $branch = $item->branch->getData();
            $branch['state'] = $item->state->getData();
            $result[] = $branch;
        }
        return $result;
    }

    public static function fetchAllHub(){
        $obj = new Branch();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Branch.*', 'State.*'])
            ->from('Branch')
            ->innerJoin('State', 'Branch.state_id = State.id')
            ->where('Branch.branch_type = :branch_type: AND Branch.status = :status:')
            ->orderBy('Branch.name');

        $data = $builder->getQuery()->execute(['branch_type' => BranchType::HUB, 'status' => Status::ACTIVE]);

        $result = [];
        foreach($data as $item){
            $branch = $item->branch->getData();
            $branch['state'] = $item->state->getData();
            $result[] = $branch;
        }
        return $result;
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with){
        $obj = new Branch();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Branch.*', 'State.*'])
            ->from('Branch')
            ->innerJoin('State', 'Branch.state_id = State.id')
            ->limit($count, $offset)
            ->orderBy('Branch.name');

        $where = [];
        $bind = [];

        if (isset($filter_by['state_id'])){ $where[] = 'Branch.state_id = :state_id:'; $bind['state_id'] = $filter_by['state_id'];}
        if (isset($filter_by['branch_type'])){ $where[] = 'Branch.branch_type = :branch_type:'; $bind['branch_type'] = $filter_by['branch_type'];}

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach($data as $item){
            $branch = $item->branch->getData();
            $parent = Branch::getParentById($item->branch->getId());
            if(isset($fetch_with['with_parent'])) {
                $branch['parent'] = ($parent == null) ? null : $parent->getData();
            }
            $branch['state'] = $item->state->getData();
            $result[] = $branch;
        }
        return $result;
    }

    public static function fetchOne($filter_by){
        $obj = new Branch();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Branch.*', 'State.*'])
            ->from('Branch')
            ->innerJoin('State', 'Branch.state_id = State.id');

        $bind = array();
        if (isset($filter_by['branch_id'])){
            $builder->where('Branch.id = :branch_id:');
            $bind['branch_id'] = $filter_by['branch_id'];
        }else if (isset($filter_by['code'])){
            $builder->where('Branch.code = :code:');
            $bind['code'] = trim($filter_by['code']);
        }

        $builder->andWhere('Branch.status = :status:');
        $bind['status'] = Status::ACTIVE;

        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0){
            return null;
        }

        $result = $data[0]->branch->getData();
        $parent = Branch::getParentById($data[0]->branch->getId());
        $result['parent'] = ($parent == null) ? null : $parent->getData();
        $result['state'] = $data[0]->state->getData();

        return $result;
    }
}
