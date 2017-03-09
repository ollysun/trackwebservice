<?php

/**
 * CompanyMap
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2017-02-27, 06:01:17
 */
class CompanyMap extends \Phalcon\Mvc\Model
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
    protected $reg_no;

    /**
     *
     * @var string
     */
    protected $region;

    /**
     *
     * @var string
     */
    protected $territory;

    /**
     *
     * @var string
     */
    protected $business_manager;

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
     * Method to set the value of field reg_no
     *
     * @param string $reg_no
     * @return $this
     */
    public function setRegNo($reg_no)
    {
        $this->reg_no = $reg_no;

        return $this;
    }

    /**
     * Method to set the value of field region
     *
     * @param string $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Method to set the value of field territory
     *
     * @param string $territory
     * @return $this
     */
    public function setTerritory($territory)
    {
        $this->territory = $territory;

        return $this;
    }

    /**
     * Method to set the value of field business_manager
     *
     * @param string $business_manager
     * @return $this
     */
    public function setBusinessManager($business_manager)
    {
        $this->business_manager = $business_manager;

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
     * Returns the value of field reg_no
     *
     * @return string
     */
    public function getRegNo()
    {
        return $this->reg_no;
    }

    /**
     * Returns the value of field region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Returns the value of field territory
     *
     * @return string
     */
    public function getTerritory()
    {
        return $this->territory;
    }

    /**
     * Returns the value of field business_manager
     *
     * @return string
     */
    public function getBusinessManager()
    {
        return $this->business_manager;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'company_map';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyMap[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyMap
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
            'reg_no' => 'reg_no',
            'region' => 'region',
            'territory' => 'territory',
            'business_manager' => 'business_manager'
        );
    }

}
