<?php

use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
class CompanyUser extends \Phalcon\Mvc\Model
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
    protected $company_id;

    /**
     *
     * @var integer
     */
    protected $user_auth_id;

    /**
     *
     * @var integer
     */
    protected $role_id;

    /**
     *
     * @var string
     */
    protected $firstname;

    /**
     *
     * @var string
     */
    protected $lastname;

    /**
     *
     * @var string
     */
    protected $phone_number;

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
     * Method to set the value of field company_id
     *
     * @param integer $company_id
     * @return $this
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;

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
     * Method to set the value of field firstname
     *
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Method to set the value of field lastname
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Method to set the value of field phone_number
     *
     * @param string $phone_number
     * @return $this
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;

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
     * Returns the value of field company_id
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->company_id;
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
     * Returns the value of field role_id
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Returns the value of field firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Returns the value of field lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Returns the value of field phone_number
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
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
        $this->belongsTo('company_id', 'Company', 'id', array('alias' => 'Company'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('company_id', 'Company', 'id', array('alias' => 'Company'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return CompanyUser[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return CompanyUser
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
            'company_id' => 'company_id', 
            'user_auth_id' => 'user_auth_id', 
            'role_id' => 'role_id', 
            'firstname' => 'firstname', 
            'lastname' => 'lastname', 
            'phone_number' => 'phone_number',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date'
        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'user_auth_id' => $this->getUserAuthId(),
            'role_id' => $this->getRoleId(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'phone_number' => $this->getPhoneNumber(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate()
        );
    }

    public function initData($company_id, $user_auth_id, $role_id, $firstname, $lastname, $phone_number)
    {
        $this->setCompanyId($company_id);
        $this->setUserAuthId($user_auth_id);
        $this->setRoleId($role_id);
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setPhoneNumber($phone_number);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
    }

    /**
     * Created Corporate with UserAuth at once
     * @author Rahman Shitu <rahman@cottacush.com>
     *
     * @param $company_id
     * @param $role_id
     * @param $firstname
     * @param $lastname
     * @param $phone_number
     * @param $email
     * @param $password
     * @return bool
     */
    public function createWithAuth($company_id, $role_id, $firstname, $lastname, $phone_number, $email, $password)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);

            $auth = new UserAuth();
            $auth->setTransaction($transaction);
            $auth->initData($email, $password, UserAuth::ENTITY_TYPE_CORPORATE);
            if ($auth->save()){
                $this->initData($company_id, $auth->getId(), $role_id, $firstname, $lastname, $phone_number);
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

    /**
     * Used to set details of a corporate user
     * @author Rahman Shitu <rahman@cottacush.com>
     *
     * @param $role_id
     * @param $firstname
     * @param $lastname
     * @param $phone_number
     */
    public function changeDetails($role_id, $firstname, $lastname, $phone_number)
    {
        $this->setRoleId($role_id);
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setPhoneNumber($phone_number);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    /**
     * Fetches login data for a corporate user
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param $user_auth_id
     * @return array|false
     */
    public static function fetchLoginData($user_auth_id){
        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['CompanyUser.*', 'Role.*', 'Company.*'])
            ->from('CompanyUser')
            ->innerJoin('Role', 'CompanyUser.role_id = Role.id')
            ->innerJoin('Company', 'CompanyUser.company_id = Company.id')
            ->where('(CompanyUser.user_auth_id = :user_auth_id:)');

        $data = $builder->getQuery()->execute(['user_auth_id' => $user_auth_id]);

        if (count($data) == 0){
            return false;
        }
        $login_data = $data[0]->companyUser->toArray();
        $login_data['company'] = $data[0]->company->toArray();
        $login_data['role'] = $data[0]->role->toArray();

        return $login_data;
    }

    /**
     * Used to fetch corporate user data using their email
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param $email
     * @param null $not_id - Used to exclude an admin id on search, useful for change the email or staff id of an admin
     * @return object|bool - object contains companyUser and userAuth properties
     */
    public static function fetchByIdentifier($email, $not_id = null)
    {
        $id_condition = (empty($not_id)) ? '' : ' AND UserAuth.id != :id:';
        $bind = (empty($not_id)) ? [] : ['id' => $not_id];
        $bind['email'] = $email;

        $obj = new Admin();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['CompanyUser.*', 'UserAuth.*'])
            ->from('UserAuth')
            ->innerJoin('CompanyUser', 'CompanyUser.user_auth_id = UserAuth.id')
            ->where('UserAuth.email = :email:' . $id_condition, $bind);

        $data = $builder->getQuery()->execute();

        if (count($data) == 0){
            return false;
        }

        return $data[0];
    }

    /**
     * Used for fetching a paginated array of corporate users using optional filters
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with)
    {
        $obj = new CompanyUser();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('CompanyUser')
            ->innerJoin('Role', 'CompanyUser.role_id = Role.id')
            ->innerJoin('UserAuth', 'UserAuth.id = CompanyUser.user_auth_id')
            ->limit($count, $offset);

        $bind = [];
        $where = [];
        $columns = ['CompanyUser.*', 'Role.*', 'UserAuth.*'];

        if (isset($filter_by['company_id'])){
            $where[] = 'CompanyUser.company_id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }
        if (isset($filter_by['role_id'])) {
            $where[] = 'CompanyUser.role_id = :role_id:';
            $bind['role_id'] = $filter_by['role_id'];
        }

        if (isset($filter_by['email'])){
            $where[] = 'UserAuth.email LIKE :email:';
            $bind['email'] = '%' . strtolower(trim($filter_by['email'])) . '%';
        }

        if (isset($fetch_with['with_company'])){
            $columns[] = 'Company.*';
            $builder->innerJoin('Company', 'Company.id = CompanyUser.company_id');
        }

        $builder->columns($columns)->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach($data as $item){
            $user = $item->companyUser->getData();
            $user['email'] = $item->userAuth->getEmail();
            $user['status'] = $item->userAuth->getStatus();
            $user['role'] = $item->role->toArray();
            if (isset($fetch_with['company'])){
                $user['company'] = $item->company->getData();
            }
            $result[] = $user;
        }

        return $result;
    }

    /**
     * Fetches a single corporate user
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param $filter_by
     * @return array|false
     */
    public static function fetchOne($filter_by, $fetch_with)
    {
        $obj = new CompanyUser();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('CompanyUser')
            ->innerJoin('Role', 'CompanyUser.role_id = Role.id')
            ->innerJoin('UserAuth', 'UserAuth.id = CompanyUser.user_auth_id');

        $columns = ['CompanyUser.*', 'Role.*', 'UserAuth.*'];

        if (isset($filter_by['email'])){
            $builder->where('UserAuth.email = :email:', ['email' => strtolower(trim($filter_by['email']))]);
        } else if (isset($filter_by['id'])){
            $builder->where('CompanyUser.id = :id:', ['id' => $filter_by['id']]);
        }

        if (isset($fetch_with['with_company'])){
            $columns[] = 'Company.*';
            $builder->innerJoin('Company', 'Company.id = CompanyUser.company_id');
        }

        $builder->columns($columns);
        $data = $builder->getQuery()->execute();

        if (count($data) == 0){
            return false;
        }

        $user = $data[0]->companyUser->getData();
        $user['email'] = $data[0]->userAuth->getEmail();
        $user['status'] = $data[0]->userAuth->getStatus();
        $user['role'] = $data[0]->role->toArray();
        if (isset($fetch_with['with_company'])){
            $user['company'] = $data[0]->company->getData();
        }

        return $user;
    }

    /**
     * Get the corporate user using the id and (optional) role_id
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param $id
     * @param null $role_id
     * @return CompanyUser
     */
    public static function getById($id, $role_id=null){
        $condition = 'id = :id: AND status = :status:';
        $bind = array('id' => $id, 'status' => Status::ACTIVE);

        if ($role_id != null){
            $condition .= ' AND role_id=:role_id:';
            $bind['role_id'] = $role_id;
        }
        return CompanyUser::findFirst(array(
            $condition,
            'bind' => $bind
        ));
    }
}
