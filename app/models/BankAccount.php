<?php

class BankAccount extends \Phalcon\Mvc\Model
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
    protected $owner_id;

    /**
     *
     * @var integer
     */
    protected $owner_type;

    /**
     *
     * @var integer
     */
    protected $bank_id;

    /**
     *
     * @var string
     */
    protected $account_name;

    /**
     *
     * @var string
     */
    protected $account_no;

    /**
     *
     * @var string
     */
    protected $sort_code;

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
     * Method to set the value of field owner_id
     *
     * @param integer $owner_id
     * @return $this
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    /**
     * Method to set the value of field owner_type
     *
     * @param integer $owner_type
     * @return $this
     */
    public function setOwnerType($owner_type)
    {
        $this->owner_type = $owner_type;

        return $this;
    }

    /**
     * Method to set the value of field bank_id
     *
     * @param integer $bank_id
     * @return $this
     */
    public function setBankId($bank_id)
    {
        $this->bank_id = $bank_id;

        return $this;
    }

    /**
     * Method to set the value of field account_name
     *
     * @param string $account_name
     * @return $this
     */
    public function setAccountName($account_name)
    {
        $this->account_name = strtolower(Text::removeExtraSpaces($account_name));

        return $this;
    }

    /**
     * Method to set the value of field account_no
     *
     * @param string $account_no
     * @return $this
     */
    public function setAccountNo($account_no)
    {
        $this->account_no = trim($account_no);

        return $this;
    }

    /**
     * Method to set the value of field sort_code
     *
     * @param string $sort_code
     * @return $this
     */
    public function setSortCode($sort_code)
    {
        $this->sort_code = trim($sort_code);

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
     * Returns the value of field owner_id
     *
     * @return integer
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * Returns the value of field owner_type
     *
     * @return integer
     */
    public function getOwnerType()
    {
        return $this->owner_type;
    }

    /**
     * Returns the value of field bank_id
     *
     * @return integer
     */
    public function getBankId()
    {
        return $this->bank_id;
    }

    /**
     * Returns the value of field account_name
     *
     * @return string
     */
    public function getAccountName()
    {
        return $this->account_name;
    }

    /**
     * Returns the value of field account_no
     *
     * @return string
     */
    public function getAccountNo()
    {
        return $this->account_no;
    }

    /**
     * Returns the value of field sort_code
     *
     * @return string
     */
    public function getSortCode()
    {
        return $this->sort_code;
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
        $this->belongsTo('bank_id', 'Bank', 'id', array('alias' => 'Bank'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return BankAccount[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return BankAccount
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
            'owner_id' => 'owner_id', 
            'owner_type' => 'owner_type', 
            'bank_id' => 'bank_id', 
            'account_name' => 'account_name', 
            'account_no' => 'account_no', 
            'sort_code' => 'sort_code',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'owner_id' => $this->getOwnerId(),
            'owner_type' => $this->getOwnerType(),
            'bank_id' => $this->getBankId(),
            'account_name' => $this->getAccountName(),
            'account_no' => $this->getAccountNo(),
            'sort_code' => $this->getSortCode(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($owner_id, $owner_type, $bank_id, $account_name, $account_no, $sort_code, $is_existing=false){
        $this->setOwnerId($owner_id);
        $this->setOwnerType($owner_type);
        $this->setBankId($bank_id);
        $this->setAccountName($account_name);
        $this->setAccountNo($account_no);
        $this->setSortCode($sort_code);

        $now = date('Y-m-d H:i:s');
        if (!$is_existing){
            $this->setCreatedDate($now);
            $this->setStatus(Status::ACTIVE);
        }
        $this->setModifiedDate($now);
    }

    public function edit($bank_id, $account_name, $account_no, $sort_code){
        $this->setBankId($bank_id);
        $this->setAccountName($account_name);
        $this->setAccountNo($account_no);
        $this->setSortCode($sort_code);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeStatus($status){
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function fetchActive($id, $owner_id, $owner_type){
        return BankAccount::findFirst([
            'id = :id: AND owner_id = :owner_id: AND owner_type = :owner_type:',
            'bind' => ['owner_id' => $owner_id, 'owner_type' => $owner_type, 'id' => $id]
        ]);
    }

    public static function fetchAll($offset, $count, $filter_by = array(), $fetch_with = array()){
        $obj = new BankAccount();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['BankAccount.*', 'Bank.*'])
            ->from('BankAccount')
            ->innerJoin('Bank', 'Bank.id = BankAccount.bank_id')
            ->limit($count, $offset);

        $bind = [];
        $where = ['BankAccount.status = '.Status::ACTIVE];

        if (isset($filter_by['owner_id'])) {
            $where[] = 'BankAccount.owner_id = :owner_id:';
            $bind['owner_id'] = $filter_by['owner_id'];
        }

        if (isset($filter_by['owner_type'])) {
            $where[] = 'BankAccount.owner_type = :owner_type:';
            $bind['owner_type'] = $filter_by['owner_type'];
        }

        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);
        $result = [];
        foreach($data as $item){
            $account = $item->bankAccount->getData();
            $account['bank'] = $item->bank->toArray();
            $result[] = $account;
        }
        return $result;
    }

    public static function fetchById($id){
        return BankAccount::findFirst(array(
            'id = :id:',
            'bind' => ['id' => $id]
        ));
    }
}
