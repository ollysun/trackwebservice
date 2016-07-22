<?php


use Phalcon\Exception;

class ZoneController extends ControllerBase
{
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description', null, ' ');

        if (in_array(null, [$name, $code])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchByDetails($name, $code);
        if ($zone != false) {
            if ($zone->hasSameName($name)) {
                return $this->response->sendError(ResponseMessage::ZONE_NAME_EXISTS);
            } else if ($zone->hasSameCode($code)) {
                return $this->response->sendError(ResponseMessage::ZONE_CODE_EXISTS);
            }
            return $this->response->sendError();
        }

        $zone = new Zone();
        $zone->initData($name, $code, $description);
        if ($zone->save()) {
            return $this->response->sendSuccess(['id' => $zone->getId()]);
        }
        return $this->response->sendError();
    }

    public function editAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $zone_id = $this->request->getPost('zone_id');
        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description', null, ' ');

        if (in_array(null, [$zone_id, $name, $code])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchByDetails($name, $code, $zone_id);

        if ($zone != false) {
            if ($zone->hasSameName($name)) {
                return $this->response->sendError(ResponseMessage::ZONE_NAME_EXISTS);
            } else if ($zone->hasSameCode($code)) {
                return $this->response->sendError(ResponseMessage::ZONE_CODE_EXISTS);
            }
            return $this->response->sendError();
        }

        $zone = Zone::fetchById($zone_id);
        if ($zone != false) {
            $zone->edit($name, $code, $description);
            if ($zone->save()) {
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function changeStatusAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $zone_id = $this->request->getPost('zone_id');
        $status = $this->request->getPost('status');

        if (in_array(null, [$zone_id, $status])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchById($zone_id);
        if ($zone != false) {
            $zone->changeStatus($status);
            if ($zone->save()) {
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function fetchByIdAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $zone_id = $this->request->getQuery('zone_id');
        if (in_array(null, [$zone_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchById($zone_id);
        if ($zone != false) {
            return $this->response->sendSuccess($zone->getData());
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function fetchByCodeAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $code = $this->request->getQuery('code');
        if (in_array(null, [$code])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchByCode($code);
        if ($zone != false) {
            return $this->response->sendSuccess($zone->getData());
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function fetchAllAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $status = $this->request->getQuery('status');

        $filter_by = [];
        if (!is_null($status)) {
            $filter_by['status'] = $status;
        }

        return $this->response->sendSuccess(Zone::fetchAll($offset, $count, $filter_by));
    }

    /**
     * Adds billing
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function addBillingAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $zone_id = $this->request->getPost('zone_id');
        $weight_range_id = $this->request->getPost('weight_range_id');
        $base_cost = $this->request->getPost('base_cost');
        $base_percentage = $this->request->getPost('base_percentage'); //value must have been divided by 100 e.g 0.95 for 95%
        $increment_cost = $this->request->getPost('increment_cost');
        $increment_percentage = $this->request->getPost('increment_percentage'); //value must have been divided by 100 e.g 0.95 for 95%

        if (in_array(null, [$zone_id, $weight_range_id, $base_cost, $base_percentage, $increment_cost, $increment_percentage])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if ($base_cost <= 0 OR $increment_cost < 0) {
            return $this->response->sendError(ResponseMessage::INVALID_VALUES);
        }

        $zone = Zone::fetchById($zone_id);
        if ($zone == false) {
            return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
        }

        $weight_range = WeightRange::fetchById($weight_range_id);
        if ($weight_range == false) {
            return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_DOES_NOT_EXIST);
        }

        $billing = WeightBilling::fetchByDetails($zone_id, $weight_range_id);
        if ($billing != false) {
            return $this->response->sendError(ResponseMessage::BILLING_EXISTS);
        }

        $billing = new WeightBilling();
        $billing->initData($zone_id, $weight_range_id, $base_cost, $base_percentage, $increment_cost, $increment_percentage);
        if ($billing->save()) {
            return $this->response->sendSuccess(['id' => $billing->getId()]);
        }
        return $this->response->sendError();
    }

    /**
     * Edits billing
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function editBillingAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $weight_billing_id = $this->request->getPost('weight_billing_id');
        $base_cost = $this->request->getPost('base_cost');
        $base_percentage = $this->request->getPost('base_percentage');//value must have been divided by 100 e.g 0.95 for 95%
        $increment_cost = $this->request->getPost('increment_cost');
        $increment_percentage = $this->request->getPost('increment_percentage');//value must have been divided by 100 e.g 0.95 for 95%

        if (in_array(null, [$weight_billing_id, $base_cost, $base_percentage, $increment_cost, $increment_percentage])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if ($base_cost <= 0 OR $increment_cost < 0) {
            return $this->response->sendError(ResponseMessage::INVALID_VALUES);
        }

        $billing = WeightBilling::fetchById($weight_billing_id);
        if ($billing != false) {
            $billing->editBilling($base_cost, $base_percentage, $increment_cost, $increment_percentage);
            if ($billing->save()) {
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::BILLING_NOT_EXISTS);
    }

    public function removeBillingAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $weight_billing_id = $this->request->getPost('weight_billing_id');

        if (in_array(null, [$weight_billing_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $billing = WeightBilling::fetchById($weight_billing_id);
        if ($billing != false) {
            if ($billing->delete()) {
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::BILLING_NOT_EXISTS);
    }

    public function fetchBillingByIdAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);
        $weight_billing_id = $this->request->getQuery('weight_billing_id');

        if (in_array(null, [$weight_billing_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $billing = WeightBilling::fetchOne($weight_billing_id);
        if ($billing != false) {
            return $this->response->sendSuccess($billing);
        }
        return $this->response->sendError(ResponseMessage::BILLING_NOT_EXISTS);
    }

    public function fetchBillingAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $zone_id = $this->request->getQuery('zone_id');//optional
        $billing_plan_id = $this->request->getQuery('billing_plan_id');//optional
        $weight_range_id = $this->request->getQuery('weight_range_id');//optional
        $send_all = $this->request->getQuery('send_all');//optional - nullifies offset and count

        $filter_by = [];
        if (!is_null($zone_id)) {
            $filter_by['zone_id'] = $zone_id;
        }
        if (!is_null($weight_range_id)) {
            $filter_by['weight_range_id'] = $weight_range_id;
        }
        if (!is_null($billing_plan_id)) {
            $filter_by['billing_plan_id'] = $billing_plan_id;
        }
        if (!is_null($send_all)) {
            $filter_by['send_all'] = $send_all;
        }

        return $this->response->sendSuccess(WeightBilling::fetchAll($offset, $count, $filter_by));
    }

    public function saveMatrixAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $matrix_info = $this->request->getJsonRawBody(true);
//        $matrix_info = [
//            ['to_branch_id' => 4, 'from_branch_id' => 5, 'zone_id' => 1],
//            ['to_branch_id' => 5, 'from_branch_id' => 9, 'zone_id' => 2],
//            ['to_branch_id' => 4, 'from_branch_id' => 9, 'zone_id' => 3],
//            ['to_branch_id' => 4, 'from_branch_id' => 12, 'zone_id' => 1],
//        ];
        $bad_matrix_info = ZoneMatrix::saveMatrix($matrix_info);
        return $this->response->sendSuccess(['bad_matrix_info' => $bad_matrix_info]);
    }

    public function removeMatrixAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $zone_matrix_id = $this->request->getPost('zone_matrix_id');

        if ($zone_matrix_id == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone_matrix = ZoneMatrix::fetchById($zone_matrix_id);
        if ($zone_matrix != false) {
            if ($zone_matrix->delete()) {
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::ZONE_MATRIX_NOT_EXIST);
    }

    public function getMatrixAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $zone_id = $this->request->getQuery('zone_id');//optional
        $branch_id = $this->request->getQuery('branch_id');//optional
        $other_branch_id = $this->request->getQuery('other_branch_id');//optional

        $filter_by = [];
        if (!is_null($zone_id)) {
            $filter_by['zone_id'] = $zone_id;
        }
        if (!is_null($branch_id)) {
            $filter_by['branch_id'] = $branch_id;
        }
        if (!is_null($other_branch_id)) {
            $filter_by['other_branch_id'] = $other_branch_id;
        }

        return $this->response->sendSuccess(ZoneMatrix::fetchAll($filter_by));
    }

    public function calcBillingAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER]);

        $from_branch_id = $this->request->getPost('from_branch_id');
        $to_branch_id = $this->request->getPost('to_branch_id');
        $onforwarding_billing_plan_id = $this->request->getPost('onforwarding_billing_plan_id');
        $city_id = $this->request->getPost('city_id');
        $weight = $this->request->getPost('weight');
        $weight_billing_plan_id = $this->request->getPost('weight_billing_plan_id');

        if (in_array(null, [$city_id, $from_branch_id, $to_branch_id, $onforwarding_billing_plan_id, $weight, $weight_billing_plan_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        try {
            $amountDue = Zone::calculateBilling($from_branch_id, $to_branch_id, $weight, $weight_billing_plan_id, $city_id, $onforwarding_billing_plan_id);
            return $this->response->sendSuccess($amountDue);
        } catch (Exception $ex) {
            return $this->response->sendError($ex->getMessage());
        }
    }

    public function getTransitTimeAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $branch_id = $this->request->getQuery('branch_id');//optional
        $other_branch_id = $this->request->getQuery('other_branch_id');//optional

        $filter_by = [];

        if (!is_null($branch_id)) {
            $filter_by['branch_id'] = $branch_id;
        }
        if (!is_null($other_branch_id)) {
            $filter_by['other_branch_id'] = $other_branch_id;
        }

        return $this->response->sendSuccess(TransitTime::fetchAll($filter_by));
    }


    public function saveTransitTimeAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $transit_time_info = $this->request->getJsonRawBody(true);
//        $matrix_info = [
//            ['to_branch_id' => 4, 'from_branch_id' => 5, 'zone_id' => 1],
//            ['to_branch_id' => 5, 'from_branch_id' => 9, 'zone_id' => 2],
//            ['to_branch_id' => 4, 'from_branch_id' => 9, 'zone_id' => 3],
//            ['to_branch_id' => 4, 'from_branch_id' => 12, 'zone_id' => 1],
//        ];
        $bad_transit_time_info = TransitTime::saveTransitTime($transit_time_info);
        return $this->response->sendSuccess(['bad_matrix_info' => $bad_transit_time_info]);
    }

    public function removeTransitTimeAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $transit_time_id = $this->request->getPost('transit_time_id');

        if ($transit_time_id == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $transit_time = TransitTime::fetchById($transit_time_id);
        if ($transit_time != false) {
            if ($transit_time->delete()) {
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::TRANSIT_TIME_NOT_EXIST);
    }

} 