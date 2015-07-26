<?php

class Region extends \Phalcon\Mvc\Model
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
    protected $country_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var integer
     */
    protected $active_fg;

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
     * Method to set the value of field country_id
     *
     * @param integer $country_id
     * @return $this
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;

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
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = Text::removeExtraSpaces($description);

        return $this;
    }

    /**
     * Method to set the value of field active_fg
     *
     * @param integer $active_fg
     * @return $this
     */
    public function setActiveFg($active_fg)
    {
        $this->active_fg = $active_fg;

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
     * Returns the value of field country_id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->country_id;
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
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field active_fg
     *
     * @return integer
     */
    public function getActiveFg()
    {
        return $this->active_fg;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('country_id', 'Country', 'id', array('alias' => 'Country'));
    }

    /**
     * @return Region[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Region
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
            'country_id' => 'country_id', 
            'name' => 'name',
            'description' => 'description',
            'active_fg' => 'active_fg'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'country_id' => $this->getCountryId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'active_fg' => $this->getActiveFg()
        );
    }

    public function initData($country_id, $name, $desc){
        $this->setCountryId($country_id);
        $this->setName($name);
        $this->setDescription($desc);
        $this->setActiveFg(1);
    }

    public function edit($country_id, $name, $desc){
        $this->setDescription($desc);
        $this->setCountryId($country_id);
        $this->setName($name);
    }

    public function changeActiveFg($active_fg){
        $this->setActiveFg($active_fg);
    }

    public static function isExisting($country_id, $name, $region_id = null){
        $bind = ['country_id' => $country_id, 'name' => Text::removeExtraSpaces(strtolower($name))];
        $id_condition = ($region_id == null) ? '' : ' AND id != :id:';

        if ($region_id != null) {$bind['id'] = $region_id;}

        $region = Region::findFirst([
            'country_id = :country_id: AND name = :name: ' . $id_condition,
            'bind' => $bind
        ]);
        return $region != false;
    }

    public static function fetchActive($region_id){
        return Region::findFirst([
            'id = :id: AND active_fg=1',
            'bind' => ['id' => $region_id]
        ]);
    }
}
