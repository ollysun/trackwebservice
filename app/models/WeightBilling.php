<?php

class WeightBilling extends \Phalcon\Mvc\Model
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
    protected $zone_id;

    /**
     *
     * @var integer
     */
    protected $weight_range_id;

    /**
     *
     * @var double
     */
    protected $base_cost;

    /**
     *
     * @var double
     */
    protected $base_percentage;

    /**
     *
     * @var double
     */
    protected $increment_cost;

    /**
     *
     * @var double
     */
    protected $increment_percentage;

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
     * Method to set the value of field zone_id
     *
     * @param integer $zone_id
     * @return $this
     */
    public function setZoneId($zone_id)
    {
        $this->zone_id = $zone_id;

        return $this;
    }

    /**
     * Method to set the value of field weight_range_id
     *
     * @param integer $weight_range_id
     * @return $this
     */
    public function setWeightRangeId($weight_range_id)
    {
        $this->weight_range_id = $weight_range_id;

        return $this;
    }

    /**
     * Method to set the value of field base_cost
     *
     * @param double $base_cost
     * @return $this
     */
    public function setBaseCost($base_cost)
    {
        $this->base_cost = $base_cost;

        return $this;
    }

    /**
     * Method to set the value of field base_percentage
     *
     * @param double $base_percentage
     * @return $this
     */
    public function setBasePercentage($base_percentage)
    {
        $this->base_percentage = $base_percentage;

        return $this;
    }

    /**
     * Method to set the value of field increment_cost
     *
     * @param double $increment_cost
     * @return $this
     */
    public function setIncrementCost($increment_cost)
    {
        $this->increment_cost = $increment_cost;

        return $this;
    }

    /**
     * Method to set the value of field increment_percentage
     *
     * @param double $increment_percentage
     * @return $this
     */
    public function setIncrementPercentage($increment_percentage)
    {
        $this->increment_percentage = $increment_percentage;

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
     * Returns the value of field zone_id
     *
     * @return integer
     */
    public function getZoneId()
    {
        return $this->zone_id;
    }

    /**
     * Returns the value of field weight_range_id
     *
     * @return integer
     */
    public function getWeightRangeId()
    {
        return $this->weight_range_id;
    }

    /**
     * Returns the value of field base_cost
     *
     * @return double
     */
    public function getBaseCost()
    {
        return $this->base_cost;
    }

    /**
     * Returns the value of field base_percentage
     *
     * @return double
     */
    public function getBasePercentage()
    {
        return $this->base_percentage;
    }

    /**
     * Returns the value of field increment_cost
     *
     * @return double
     */
    public function getIncrementCost()
    {
        return $this->increment_cost;
    }

    /**
     * Returns the value of field increment_percentage
     *
     * @return double
     */
    public function getIncrementPercentage()
    {
        return $this->increment_percentage;
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
        $this->belongsTo('zone_id', 'Zone', 'id', array('alias' => 'Zone'));
        $this->belongsTo('weight_range_id', 'Weight_range', 'id', array('alias' => 'Weight_range'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return WeightBilling[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return WeightBilling
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
            'zone_id' => 'zone_id', 
            'weight_range_id' => 'weight_range_id', 
            'base_cost' => 'base_cost', 
            'base_percentage' => 'base_percentage', 
            'increment_cost' => 'increment_cost', 
            'increment_percentage' => 'increment_percentage', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date', 
            'status' => 'status'
        );
    }

}
