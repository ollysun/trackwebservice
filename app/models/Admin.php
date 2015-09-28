<?php

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Admin extends \Phalcon\Mvc\Model
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
    protected $user_auth_id;

    /**
     *
     * @var string
     */
    protected $fullname;

    /**
     *
     * @var integer
     */
    protected $role_id;

    /**
     *
     * @var integer
     */
    protected $branch_id;

    /**
     *
     * @var string
     */
    protected $phone;

    /**
     *
     * @var string
     */
    protected $staff_id;

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
     * Method to set the value of field user_auth_id
     *
     * @param integer $user_auth_id
     * @return $this
     */
    public function setUserAuthId($user_auth_id)
    {
        $this->user_auth_id = $user_auth_id;

        return $this;
    }

    /**
     * Method to set the value of field fullname
     *
     * @param string $fullname
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->fullname = Text::removeExtraSpaces(strtolower($fullname));

        return $this;
    }

    /**
     * Method to set the value of field role_id
     *
     * @param integer $role_id
     * @return $this
     */
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;

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
     * Method to set the value of field phone
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = trim($phone);

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
        $this->staff_id = strtoupper(trim($staff_id));

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field user_auth_id
     *
     * @return integer
     */
    public function getUserAuthId()
    {
        return $this->user_auth_id;
    }

    /**
     * Returns the value of field fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Returns the value of field role_id
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->role_id;
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
     * Returns the value of field phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('role_id', 'Role', 'id', array('alias' => 'Role'));
        $this->belongsTo('branch_id', 'Branch', 'id', array('alias' => 'Branch'));
    }

    /**
     * @return Admin[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Admin
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
            'user_auth_id' => 'user_auth_id',
            'fullname' => 'fullname', 
            'role_id' => 'role_id',
            'branch_id' => 'branch_id',
            'phone' => 'phone', 
            'staff_id' => 'staff_id', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
        );
    }


    public function initData($branch_id, $role_id, $staff_id, $auth_id, $fullname, $phone){
        $this->setUserAuthId($auth_id);
        $this->setBranchId($branch_id);
        $this->setRoleId($role_id);
        $this->setStaffId($staff_id);
        $this->setFullname($fullname);
        $this->setPhone($phone);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
    }

    public function createWithAuth($branch_id, $role_id, $staff_id, $email, $password, $fullname, $phone)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);

            $auth = new UserAuth();
            $auth->setTransaction($transaction);
            $auth->initData($email, $password, UserAuth::ENTITY_TYPE_ADMIN);
            if ($auth->save()){
                $this->initData($branch_id, $role_id, $staff_id, $auth->getId(), $fullname, $phone);
                if ($this->save()){
                    $transactionManager->commit();
                    return true;
                }
            }
        } catch (Exception $e) {
        }

        $transactionManager->rollback();
        return false;
    }

    public function changeDetails($branch_id, $role_id, $staff_id, $fullname, $phone)
    {
        $this->setBranchId($branch_id);
        $this->setRoleId($role_id);
        $this->setStaffId($staff_id);
        $this->setFullname($fullname);
        $this->setPhone($phone);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeBranch($branch_id){
        $this->setBranchId($branch_id);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'user_auth_id' => $this->getUserAuthId(),
            'fullname' => $this->getFullname(),
            'role_id' => $this->getRoleId(),
            'branch_id' => $this->getBranchId(),
            'phone' => $this->getPhone(),
            'staff_id' => $this->getStaffId(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
        );
    }

    public static function fetchLoginData($user_auth_id){
        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Admin.*', 'Role.*', 'Branch.*'])
            ->from('Admin')
            ->innerJoin('Role', 'Admin.role_id = Role.id')
            ->innerJoin('Branch', 'Admin.branch_id = Branch.id')
            ->where('(user_auth_id = :user_auth_id:)');

        $data = $builder->getQuery()->execute(['user_auth_id' => $user_auth_id]);

        if (count($data) == 0){
            return false;
        }
        $admin = $data[0]->admin->toArray();
        $admin['branch'] = $data[0]->branch->toArray();
        $admin['role'] = $data[0]->role->toArray();

        return $admin;
    }

    /**
     * Get the Admin model attributed to the idenfier (either email or staff id)
     * @param $email - Email of the admin
     * @param $staff_id - Staff id
     * @param $not_id - Used to exclude an admin id on search, useful for change the email or staff id of an admin
     * @return object - containing userAuth and admin properties
     */
    public static function fetchByIdentifier($email, $staff_id, $not_id = null){
        $id_condition = (empty($not_id)) ? '' : ' AND UserAuth.id != :id:';
        $bind = (empty($not_id)) ? [] : ['id' => $not_id];
        $bind['email'] = $email;
        $bind['staff_id'] = $staff_id;

        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Admin.*', 'UserAuth.*'])
            ->from('UserAuth')
            ->innerJoin('Admin', 'Admin.user_auth_id = UserAuth.id')
            ->where('Admin.staff_id = :staff_id: OR UserAuth.email = :email:' . $id_condition, $bind);

        $data = $builder->getQuery()->execute();

        if (count($data) == 0){
            return false;
        }

        return $data[0];
    }

    public static function fetchAll($offset, $count, $filter_by=array()){
        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Admin.*', 'Role.*', 'Branch.*', 'UserAuth.*'])
            ->from('Admin')
            ->innerJoin('Role', 'Admin.role_id = Role.id')
            ->innerJoin('Branch', 'Admin.branch_id = Branch.id')
            ->innerJoin('UserAuth', 'UserAuth.id = Admin.user_auth_id')
            ->limit($count, $offset);

        $bind = [];
        $where = [];

        if (isset($filter_by['role_id'])) {
            $where[] = 'role_id = :role_id:';
            $bind['role_id'] = $filter_by['role_id'];
        }

        if (isset($filter_by['branch_id'])) {
            $where[] = 'branch_id = :branch_id:';
            $bind['branch_id'] = $filter_by['branch_id'];
        }

        if (isset($filter_by['status'])) {
            $where[] = 'UserAuth.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        if (isset($filter_by['staff_id'])){
            $where[] = 'staff_id LIKE :staff_id:';
            $bind['staff_id'] = '%' . strtoupper(trim($filter_by['staff_id'])) . '%';
        }

        if (isset($filter_by['email'])){
            $where[] = 'email LIKE :email:';
            $bind['email'] = '%' . strtolower(trim($filter_by['email'])) . '%';
        }

        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach($data as $item){
            $admin = $item->admin->getData();
            $admin['email'] = $item->userAuth->getEmail();
            $admin['status'] = $item->userAuth->getStatus();
            $admin['branch'] = $item->branch->toArray();
            $admin['role'] = $item->role->toArray();
            $result[] = $admin;
        }

        return $result;
    }

    public static function fetchOne($filter_by){
        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Admin.*', 'Role.*', 'Branch.*', 'UserAuth.*'])
            ->from('Admin')
            ->innerJoin('Role', 'Admin.role_id = Role.id')
            ->innerJoin('Branch', 'Admin.branch_id = Branch.id')
            ->innerJoin('UserAuth', 'UserAuth.id = Admin.user_auth_id');

        if (isset($filter_by['staff_id'])){
            $builder->where('Admin.staff_id = :staff_id:', ['staff_id' => strtoupper(trim($filter_by['staff_id']))]);
        } else if (isset($filter_by['email'])){
            $builder->where('UserAuth.email = :email:', ['email' => strtolower(trim($filter_by['email']))]);
        } else if (isset($filter_by['id'])){
            $builder->where('Admin.id = :id:', ['id' => $filter_by['id']]);
        }

        $data = $builder->getQuery()->execute();

        if (count($data) == 0){
            return false;
        }
        $admin = $data[0]->admin->getData();
        $admin['email'] = $data[0]->userAuth->getEmail();
        $admin['status'] = $data[0]->userAuth->getStatus();
        $admin['branch'] = $data[0]->branch->toArray();
        $admin['role'] = $data[0]->role->toArray();

        return $admin;
    }

    public static function getById($id, $role_id=null){
        $condition = 'id = :id: AND status = :status:';
        $bind = array('id' => $id, 'status' => Status::ACTIVE);

        if ($role_id != null){
            $condition .= ' AND role_id=:role_id:';
            $bind['role_id'] = $role_id;
        }
        return Admin::findFirst(array(
            $condition,
            'bind' => $bind
        ));
    }
}
