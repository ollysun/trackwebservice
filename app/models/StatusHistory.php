<?php

use Phalcon\Mvc\Model;

/**
 * Created by PhpStorm.
 * User: Moses Olalere
 * Date: 2/13/2018
 * Time: 2:20 PM
 */

class StatusHistory extends Model
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
     *
     * @var string
     */
    protected $extra_note;


    /**
     *
     * @var string
     */
    protected $comment;

    /**
     *
     * @var integer
     */
    protected $created_by;


    /**
     * Method to set the value of field extra_note
     *
     * @param string $created_by
     * @return $this
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * Returns the value of field extra_note
     *
     * @return striing
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }


    /**
     * Method to set the value of field extra_note
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
     * Returns the value of field extra_note
     *
     * @return striing
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Method to set the value of field extra_note
     *
     * @param string $extra_note
     * @return $this
     */
    public function setExtraNote($extra_note)
    {
        $this->extra_note = $extra_note;

        return $this;
    }

    /**
     * Returns the value of field extra_note
     *
     * @return striing
     */
    public function getExtraNote()
    {
        return $this->extra_note;
    }


    /**
     * Method to set the value of field waybill_number
     *
     * @param string $waybill_number
     * @return $this
     */
    public function setWaybillNumber($waybill_number)
    {
        $this->waybill_number = $waybill_number;

        return $this;
    }

    /**
     * Returns the value of field waybill_number
     *
     * @return string
     */
    public function getWaybillNumber()
    {
        return $this->waybill_number;
    }



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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('created_by', 'Admin', 'id', array('alias' => 'Admin'));
    }

    /**
     * @return City[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return City
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    public static function fetchAll($offset, $count, $filter_by, $paginate = false)
    {
        $obj = new StatusHistory();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['StatusHistory.comment', 'StatusHistory.waybill_number',
                        'StatusHistory.extra_note','Admin.fullname'])
            ->from('StatusHistory')
            ->innerJoin('Admin', 'Admin.id = StatusHistory.created_by')
            ->orderBy('StatusHistory.id DESC');

        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        if (!empty($filter_by['waybill_number'])) {
            $where[] = 'StatusHistory.waybill_number = :waybill_number:';
            $bind['waybill_number'] = $filter_by['waybill_number'];
        }

        $builder->where(join(' AND ', $where));
        $results = $builder->getQuery()->execute($bind);
        $result =[];

        foreach ($results as $item) {
            $result[]  = $item->toArray();
        }

        return $result;

    }
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'waybill_number' => 'waybill_number',
            'extra_note' => 'extra_note',
            'comment' => 'comment',
            'created_by' => 'created_by',
        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'waybill_number' => $this->getWaybillNumber(),
            'extra_note' => $this->getExtraNote(),
            'comment' => $this->getComment(),
            'created_by' => $this->getCreatedBy(),
        );
    }
}