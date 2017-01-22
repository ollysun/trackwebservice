<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 1/20/2017
 * Time: 3:03 PM
 */
class BmcentresController extends ControllerBase
{
    public function getAllAction(){
        $this->auth->allowOnly(Role::ADMIN);

        return $this->response->sendSuccess(BmCentre::fetchAll(0, 0, [], [], false));
    }
}