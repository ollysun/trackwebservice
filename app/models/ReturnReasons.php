<?php
/**
 * Created by PhpStorm.
 * User: babatunde
 * Date: 11/9/15
 * Time: 4:29 PM
 */

use Phalcon\Mvc\Model;



class ReturnReasons extends phalcon\Mvc\Model
{

    protected $id;

    protected $status_code;

    protected $meaning_of_status;

    protected $usage_of_status;

    public function getSource()
    {
        return "return_reasons";
    }

    public function initialize()
    {
        $this->setSource("return_reasons");
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMeaningOfStatus($meaning_of_status)
    {
        $this->meaning_of_status = $meaning_of_status;
    }

    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    public function setUsageOfStatus($usage_of_status)
    {
        $this->usage_of_status = $usage_of_status;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMeaningOfStatus()
    {
        return $this->meaning_of_status;
    }

    public function getStatusCode()
    {
        return $this->status_code;

    }

    public function getUsageOfStatus()
    {
        return $this->usage_of_status;
    }



    public static function getAll()
    {
        $obj = new ReturnReasons();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns("id, status_code, meaning_of_status, usage_of_status")
            ->from('ReturnReasons');

        $data = $builder->getQuery()->execute();

        return $data->toArray();

    }



}

