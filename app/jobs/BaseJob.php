<?php
use Pheanstalk\Job;

/**
 * Class BaseJob
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
abstract class BaseJob
{
    /** @var  $worker BaseWorker */
    public $worker;
    public $data;
    public $id;

    public function __construct(Job $serverJob)
    {
        $this->data = json_decode($serverJob->getData());
        $this->id = $serverJob->getId();
    }

    public function __toString()
    {
        return '';
    }

    /**
     * action to perform on start
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onStart(){

    }

    /**
     * action to perform on fail
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param null $error
     * @return mixed
     */
    public  function onFail($error = null){

    }

    /**
     * action to perform on success
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onSuccess(){

    }

    /**
     * action to perform on complete
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onComplete(){

    }

    /**
     * execute current job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public abstract function execute();

    /**
     * set Job worker
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $worker
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;
    }


}