<?php


class BranchController extends ControllerBase
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Wale Lawal <wale@cottacush.com>
     * @return $this
     */
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $branch_type = $this->request->getPost('branch_type');
        $state_id = $this->request->getPost('state_id');
        $address = $this->request->getPost('address');
        $status = $this->request->getPost('status');

        $hub_id = $this->request->getPost('hub_id'); //optional only used for EC creation

        if (in_array(null, array($name, $branch_type, $state_id, $address, $status))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($status, [Status::ACTIVE, Status::INACTIVE])) {
            return $this->response->sendError(ResponseMessage::INVALID_STATUS);
        }

        if ($branch_type == BranchType::EC) {
            if ($hub_id == null) {
                return $this->response->sendError(ResponseMessage::NO_HUB_PROVIDED);
            }

            $hub = Branch::fetchById($hub_id);
            if ($hub == false) {
                return $this->response->sendError(ResponseMessage::HUB_NOT_EXISTING);
            } else if ($hub->getBranchType() != BranchType::HUB) {
                return $this->response->sendError(ResponseMessage::INVALID_HUB_PROVIDED);
            }
        }

        $branch = new Branch();
        $branch->initData($name, $branch_type, $state_id, $address, $status);
        if ($branch->saveBranch($hub_id)) {
            return $this->response->sendSuccess(['id' => $branch->getId(), 'code' => $branch->getCode()]);
        }
        return $this->response->sendError();
    }

    public function editDetailsAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $branch_id = $this->request->getPost('branch_id');
        $name = $this->request->getPost('name');
        $state_id = $this->request->getPost('state_id');
        $address = $this->request->getPost('address');

        if (in_array(null, array($branch_id, $name, $state_id, $address))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $branch = Branch::fetchById($branch_id);
        if ($branch == false) {
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        $branch->editDetails($name, $state_id, $address);
        if ($branch->save()) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }

    public function changeStatusAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $branch_id = $this->request->getPost('branch_id');
        $status = $this->request->getPost('status');

        if (in_array(null, array($branch_id, $status))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($status, [Status::ACTIVE, Status::INACTIVE])) {
            return $this->response->sendError(ResponseMessage::INVALID_STATUS);
        }

        $branch = Branch::fetchById($branch_id);
        if ($branch == false) {
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        $branch->changeStatus($status);
        if ($branch->save()) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }

    public function getAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN]);

        $branch_id = $this->request->getQuery('branch_id');
        $code = $this->request->getQuery('code');

        if ($branch_id == null and $code == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $filter_by = [];
        if (!is_null($branch_id)) {
            $filter_by['branch_id'] = $branch_id;
        }
        if (!is_null($code)) {
            $filter_by['code'] = $code;
        }

        $branch = Branch::fetchOne($filter_by);

        if ($branch == null) {
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        return $this->response->sendSuccess($branch);
    }

    /**
     * Get all ECs
     * @author Adegoke Obasa <goke@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function getAllECAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN]);

        $hub_id = $this->request->getQuery('hub_id');
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);
        $paginate = ($paginate == 0 || $paginate == false) ? false : true;
        $with_parent = $this->request->getQuery('with_parent');

        $branch_data = Branch::fetchAllEC($hub_id, $paginate, $offset, $count, $with_parent);

        if ($paginate) {
            $total_count = Branch::getEcCount($hub_id);
            $result['branch_data'] = $branch_data;
            $result['total_count'] = $total_count;
        }
        else {
            $result = $branch_data;
        }
        return $this->response->sendSuccess($result);
    }

    public function getAllHubAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN, Role::COMPANY_ADMIN, Role::COMPANY_OFFICER]);

        $state_id = $this->request->getQuery('state_id', null, null);
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);

        $hub_data = Branch::fetchAllHub($state_id, $paginate, $offset, $count);

        if ($paginate) {
            $total_count = Branch::getHubCount($state_id);
            $result['hub_data'] = $hub_data;
            $result['total_count'] = $total_count;
        }
        else {
            $result = $hub_data;
        }
        return $this->response->sendSuccess($result);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Wale Lawal <wale@cottacush.com>
     * @return $this
     */
    public function getAllAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, true);
        $paginate = ($paginate == "0" || $paginate == "false") ? false : true;

        $state_id = $this->request->getQuery('state_id');
        $branch_type = $this->request->getQuery('branch_type');
        $with_parent = $this->request->getQuery('with_parent');
        $with_region = $this->request->getQuery('with_region');

        $filter_by = [];
        if (!is_null($state_id)) {
            $filter_by['state_id'] = $state_id;
        }
        if (!is_null($branch_type)) {
            $filter_by['branch_type'] = $branch_type;
        }

        $fetch_with = [];
        if (!is_null($with_parent)) {
            $fetch_with['with_parent'] = true;
        }

        if(!is_null($with_region)){
            $fetch_with['with_region'] = true;
        }

        $branch_data = Branch::fetchAll($offset, $count, $filter_by, $fetch_with, $paginate);

        if ($paginate) {
            $total_count = Branch::getTotalCount($filter_by);
            $result = ['total_count' => $total_count, 'branch_data' => $branch_data];
            return $this->response->sendSuccess($result);
        } else {
            return $this->response->sendSuccess($branch_data);
        }

    }

    public function relinkAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $ec_id = $this->request->getPost('ec_id');
        $hub_id = $this->request->getPost('hub_id');

        if (in_array(null, array($ec_id, $hub_id))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $ec = Branch::fetchById($ec_id);
        if ($ec == false) {
            return $this->response->sendError(ResponseMessage::EC_NOT_EXISTING);
        } else if ($ec->getBranchType() != BranchType::EC) {
            return $this->response->sendError(ResponseMessage::INVALID_EC_PROVIDED);
        }

        $hub = Branch::getParentById($ec_id);
        if ($hub == false) {
            return $this->response->sendError(ResponseMessage::HUB_NOT_EXISTING);
        } else if ($hub->getBranchType() != BranchType::HUB) {
            return $this->response->sendError(ResponseMessage::INVALID_HUB_PROVIDED);
        }

        $link = BranchMap::getByChildId($ec_id);
        if ($link == false) {
            return $this->response->sendError(ResponseMessage::RELINK_NOT_POSSIBLE);
        }

        $link->setParentId($hub_id);
        if ($link->save()) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }
}