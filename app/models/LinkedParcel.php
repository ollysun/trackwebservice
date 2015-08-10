<?php

class LinkedParcel extends \Phalcon\Mvc\Model
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
    protected $parent_id;

    /**
     *
     * @var integer
     */
    protected $child_id;

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
     * Method to set the value of field parent_id
     *
     * @param integer $parent_id
     * @return $this
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * Method to set the value of field child_id
     *
     * @param integer $child_id
     * @return $this
     */
    public function setChildId($child_id)
    {
        $this->child_id = $child_id;

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
     * Returns the value of field parent_id
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Returns the value of field child_id
     *
     * @return integer
     */
    public function getChildId()
    {
        return $this->child_id;
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
        $this->belongsTo('parent_id', 'Parcel', 'id', array('alias' => 'Parcel'));
        $this->belongsTo('child_id', 'Parcel', 'id', array('alias' => 'Parcel'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return LinkedParcel[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return LinkedParcel
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
            'parent_id' => 'parent_id', 
            'child_id' => 'child_id', 
            'status' => 'status'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'parent_id' => $this->getParentId(),
            'child_id' => $this->getChildId(),
            'status' => $this->getStatus()
        );
    }

    public function initData($parent_id, $child_id){
        $this->setParentId($parent_id);
        $this->setChildId($child_id);
        $this->setStatus(Status::ACTIVE);
    }
}
