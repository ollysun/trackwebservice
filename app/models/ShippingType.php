<?php

class ShippingType extends \Phalcon\Mvc\Model
{

    const EXPRESS = 1;
    const SPECIAL_PROJECTS = 2;
    const LOGISTICS = 3;
    const BULK_MAIL = 4;
    const INTL_EXPRESS_DOCUMENT = 5;
    const INTL_EXPRESS_NON_DOCUMENT = 6;
    const INTL_ECONOMY_EXPRESS= 7;
    const INTL_IMPORT_EXPRESS_DOCUMENT = 8;
    const INTL_IMPORT_EXPRESS_NON_DOCUMENT = 9;
    const INTL_IMPORT_ECONOMY_EXPRESS= 10;
    const INTL_TRANSCRIPT_EXPORT = 11;

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
        $this->hasMany('id', 'Parcel', 'shipping_type', array('alias' => 'Parcel'));
    }

    /**
     * @return ShippingType[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return ShippingType
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
