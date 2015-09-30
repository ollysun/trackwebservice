<?php

use Phalcon\Di;
use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Company extends \Phalcon\Mvc\Model
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
     * @var string
     */
    protected $reg_no;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $phone_number;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var integer
     */
    protected $city_id;

    /**
     *
     * @var double
     */
    protected $credit_limit;

    /**
     *
     * @var double
     */
    protected $discount;

    /**
     *
     * @var integer
     */
    protected $primary_contact_id;

    /**
     *
     * @var integer
     */
    protected $sec_contact_id;

    /**
     *
     * @var integer
     */
    protected $relations_officer_id;

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
     * Method to set the value of field reg_no
     *
     * @param string $reg_no
     * @return $this
     */
    public function setRegNo($reg_no)
    {
        $this->reg_no = trim($reg_no);

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
        $this->email = $email;

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
     * Method to set the value of field city_id
     *
     * @param integer $city_id
     * @return $this
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    /**
     * Method to set the value of field credit_limit
     *
     * @param double $credit_limit
     * @return $this
     */
    public function setCreditLimit($credit_limit)
    {
        $this->credit_limit = $credit_limit;

        return $this;
    }

    /**
     * Method to set the value of field discount
     *
     * @param double $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Method to set the value of field primary_contact_id
     *
     * @param integer $primary_contact_id
     * @return $this
     */
    public function setPrimaryContactId($primary_contact_id)
    {
        $this->primary_contact_id = $primary_contact_id;

        return $this;
    }

    /**
     * Method to set the value of field sec_contact_id
     *
     * @param integer $sec_contact_id
     * @return $this
     */
    public function setSecContactId($sec_contact_id)
    {
        $this->sec_contact_id = $sec_contact_id;

        return $this;
    }

    /**
     * Method to set the value of field relations_officer_id
     *
     * @param integer $relations_officer_id
     * @return $this
     */
    public function setRelationsOfficerId($relations_officer_id)
    {
        $this->relations_officer_id = $relations_officer_id;

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
     * Returns the value of field reg_no
     *
     * @return string
     */
    public function getRegNo()
    {
        return $this->reg_no;
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
     * Returns the value of field phone_number
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
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
     * Returns the value of field city_id
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Returns the value of field credit_limit
     *
     * @return double
     */
    public function getCreditLimit()
    {
        return $this->credit_limit;
    }

    /**
     * Returns the value of field discount
     *
     * @return double
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Returns the value of field primary_contact_id
     *
     * @return integer
     */
    public function getPrimaryContactId()
    {
        return $this->primary_contact_id;
    }

    /**
     * Returns the value of field sec_contact_id
     *
     * @return integer
     */
    public function getSecContactId()
    {
        return $this->sec_contact_id;
    }

    /**
     * Returns the value of field relations_officer_id
     *
     * @return integer
     */
    public function getRelationsOfficerId()
    {
        return $this->relations_officer_id;
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
        $this->hasMany('id', 'Company_user', 'company_id', array('alias' => 'Company_user'));
        $this->hasMany('id', 'Company_user', 'company_id', array('alias' => 'Company_user'));
        $this->belongsTo('created_by', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('modified_by', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('approved_by', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return Company[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Company
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
            'reg_no' => 'reg_no',
            'email' => 'email',
            'phone_number' => 'phone_number',
            'address' => 'address',
            'city_id' => 'city_id',
            'credit_limit' => 'credit_limit',
            'discount' => 'discount',
            'primary_contact_id' => 'primary_contact_id',
            'sec_contact_id' => 'sec_contact_id',
            'relations_officer_id' => 'relations_officer_id',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'status' => 'status'
        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'reg_no' => $this->getRegNo(),
            'email' => $this->getEmail(),
            'phone_number' => $this->getPhoneNumber(),
            'address' => $this->getAddress(),
            'city_id' => $this->getCityId(),
            'credit_limit' => $this->getCreditLimit(),
            'discount' => $this->getDiscount(),
            'primary_contact_id' => $this->getPrimaryContactId(),
            'sec_contact_id' => $this->getSecContactId(),
            'relations_officer_id' => $this->getRelationsOfficerId(),
            'created_date' => $this->getSecContactId(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public static function add($company_data)
    {
        $company_data['credit_limit'] = (isset($company_data['credit_limit'])) ? $company_data['credit_limit'] : null;
        $company_data['discount'] = (isset($company_data['discount'])) ? $company_data['discount'] : null;
        $company = new Company();
        $company->initData($company_data['name'],
            $company_data['reg_no'],
            $company_data['email'],
            $company_data['phone_number'],
            $company_data['address'],
            $company_data['city_id'],
            $company_data['credit_limit'],
            $company_data['discount'],
            $company_data['relations_officer_id']);
        if ($company->save()) {
            return $company;
        } else {
            return false;
        }
    }

    public function createUser($role_id, $data)
    {
        $user = new CompanyUser();
        $auth = new UserAuth();
        $auth->initData($data['email'], $data['password'], UserAuth::ENTITY_TYPE_CORPORATE);
        if (!$auth->save()) {
            return false;
        }
        $user->initData($this->getId(), $auth->getId(), $role_id, $data['firstname'], $data['lastname'], $data['phone_number']);
        if ($user->save()) {
            return $user;
        }
        return false;
    }

    public function initData($name, $reg_no, $email, $phone_number, $address, $city_id, $credit_limit, $discount, $relations_officer_id)
    {
        $this->setName($name);
        $this->setRegNo($reg_no);
        $this->setEmail($email);
        $this->setPhoneNumber($phone_number);
        $this->setAddress($address);
        $this->setCityId($city_id);
        $this->setCreditLimit($credit_limit);
        $this->setDiscount($discount);
        $this->setPrimaryContactId(null);
        $this->setSecContactId(null);
        $this->setRelationsOfficerId($relations_officer_id);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);

        $this->setStatus(Status::ACTIVE);
    }

    public function changeDetails($name, $reg_no, $email, $phone_number, $address, $city_id, $credit_limit, $discount, $primary_contact_id, $sec_contact_id, $relations_officer_id, $status_id)
    {
        $this->setName($name);
        $this->setRegNo($reg_no);
        $this->setEmail($email);
        $this->setPhoneNumber($phone_number);
        $this->setAddress($address);
        $this->setCityId($city_id);
        $this->setCreditLimit($credit_limit);
        $this->setDiscount($discount);
        $this->setPrimaryContactId($primary_contact_id);
        $this->setSecContactId($sec_contact_id);
        $this->setRelationsOfficerId($relations_officer_id);
        $this->setStatus($status_id);

        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public
    function changeStatus($status)
    {
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    /**
     * Used to set up the where clause with its bind parameters
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param $filter_by
     * @return array
     */
    private static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];

        if (isset($filter_by['status'])) {
            $where[] = 'status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        if (isset($filter_by['name'])){
            $where[] = 'name LIKE :name:';
            $bind['name'] = '%' . strtolower(trim($filter_by['name'])) . '%';
        }

        return ['where' => $where, 'bind' => $bind];
    }

    public static function getTotalCount($filter_by)
    {
        $obj = new Company();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS company_count')
            ->from('Company');

        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0){
            return null;
        }

        return intval($data[0]->company_count);
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with)
    {
        $obj = new Company();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Company')
            ->limit($count, $offset);

        $columns = ['Company.*'];
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        if (isset($fetch_with['with_city'])) {
            $builder->innerJoin('City', 'City.id = Company.city_id');
            $builder->innerJoin('State', 'State.id = City.state_id');
            $columns[] = 'City.*';
            $columns[] = 'State.*';
        }

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);
        $result = [];
        foreach ($data as $item) {
            $company = [];
            if (isset($item->company)) {
                $company = $item->company->getData();
                if (isset($fetch_with['with_city'])) {
                    $company['city'] = $item->city->getData();
                    $company['state'] = $item->state->getData();
                }
            } else {
                $company = $item->getData();
            }
            $result[] = $company;
        }
        return $result;
    }

    public static function fetchOne($filter_by, $fetch_with)
    {
        $obj = new Company();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Company');

        $columns = ['Company.*'];
        $bind = [];
        $where = [];

        if (isset($filter_by['id'])) {
            $where[] = 'Company.id = :id:';
            $bind['id'] = $filter_by['id'];
        }

        if (isset($fetch_with['with_city'])) {
            $builder->innerJoin('City', 'City.id = Company.city_id');
            $builder->innerJoin('State', 'State.id = City.state_id');
            $columns[] = 'City.*';
            $columns[] = 'State.*';
        }

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return false;
        }

        $company = [];
        if (isset($data[0]->company)) {
            $company = $data[0]->company->getData();
            if (isset($fetch_with['with_city'])) {
                $company['city'] = $data[0]->city->getData();
                $company['state'] = $data[0]->state->getData();
            }
        } else {
            $company = $data[0]->getData();
        }

        if (isset($fetch_with['with_primary_contact'])) {
            $company['primary_contact'] = CompanyUser::fetchOne(['id' => $company['primary_contact_id']], []);
        }

        if (isset($fetch_with['with_secondary_contact'])) {
            $company['secondary_contact'] = CompanyUser::fetchOne(['id' => $company['sec_contact_id']], []);
        }

        if (isset($fetch_with['with_relations_officer'])) {
            $company['relations_officer'] = Admin::fetchOne(['id' => $company['relations_officer_id']]);
        }

        return $company;
    }

    public static function getByName($name)
    {
        $name = Text::removeExtraSpaces(strtolower($name));
        return Company::findFirst([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name]
        ]);
    }

    /**
     * Send notification to contact
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $contact
     * @return bool
     */
    public function notifyContact($contact)
    {
        return EmailMessage::send(
            EmailMessage::COMPANY_USER_ACCOUNT_CREATION,
            [
                'name' => $contact['firstname'] . ' ' . $contact['lastname'],
                'email' => $contact['email'],
                'company_name' => ucwords($this->name),
                'password' => $contact['password'],
                'link' => Di::getDefault()->getConfig()->fe_base_url . '/site/changePassword?ican=' . md5($contact['id']) . '&salt=' . $contact['id'],
                'year' => date('Y')
            ],
            'Courier Plus',
            $contact['email']
        );
    }
}
