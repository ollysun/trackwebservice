<?php


class WeightrangeController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $min_weight = $this->request->getPost('min_weight');
        $max_weight = $this->request->getPost('max_weight');
        $increment_weight = $this->request->getPost('increment_weight');
        $billing_plan_id = $this->request->getPost('billing_plan_id');

        if (in_array(null, [$min_weight, $max_weight, $increment_weight])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $min_weight = floatval($min_weight);
        $max_weight = floatval($max_weight);
        $increment_weight = floatval($increment_weight);

        if (bccomp($min_weight, 0.0) == -1 or bccomp($max_weight, 0.0) == -1 ){
            return $this->response->sendError(ResponseMessage::NEGATIVE_WEIGHT);
        } else if ($increment_weight > $max_weight - $min_weight){
            return $this->response->sendError(ResponseMessage::INCREMENT_WEIGHT_TOO_LARGE);
        } else if ($increment_weight <= 0.0){
            return $this->response->sendError(ResponseMessage::INVALID_WEIGHT);
        }

        $weight_range = WeightRange::getIntersectingRange($min_weight, $max_weight, $billing_plan_id);
        if ($weight_range != false){
            return $this->response->sendError(
                'This weight range is intersecting another weight range('
                .$weight_range->getMinWeight() .',' . $weight_range->getMaxWeight() . ')'
            );
        }

        $weight_range = new WeightRange();
        $weight_range->initData($min_weight, $max_weight, $increment_weight, $billing_plan_id);
        if ($weight_range->save()){
            return $this->response->sendSuccess(['id' => $weight_range->getId()]);
        }
        return $this->response->sendError();
    }

    public function editAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $weight_range_id = $this->request->getPost('weight_range_id');
        $min_weight = $this->request->getPost('min_weight');
        $max_weight = $this->request->getPost('max_weight');
        $increment_weight = $this->request->getPost('increment_weight');

        if (in_array(null, [$min_weight, $max_weight, $increment_weight, $weight_range_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $min_weight = floatval($min_weight);
        $max_weight = floatval($max_weight);

        if (bccomp($min_weight, 0.0) == -1 or bccomp($max_weight, 0.0) == -1 ) {
            return $this->response->sendError(ResponseMessage::NEGATIVE_WEIGHT);
        } else if ($increment_weight > $max_weight - $min_weight){
            return $this->response->sendError(ResponseMessage::INCREMENT_WEIGHT_TOO_LARGE);
        } else if ($increment_weight <= 0.0){
            return $this->response->sendError(ResponseMessage::INVALID_WEIGHT);
        }

        $weight_range = WeightRange::getIntersectingRange($min_weight, $max_weight, $weight_range_id);
        if ($weight_range != false){
            return $this->response->sendError('This weight range is intersecting another weight range');
        }

        $weight_range = WeightRange::fetchById($weight_range_id);
        if ($weight_range != false){
            if (bccomp($weight_range->getMinWeight(), 0.0) == 0 and $weight_range->getMinWeight() != $min_weight){
                return $this->response->sendError(ResponseMessage::BASE_WEIGHT_CHANGE);
            }

            $weight_range->edit($min_weight, $max_weight, $increment_weight);
            if ($weight_range->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_DOES_NOT_EXIST);
    }

    public function changeStatusAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $weight_range_id = $this->request->getPost('weight_range_id');
        $status = $this->request->getPost('status');

        if (in_array(null, [$weight_range_id, $status])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $weight_range = WeightRange::fetchById($weight_range_id);
        if ($weight_range != false){
            $weight_range->changeStatus($status);
            if ($weight_range->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_DOES_NOT_EXIST);
    }

    public function fetchByIdAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $weight_range_id = $this->request->getQuery('weight_range_id');
        if (in_array(null, [$weight_range_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $weight_range = WeightRange::fetchById($weight_range_id);
        if ($weight_range != false){
            return $this->response->sendSuccess($weight_range->getData());
        }
        return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_DOES_NOT_EXIST);
    }

    public function fetchAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $status = $this->request->getQuery('status');
        $min_weight = $this->request->getQuery('min_weight');
        $max_weight = $this->request->getQuery('max_weight');
        $billing_plan_id = $this->request->getQuery('billing_plan_id');

        $filter_by = [];
        if (!is_null($status)){ $filter_by['status'] = $status; }
        if (!is_null($min_weight)){ $filter_by['min_weight'] = $min_weight; }
        if (!is_null($max_weight)){ $filter_by['max_weight'] = $max_weight; }
        if (!is_null($billing_plan_id)){ $filter_by['billing_plan_id'] = $billing_plan_id; }

        return $this->response->sendSuccess(WeightRange::fetchAll($offset, $count, $filter_by));
    }

    /**
     * Delete's a weight range
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function deleteAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);
        $weight_range_id = $this->request->getPost('weight_range_id');

        // Check if weight range is valid
        $weightRange = WeightRange::fetchById($weight_range_id);
        if(!$weightRange) {
            return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_DOES_NOT_EXIST);
        }

        // Check if there's a pricing for the weight range
        $weightBillings = WeightBilling::fetchAll(DEFAULT_OFFSET, DEFAULT_COUNT, ['weight_range_id' => $weight_range_id]);
        if(!empty($weightBillings)) {
            return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_STILL_HAS_EXISTING_BILLING);
        }

        if($weightRange->delete()) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::UNABLE_TO_DELETE_WEIGHT_RANGE);
    }
} 