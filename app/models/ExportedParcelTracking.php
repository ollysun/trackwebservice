<?php

/**
 * ExportedParcelTracking
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2017-02-24, 11:04:57
 */
class ExportedParcelTracking extends \Phalcon\Mvc\Model
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
    protected $commentdate;

    /**
     *
     * @var string
     */
    protected $comment;

    /**
     *
     * @var string
     */
    protected $created_at;

    /**
     *
     * @var integer
     */
    protected $admin_id;

    /**
     *
     * @var integer
     */
    protected $exportedparcel_id;

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
     * Method to set the value of field commentdate
     *
     * @param string $commentdate
     * @return $this
     */
    public function setCommentdate($commentdate)
    {
        $this->commentdate = $commentdate;

        return $this;
    }

    /**
     * Method to set the value of field comment
     *
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field admin_id
     *
     * @param integer $admin_id
     * @return $this
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;

        return $this;
    }

    /**
     * Method to set the value of field exportedparcel_id
     *
     * @param integer $exportedparcel_id
     * @return $this
     */
    public function setExportedparcelId($exportedparcel_id)
    {
        $this->exportedparcel_id = $exportedparcel_id;

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
     * Returns the value of field commentdate
     *
     * @return string
     */
    public function getCommentdate()
    {
        return $this->commentdate;
    }

    /**
     * Returns the value of field comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Returns the value of field created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * Returns the value of field exportedparcel_id
     *
     * @return integer
     */
    public function getExportedparcelId()
    {
        return $this->exportedparcel_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('admin_id', 'Admin', 'id', array('alias' => 'Admin'));
        $this->belongsTo('exportedparcel_id', 'ExportedParcel', 'id', array('alias' => 'ExportedParcel'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'exported_parcel_tracking';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ExportedParcelTracking[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ExportedParcelTracking
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
            'commentdate' => 'commentdate',
            'comment' => 'comment',
            'created_at' => 'created_at',
            'admin_id' => 'admin_id',
            'exportedparcel_id' => 'exportedparcel_id'
        );
    }

}