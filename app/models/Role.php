<?php

class Role extends \Phalcon\Mvc\Model
{
    const ADMIN = 1;
    const OFFICER = 2;
    const SWEEPER = 3;
    const DISPATCHER = 4;
    const GROUNDSMAN = 5;
    const COMPANY_ADMIN = 6;
    const COMPANY_OFFICER = 7;
    const SUPER_ADMIN = 8;
    const SALES_AGENT = 9;
    const BUSINESS_MANAGER = 10;
    const REGIONAL_MANAGER = 11;
    const FINANCE = 12;
    const BILLING = 13;

    const INACTIVE_USER = -1;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Admin', 'role_id', array('alias' => 'Admin'));
    }
    /**
     * @return Role[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Role
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
            'name' => 'name'
        );
    }
}
