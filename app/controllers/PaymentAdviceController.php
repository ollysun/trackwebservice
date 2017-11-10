<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 1/26/2017
 * Time: 10:49 AM
 */


class PaymentAdviceController extends ControllerBase
{
    public function getAll(){
        $company_id = $this->request->getQuery('company_id');

        $filter_by = [];

        if($company_id){
            $filter_by['company_id'] = $company_id;
        }


    }

    public function createAction(){

    }

    public function changeStatusAction(){

    }
}