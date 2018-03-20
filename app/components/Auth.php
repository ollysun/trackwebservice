<?php
use \Phalcon\DI\FactoryDefault;
use Phalcon\Cache\Backend;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

/**
 * Class AuthToken
 *
 * @property Phalcon\Cache\Backend cache
 */
class Auth
{
    //const TOKEN_SUFFIX = '.token';
    const TOKEN_SUFFIX = '.toks';

    const STATUS_OK = 1;
    const STATUS_ACCESS_DENIED = 2;
    const STATUS_LOGIN_REQUIRED = 3;

    const L_TOKEN = 'token';
    const L_EMAIL = 'email';
    const L_USER_TYPE = 'user_type';
    const L_DATA = 'data';
    const L_OPERATIONS = 'operations';

    const TOKEN_CHAR_SET = 'QWERTYUPASDFGHJKLZXCVBNM123456789';
    const TOKEN_LENGTH = 20;

    protected $cache_life_time = 259200;
    protected $cache;
    protected $di;

    /**
     * @var array|null
     */
    protected $data = null;

    /**
     * @var int|null
     */
    protected $client_id = null;

    /**
     * @var string|null
     */
    protected $token = null;

    /**
     * An array of operations a user can perform
     * @var array
     */
    protected $operations = null;

    /**
     * @var string|null
     */
    protected $email = null;


    /**
     * @var int|null
     */
    protected $user_type = null;

    public function getData(){
        return $this->data;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUserType()
    {
        return $this->user_type;
    }

    protected function getCacheKey()
    {
        return $this->client_id . self::TOKEN_SUFFIX;
    }

    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->cache = $this->di['cache'];
    }

    public function loadTokenData($client_id, $cache_life_time = null)
    {
        if ($cache_life_time == null) {
            $this->cache_life_time = $this->di['config']->application->cacheLifeTime;
        } else {
            $this->cache_life_time = $cache_life_time;
        }

        $this->client_id = $client_id;
        $cache_key = $this->getCacheKey();
        $stored_data = $this->cache->get($cache_key);

        if ($stored_data === null) {
            $this->token = null;
            $this->email = null;
            $this->user_type = null;
            $this->data = null;
            $this->operations = null;
        } else {
            $this->token = $stored_data[self::L_TOKEN];
            $this->email = $stored_data[self::L_EMAIL];
            $this->user_type = $stored_data[self::L_USER_TYPE];
            $this->data = $stored_data[self::L_DATA];
            $this->operations = $stored_data[self::L_OPERATIONS];
        }
    }

    /**
     * @param int $client_id
     * @param array $data
     */
    public function saveTokenData($client_id, $data = array())
    {
        if ($client_id == null) {
            return;
        }
        $this->client_id = $client_id;

        $cache_data = array();

        $cache_data[self::L_EMAIL] = $this->email = (isset($data[self::L_EMAIL])) ? $data[self::L_EMAIL] : null;
        $cache_data[self::L_USER_TYPE] = $this->user_type = (isset($data[self::L_USER_TYPE])) ? $data[self::L_USER_TYPE] : null;
        $cache_data[self::L_TOKEN] = $this->token = (isset($data[self::L_TOKEN])) ? $data[self::L_TOKEN] : null;
        $cache_data[self::L_DATA] = $this->data = (isset($data[self::L_DATA])) ? $data[self::L_DATA] : null;
        //operations are added wen saving token
        $cache_data[self::L_OPERATIONS] = $this->operations =
            (isset($data[self::L_OPERATIONS]))?$data[self::L_OPERATIONS]:null;

        $cache_key = $this->getCacheKey();
        $this->cache->save($cache_key, $cache_data, $this->cache_life_time);
    }


    public function checkToken($token)
    {

        if ($this->token === null) {
            return self::STATUS_LOGIN_REQUIRED;
        }

        return ($this->token == $token) ? self::STATUS_OK : self::STATUS_ACCESS_DENIED;
    }

    public function checkCompanyAccess($reg, $key)
    {
        $company_access = CompanyAccess::findFirst(
            ['registration_number = :reg_no: AND token = :tok:',
                'bind' => [
                    'reg_no' => $reg,
                    'tok' => $key
                ],
            ]);
        if ($company_access)
        {
            return $company_access;
        }
        return self::STATUS_ACCESS_DENIED;
    }

    public function resetToken()
    {
        if ($this->client_id == null) {
            return false;
        }

        $this->token = $this->generateToken(self::TOKEN_LENGTH);

        $this->saveTokenData($this->client_id, array(
            self::L_EMAIL => $this->email,
            self::L_USER_TYPE => $this->user_type,
            self::L_TOKEN => $this->token
        ));

        return $this->token;
    }

    public function clearTokenData()
    {
        if ($this->client_id == null) {
            return true;
        }

        return $this->cache->delete($this->getCacheKey());
    }

    public function generateToken($length = self::TOKEN_LENGTH)
    {
        $new_token = '';
        $char_set = self::TOKEN_CHAR_SET;
        for ($i = 0; $i < $length; $i++) {
            $new_token .= $char_set[rand(0, strlen($char_set) - 1)];
        }

        return $new_token;
    }

    public function allowOnly($user_type)
    {
        $allowed = false;
        if ($this->getUserType() == Role::SUPER_ADMIN){
            $allowed = true;
        }else if (is_array($user_type)) {
            $allowed = (in_array($this->getUserType(), $user_type));
        } else {
            $allowed = ($this->getUserType() == $user_type);
        }

        if (!$allowed) {
            echo $this->di['response']->sendAccessDenied()->getContent();
            exit();
        }
    }

    /**
     * Checks if this user can perform the supplied operation
     * @param $operation_id
     * @return bool
     */
    public function canPerformOperation($operation_id){
        return in_array($operation_id, $this->getOperations());
    }

    /**
     * This checks if Authentication should be skipped either on the controller or action levels.
     * If the action in the route_arr item is null then the skipping would be checked for the
     * whole controller else it will be checked for a particular action.
     * @param array $route_arr - An array of ('controller'=>$c, 'action'=>$a)
     * @return bool
     */
    public function skipAuth($route_arr)
    {
        /**
         * @var MvcDispatcher $dispatcher
         */
        $auth = new Auth();
        $dispatcher = $auth->di['dispatcher'];

        foreach ($route_arr as $i => $route) {
            if ($dispatcher->getControllerName() == $route['controller'] and !isset($route['action'])) {
                return true;
            } else if ($dispatcher->getControllerName() == $route['controller'] and $dispatcher->getActionName() == $route['action']) {
                return true;
            }
        }
        return false;
    }

    public function clientTokenExists($client_id)
    {
        $this->client_id = $client_id;
        $cache_key = $this->getCacheKey();
        return $this->cache->isExisting($cache_key);
    }

    /**
     * Get's the id of currently logged admin or company user
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return bool
     */
    public function getPersonId()
    {
        $this->loadTokenData($this->getClientId());

        if(isset($this->data, $this->data['id'])) {
            return $this->data['id'];
        }
        return false;
    }

    public function isCooperateUser(){
        $company_access = CompanyAccess::findFirst(['auth_username = :email:', 'bind' => ['email' => $this->getEmail()]]);
        return $this->getUserType() == Role::COMPANY_ADMIN || $this->getUserType() == Role::COMPANY_OFFICER || $company_access;
    }

    public function getCompanyId(){
        if(isset($this->getData()['company_id'])) return $this->getData()['company_id'];
        $company_access = CompanyAccess::findFirst(['auth_username = :email:', 'bind' => ['email' => $this->getEmail()]]);
        if($company_access){
            $company = Company::getByRegistrationNumber($company_access->getRegistrationNumber());
            return $company->getId();
            //return $company_access->getCompanyId();
        }
        return false;
    }
} 