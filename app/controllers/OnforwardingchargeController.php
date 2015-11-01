<?php


class OnforwardingchargeController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description', null, ' ');
        $amount = $this->request->getPost('amount');
        $percentage = $this->request->getPost('percentage');
        $billing_plan_id = $this->request->getPost('billing_plan_id');

        if (in_array(null, [$name, $code, $amount, $percentage, $billing_plan_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $amount = floatval($amount);
        if ($amount < 0.0){
            return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
        }

        $plan = BillingPlan::fetchById($billing_plan_id);
        if (empty($plan)) {
            $this->response->sendError(ResponseMessage::INVALID_BILLING_PLAN);
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
        $charge->initData($name, $code, $description, $amount, $percentage, $billing_plan_id);
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
        if ($amount < 0.0){
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
        $billing_plan_id = $this->request->getQuery('billing_plan_id');
        $with_total_count = $this->request->getQuery('with_total_count');
        $send_all = $this->request->getQuery('send_all');

        $filter_by = [];
        if (!is_null($status)){ $filter_by['status'] = $status; }
        if (!is_null($billing_plan_id)){ $filter_by['billing_plan_id'] = $billing_plan_id; }
        if (!is_null($send_all)) { $filter_by['send_all'] = true; }

        $charges = OnforwardingCharge::fetchAll($offset, $count, $filter_by);
        $result = [];
        if ($with_total_count != null) {
            $count = OnforwardingCharge::chargeCount($filter_by);
            $result = [
                'total_count' => $count,
                'charges' => $charges
            ];
        } else {
            $result = $charges;
        }

        return $this->response->sendSuccess($result);
    }

    public function linkCityAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $city_id = $this->request->getPost('city_id');
        $charge_id = $this->request->getPost('charge_id');

        if (in_array(null, [$city_id, $charge_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $link = OnforwardingCity::fetchLink($city_id, $charge_id);
        if (!empty($link)){
            return $this->response->sendError(ResponseMessage::ONFORWARDING_CITY_EXISTS);
        }

        $link = new OnforwardingCity();
        $link->initData($city_id, $charge_id);
        if ($link->save()){
            return $this->response->sendSuccess(['id' => $link->getId()]);
        }
        return $this->response->sendError();
    }

    public function unlinkCityAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $city_id = $this->request->getPost('city_id');
        $charge_id = $this->request->getPost('charge_id');

        if (in_array(null, [$city_id, $charge_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $link = OnforwardingCity::fetchLink($city_id, $charge_id);
        if (empty($link)){
            return $this->response->sendError(ResponseMessage::ONFORWARDING_CITY_NOT_EXISTS);
        }

        if ($link->delete()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }

    public function fetchByCityAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $city_id = $this->request->getQuery('city_id');
        $billing_plan_id = $this->request->getQuery('billing_plan_id');

        if (in_array(null, [$city_id, $billing_plan_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $onforwarding_charge = OnforwardingCharge::fetchByCity($city_id, $billing_plan_id);
        if (empty($onforwarding_charge)){
            return $this->response->sendError(ResponseMessage::ONFORWARDING_CHARGE_DOES_NOT_EXIST);
        }
        return $this->response->sendSuccess($onforwarding_charge->getData());
    }
}