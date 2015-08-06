<?php


class OnforwardingchargeController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description', null, ' ');
        $amount = $this->request->getPost('amount');
        $percentage = $this->request->getPost('percentage');

        if (in_array(null, [$name, $code, $amount, $percentage])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $amount = floatval($amount);
        if ($amount <= 0.0){
            return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
        }

        $charge = OnforwardingCharge::fetchByDetails($name, $code);
        if ($charge != false){
            if ($charge->hasSameName($name)){
                return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_NAME_EXISTS);
            }else if ($charge->hasSameCode($code)){
                return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_CODE_EXISTS);
            }
            return $this->response->sendError();
        }

        $charge = new OnforwardingCharge();
        $charge->initData($name, $code, $description, $amount, $percentage);
        if ($charge->save()){
            return $this->response->sendSuccess(['id' => $charge->getId()]);
        }
        return $this->response->sendError();
    }

    public function editAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $charge_id = $this->request->getPost('charge_id');
        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description', null, ' ');
        $amount = $this->request->getPost('amount');
        $percentage = $this->request->getPost('percentage');

        if (in_array(null, [$charge_id, $name, $code])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $amount = floatval($amount);
        if ($amount <= 0.0){
            return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
        }

        $charge = OnforwardingCharge::fetchByDetails($name, $code, $charge_id);

        if ($charge != false){
            if ($charge->hasSameName($name)){
                return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_NAME_EXISTS);
            }else if ($charge->hasSameCode($code)){
                return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_CODE_EXISTS);
            }
            return $this->response->sendError();
        }

        $charge = OnforwardingCharge::fetchById($charge_id);
        if ($charge != false){
            $charge->edit($name, $code, $description, $amount, $percentage);
            if ($charge->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_DOES_NOT_EXIST);
    }

    public function changeStatusAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $charge_id = $this->request->getPost('charge_id');
        $status = $this->request->getPost('status');

        if (in_array(null, [$charge_id, $status])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $charge = OnforwardingCharge::fetchById($charge_id);
        if ($charge != false){
            $charge->changeStatus($status);
            if ($charge->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_DOES_NOT_EXIST);
    }

    public function fetchByIdAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $charge_id = $this->request->getQuery('charge_id');
        if (in_array(null, [$charge_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $charge = OnforwardingCharge::fetchById($charge_id);
        if ($charge != false){
            return $this->response->sendSuccess($charge->getData());
        }
        return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_DOES_NOT_EXIST);
    }

    public function fetchByCodeAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $code = $this->request->getQuery('code');
        if (in_array(null, [$code])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $charge = OnforwardingCharge::fetchByCode($code);
        if ($charge != false){
            return $this->response->sendSuccess($charge->getData());
        }
        return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_DOES_NOT_EXIST);
    }

    public function fetchAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $status = $this->request->getQuery('status');

        $filter_by = [];
        if (!is_null($status)){ $filter_by['status'] = $status; }

        return $this->response->sendSuccess(OnforwardingCharge::fetchAll($offset, $count, $filter_by));
    }
} 