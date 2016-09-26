<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 9/15/2016
 * Time: 5:31 AM
 */
class ConflitingParcel extends \Phalcon\Mvc\Model
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
    protected $waybill_number;


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
     * Returns the value of field username
     *
     * @return string
     */
    public function getWaybillNumber()
    {
        return $this->waybill_number;
    }

    public static function fetchAll($offset = 0, $count = 0, $paginate = false)
    {
        $obj = new ConflitingParcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('ConflitingParcel');

        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        $bind = [];
        $columns = ['ConflitingParcel.*'];

        $builder->columns($columns);
        return $builder->getQuery()->execute($bind);
    }

}