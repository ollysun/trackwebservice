<?php

use Phalcon\Mvc\Controller;
use PhalconUtils\S3\S3Client;

/**
 * Class ControllerBase
 *
 * @property PackedResponse response
 * @property Phalcon\Security security
 * @property mixed cache
 * @property Auth auth
 * @property S3Client s3Client
 */
class ControllerBase extends Controller
{
    /**
     * @var datetime
     */
    private $start_time;

    /**
     * @var String
     */
    private $data;

    /**
     * @var datetime
     */
    private $end_time;

    /**
     * @param Phalcon\Mvc\Dispatcher $dispatcher
     */
    public function beforeExecuteRoute($dispatcher){
        $request = $this->request->get();
        $this->start_time = date("Y-m-d H:i:s");
    }

    /**
     * @param Phalcon\Mvc\Dispatcher $dispatcher
     */
    public function afterExecuteRoute($dispatcher){
        $data = $this->request->get();
        if(isset($data['_url'])){
            if(in_array($data['_url'], Audit::$excludes)){
                return;
            }
        }
        $audit = new Audit();
        $audit->setUsername($this->auth->getEmail());
        $audit->setService($dispatcher->getControllerName());
        $audit->setActionName($dispatcher->getActionName());
        $audit->setStartTime($this->start_time);
        $audit->setEndTime(date("Y-m-d H:i:s"));
        $audit->setIpAddress($this->request->getClientAddress());
        $audit->setClient($this->request->getUserAgent());
        $audit->setBrowser($this->request->getUserAgent());
        if(isset($data['password'])) unset($data['password']);
        if(isset($data['identifier'])) unset($data['identifier']);
        if(isset($data['_url'])) unset($data['_url']);
        $audit->setParameters(json_encode($data));

        $audit->save();
    }

    protected function getPost($name, $filters = null, $defaultValue = null){
        return $this->request->getPost($name, $filters, $defaultValue);
    }

    protected function get($name, $filters = null, $defaultValue = null){
        return $this->request->get($name, $filters, $defaultValue);
    }

    protected function getQuery($name, $filters = null, $defaultValue = null){
        return $this->request->getQuery($name, $filters, $defaultValue);
    }
}
