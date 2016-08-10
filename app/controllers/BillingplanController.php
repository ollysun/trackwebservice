<?php


use PhalconUtils\Validation\RequestValidation;
use PhalconUtils\Validation\Validators\Model;

class BillingplanController extends ControllerBase
{
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $type = $this->request->getPost('type');
        $company_id = $this->request->getPost('company_id'); //optional, null if the billing is a default plan

        if (in_array(null, [$name, $type])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($type, [BillingPlan::TYPE_WEIGHT, BillingPlan::TYPE_ON_FORWARDING, BillingPlan::TYPE_NUMBER, BillingPlan::TYPE_WEIGHT_AND_ON_FORWARDING])) {
            return $this->response->sendError(ResponseMessage::INVALID_TYPE);
        }

        $plan = BillingPlan::fetchByName($name, $company_id);
        if ($plan != false) {
            return $this->response->sendError(ResponseMessage::ANOTHER_HAS_SAME_NAME);
        }

        $plan = new BillingPlan();
        $plan->initData($name, $type, $company_id);
        if ($plan->save()) {
            //TODO Check status of cloning
            BillingPlan::cloneDefaultBilling($plan->getId());
            return $this->response->sendSuccess(['id' => $plan->getId()]);
        }
        return $this->response->sendError(ResponseMessage::BILLING_PLAN_NOT_SAVED);
    }

    public function editAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $plan_id = $this->request->getPost('plan_id');
        $name = $this->request->getPost('name');
        $status = $this->request->getPost('status');

        if (in_array(null, [$name, $status, $plan_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $plan = BillingPlan::fetchById($plan_id);
        if ($plan != false) {
            $other_plan = BillingPlan::fetchByName($name, $plan->getCompanyId(), $plan_id);
            if ($other_plan != false) {
                return $this->response->sendError(ResponseMessage::ANOTHER_HAS_SAME_NAME);
            }

            $plan->edit($name, $status);
            if ($plan->save()) {
                return $this->response->sendSuccess();
            }
            return $this->response->sendError(ResponseMessage::BILLING_PLAN_NOT_SAVED);
        }
        return $this->response->sendError(ResponseMessage::BILLING_PLAN_DOES_NOT_EXIST);
    }

    public function getAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $plan_id = $this->request->getQuery('plan_id');
        if (!isset($plan_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $plan = BillingPlan::fetchOne($plan_id);
        if ($plan != false) {
            return $this->response->sendSuccess($plan);
        }
        return $this->response->sendError(ResponseMessage::BILLING_PLAN_DOES_NOT_EXIST);
    }

    /**
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return array
     */
    private function getFilterParams()
    {
        $filter_params = ['company_id', 'type', 'status', 'company_only', 'no_paginate', 'company_name'];
        $filter_by = [];
        foreach ($filter_params as $param) {
            $$param = $this->request->getQuery($param);
            if (!is_null($$param)) {
                if ($param == 'company_id' and $$param == strtolower(trim('null'))) {
                    $filter_by[$param] = null;
                } else {
                    $filter_by[$param] = $$param;
                }
            }
        }

        return $filter_by;
    }

    public function getAllAction()
    {
        $this->auth->allowOnly([Role::SWEEPER, Role::DISPATCHER, Role::ADMIN, Role::OFFICER, Role::COMPANY_ADMIN, Role::COMPANY_OFFICER, Role::SALES_AGENT]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $with_company = $this->request->getQuery('with_company');
        $with_total_count = $this->request->getQuery('with_total_count');

        $fetch_with = [];
        if (!is_null($with_company)) {
            $fetch_with['with_company'] = true;
        }

        $filter_by = $this->getFilterParams();

        $plans = BillingPlan::fetchAll($offset, $count, $filter_by, $fetch_with);
        $result = [];
        if ($with_total_count != null) {
            $count = BillingPlan::fetchCount($filter_by);
            $result = [
                'total_count' => $count,
                'plans' => $plans
            ];
        } else {
            $result = $plans;
        }

        return $this->response->sendSuccess($result);
    }

    public function countAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);
        $filter_by = $this->getFilterParams();
        $count = BillingPlan::fetchCount($filter_by);

        if ($count === null) {
            return $this->response->sendError();
        }
        return $this->response->sendSuccess($count);
    }

    /**
     * Get's cities with onforwarding charges for a particular billing plan
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function getCitiesWithChargeAction()
    {
        $billing_plan_id = $this->request->getQuery('billing_plan_id', null);
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);
        $with_total_count = $this->request->getQuery('with_total_count', null);
        $filter_by = array_merge(['billing_plan_id' => $billing_plan_id], $this->request->getQuery());

        $fetch_with = array_merge(['with_onforwarding_charge'], Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery()));

        if (is_null($billing_plan_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $cities = OnforwardingCity::getAll($billing_plan_id, $count, $offset, $fetch_with, $filter_by, $paginate);
        if (!empty($with_total_count)) {
            $count = OnforwardingCity::getTotalCount($filter_by);
            $result = [
                'total_count' => $count,
                'cities' => $cities
            ];
        } else {
            $result = $cities;
        }

        return $this->response->sendSuccess($result);
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @return $this
     */
    public function getAllBillingPlanNamesAction()
    {
        $billingPlan = BillingPlan::find(array("columns" => "id,name"))->toArray();
        return $this->response->sendSuccess($billingPlan);
    }

    /**
     * reset onforwarding charges for billing to zero
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function resetOnforwardingChargesToZeroAction()
    {
        $postData = $this->request->getJsonRawBody();
        $validation = new RequestValidation($postData);
        $validation->setRequiredFields(['billing_plan_id']);
        $validation->add('billing_plan_id', new Model([
            'model' => BillingPlan::class
        ]));

        if (!$validation->validate()) {
            return $this->response->sendError($validation->getMessages());
        }

        $billingPlan = BillingPlan::findFirst($postData->billing_plan_id);
        $result = $billingPlan->resetOnforwardingChargesToZero();

        if ($result) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError('Could not reset onforwarding charges');
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @return $this
     */
    public function cloneBillingPlanAction()
    {
        $companyId = $this->request->getPost('company_id');
        $baseBillingPlanId = $this->request->getPost('base_billing_plan_id');
        $billingPlanName = $this->request->getPost('billing_plan_name');
        $billingPlan = BillingPlan::fetchByName($billingPlanName, $companyId);
        if ($billingPlan != false) {
            return $this->response->sendError(ResponseMessage::ANOTHER_HAS_SAME_NAME);
        }
        $newBillingPlan = new BillingPlan();
        $newBillingPlan->initData($billingPlanName, BillingPlan::TYPE_WEIGHT_AND_ON_FORWARDING, $companyId);
        if (!$newBillingPlan->save()) {
            return $this->response->sendError(ResponseMessage::BILLING_PLAN_NOT_SAVED);
        }

        $result = (new BillingPlan())->cloneBillingPlan($baseBillingPlanId, $newBillingPlan);
        if (!$result) {
            return $this->response->sendError(ResponseMessage::BILLING_PLAN_NOT_CLONED);
        }
        return $this->response->sendSuccess();
    }
}