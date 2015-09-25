<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Admin extends \Phalcon\Mvc\Model
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
    protected $fullname;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $password;

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
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = strtolower(trim($email));

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        /**
         * @var \Phalcon\Security $security
         */
        $security = $this->getDI()->getSecurity();
        $this->password = $security->hash($password);

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
     * Returns the value of field fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
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
            'fullname' => 'fullname', 
            'email' => 'email', 
            'password' => 'password', 
            'role_id' => 'role_id',
            'branch_id' => 'branch_id',
            'phone' => 'phone', 
            'staff_id' => 'staff_id', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }


    public function initData($branch_id, $role_id, $staff_id, $email, $password, $fullname, $phone){
        $this->setBranchId($branch_id);
        $this->setRoleId($role_id);
        $this->setStaffId($staff_id);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setFullname($fullname);
        $this->setPhone($phone);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::INACTIVE);
    }

    public function changeDetails($branch_id, $role_id, $staff_id, $email, $fullname, $phone, $status)
    {
        $this->setBranchId($branch_id);
        $this->setRoleId($role_id);
        $this->setStaffId($staff_id);
        $this->setEmail($email);
        $this->setFullname($fullname);
        $this->setPhone($phone);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
        $this->setStatus($status);
    }

    public function changePassword($password){
        $this->setPassword($password);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
        $this->setStatus(Status::ACTIVE);
    }

    public function changeStatus($status){
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function changeBranch($branch_id){
        $this->setBranchId($branch_id);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'fullname' => $this->getFullname(),
            'email' => $this->getEmail(),
            'role_id' => $this->getRoleId(),
            'branch_id' => $this->getBranchId(),
            'phone' => $this->getPhone(),
            'staff_id' => $this->getStaffId(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public static function fetchLoginData($identifier){
        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Admin.*', 'Role.*', 'Branch.*'])
            ->from('Admin')
            ->innerJoin('Role', 'Admin.role_id = Role.id')
            ->innerJoin('Branch', 'Admin.branch_id = Branch.id')
            ->where('(email = :identifier: OR staff_id = :identifier:) AND Admin.status IN ('. Status::ACTIVE . ',' . Status::INACTIVE . ')');

        $data = $builder->getQuery()->execute(['identifier' => $identifier]);

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
     * @return Admin
     */
    public static function fetchByIdentifier($email, $staff_id, $not_id = null){
        $id_condition = (empty($not_id)) ? '' : ' AND id != :id:';
        $bind = (empty($not_id)) ? [] : ['id' => $not_id];
        $bind['email'] = $email;
        $bind['staff_id'] = $staff_id;

        return Admin::findFirst(array(
            '(email = :email: OR staff_id = :staff_id:) ' . $id_condition,
            'bind' => $bind
        ));
    }

    public static function fetchAll($offset, $count, $filter_by=array()){
        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Admin.*', 'Role.*', 'Branch.*'])
            ->from('Admin')
            ->innerJoin('Role', 'Admin.role_id = Role.id')
            ->innerJoin('Branch', 'Admin.branch_id = Branch.id')
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
            $where[] = 'status = :status:';
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
            $admin['branch'] = $item->branch->toArray();
            $admin['role'] = $item->role->toArray();
            $result[] = $admin;
        }

        return $result;
    }

    public static function fetchOne($filter_by){
        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Admin.*', 'Role.*', 'Branch.*'])
            ->from('Admin')
            ->innerJoin('Role', 'Admin.role_id = Role.id')
            ->innerJoin('Branch', 'Admin.branch_id = Branch.id');

        if (isset($filter_by['staff_id'])){
            $builder->where('Admin.staff_id = :staff_id:', ['staff_id' => strtoupper(trim($filter_by['staff_id']))]);
        } else if (isset($filter_by['email'])){
            $builder->where('Admin.email = :email:', ['email' => strtolower(trim($filter_by['email']))]);
        } else if (isset($filter_by['id'])){
            $builder->where('Admin.id = :id:', ['id' => $filter_by['id']]);
        }

        $data = $builder->getQuery()->execute();

        if (count($data) == 0){
            return false;
        }
        $admin = $data[0]->admin->getData();
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
