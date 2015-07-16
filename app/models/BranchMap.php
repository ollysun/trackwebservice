<?php

class BranchMap extends \Phalcon\Mvc\Model
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
    protected $child_id;

    /**
     *
     * @var integer
     */
    protected $parent_id;

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
     * Returns the value of field child_id
     *
     * @return integer
     */
    public function getChildId()
    {
        return $this->child_id;
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
        $this->belongsTo('child_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('parent_id', 'Branch', 'id', array('alias' => 'Branch'));
    }

    /**
     * @return BranchMap[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return BranchMap
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
            'child_id' => 'child_id', 
            'parent_id' => 'parent_id', 
            'status' => 'status'
        );
    }

    public function initData($child_id, $parent_id)
    {
        $this->setChildId($child_id);
        $this->setParentId($parent_id);
        $this->setStatus(Status::ACTIVE);
    }

    public static function getByChildId($child_id){
        return BranchMap::findFirst([
            'child_id = :child_id:',
            'bind' => ['child_id' => $child_id]
        ]);
    }
}
