<?php

use Phalcon\Mvc\Controller;

/**
 * Class ControllerBase
 *
 * @property PackedResponse response
 * @property Phalcon\Security security
 * @property mixed cache
 * @property Auth auth
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
