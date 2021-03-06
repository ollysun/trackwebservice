<?php


use Phalcon\Mvc\Model;


/**
 * Class ReturnReasons
 * @author Babatunde Otaru <tunde@cottacush.com>
 **/
class ReturnReasons extends phalcon\Mvc\Model
{
    /**
     *@author Babatunde Otaru <tunde@cottacush.com>
     *@var integer
     */
    protected $id;


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @var String
     */
    protected $status_code;

    /**
     *@author Babatunde Otaru <tunde@cottacush.com>
     * @var String
     */
    protected $meaning_of_status;


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @var String
     */
    protected $usage_of_status;


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
    * Method to set the database for this model
    **/
    public function getSource()
    {
        return "return_reasons";
    }



    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * Initialize method for model
     */
    public function initialize()
    {
        $this->setSource("return_reasons");
    }


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
    * Method to set the value of field id
    * @param integer $id
    */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * Method to set the value of field meaning_of_status
     * @param string $meaning_of_status
     */
    public function setMeaningOfStatus($meaning_of_status)
    {
        $this->meaning_of_status = $meaning_of_status;
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * Method to set the value of field status_code
     * @param string $status_code
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * Method to set the value of field usage_of_status
     * @param string $usage_of_status
     */
    public function setUsageOfStatus($usage_of_status)
    {
        $this->usage_of_status = $usage_of_status;
    }


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     *Return the value of field meaning_of_status
     * @return integer id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     *Return the value of field meaning_of_status
     * @return string
     */
    public function getMeaningOfStatus()
    {
        return $this->meaning_of_status;
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     *Return the value of field status_code
     * @return string
     */
    public function getStatusCode()
    {
        return $this->status_code;

    }


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * Return the value of field usage_of_status
     * @return string
     */
    public function getUsageOfStatus()
    {
        return $this->usage_of_status;
    }


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * Return Reasons[]
    **/
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

