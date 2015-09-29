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
     * @param Phalcon\Mvc\Dispatcher $dispatcher
     */
    public function beforeExecuteRoute($dispatcher){

    }

    /**
     * @param Phalcon\Mvc\Dispatcher $dispatcher
     */
    public function afterExecuteRoute($dispatcher){

    }
}
