<?php


class BranchController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $branch_type = $this->request->getPost('branch_type');
        $state_id = $this->request->getPost('state_id');
        $address = $this->request->getPost('address');
        $status = $this->request->getPost('status');

        $hub_id = $this->request->getPost('hub_id'); //optional only used for EC creation

        if (in_array(null, array($name, $branch_type, $state_id, $address, $status))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($status, [Status::ACTIVE, Status::INACTIVE])){
            return $this->response->sendError(ResponseMessage::INVALID_STATUS);
        }

        if ($branch_type == BranchType::EC){
            if ($hub_id == null){
                return $this->response->sendError(ResponseMessage::NO_HUB_PROVIDED);
            }

            $hub = Branch::fetchById($hub_id);
            if ($hub == false){
                return $this->response->sendError(ResponseMessage::HUB_NOT_EXISTING);
            }else if ($hub->getBranchType() != BranchType::HUB){
                return $this->response->sendError(ResponseMessage::INVALID_HUB_PROVIDED);
            }
        }

        $branch = new Branch();
        $branch->initData($name, $branch_type, $state_id, $address, $status);
        if ($branch->saveBranch($hub_id)){
            return $this->response->sendSuccess(['id' => $branch->getId(), 'code' => $branch->getCode()]);
        }
        return $this->response->sendError();
    }

    public function editDetailsAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $branch_id = $this->request->getPost('branch_id');
        $name = $this->request->getPost('name');
        $state_id = $this->request->getPost('state_id');
        $address = $this->request->getPost('address');

        if (in_array(null, array($branch_id, $name, $state_id, $address))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $branch = Branch::fetchById($branch_id);
        if ($branch == false){
           return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        $branch->editDetails($name, $state_id, $address);
        if ($branch->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }

    public function changeStatusAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $branch_id = $this->request->getPost('branch_id');
        $status = $this->request->getPost('status');

        if (in_array(null, array($branch_id, $status))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($status, [Status::ACTIVE, Status::INACTIVE])){
            return $this->response->sendError(ResponseMessage::INVALID_STATUS);
        }

        $branch = Branch::fetchById($branch_id);
        if ($branch == false){
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        $branch->changeStatus($status);
        if ($branch->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }

    public function getAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $branch_id = $this->request->getQuery('branch_id');
        $code = $this->request->getQuery('code');

        if ($branch_id == null and $code == null){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $filter_by = [];
        if (!is_null($branch_id)){ $filter_by['branch_id'] = $branch_id; }
        if (!is_null($code)){ $filter_by['code'] = $code; }

        $branch = Branch::fetchOne($filter_by);

        if ($branch == null){
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        return $this->response->sendSuccess($branch);
    }

    public function getAllECAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $hub_id = $this->request->getQuery('hub_id');
        if (is_null($hub_id)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        return $this->response->sendSuccess(Branch::fetchAllEC($hub_id));
    }

    public function getAllHubAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        return $this->response->sendSuccess(Branch::fetchAllHub());
    }

    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $state_id = $this->request->getQuery('state_id');
        $branch_type = $this->request->getQuery('branch_type');

        $with_parent  = $this->request->getQuery('with_parent');

        $filter_by = [];
        if (!is_null($state_id)){ $filter_by['state_id'] = $state_id; }
        if (!is_null($branch_type)){ $filter_by['branch_type'] = $branch_type; }

        $fetch_with = [];
        if (!is_null($with_parent)){ $fetch_with['with_parent'] = true; }

        return $this->response->sendSuccess(Branch::fetchAll($offset, $count, $filter_by, $fetch_with));
    }

    public function relinkAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $ec_id = $this->request->getPost('ec_id');
        $hub_id = $this->request->getPost('hub_id');

        if (in_array(null, array($ec_id, $hub_id))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $ec = Branch::fetchById($ec_id);
        if ($ec == false){
            return $this->response->sendError(ResponseMessage::EC_NOT_EXISTING);
        } else if ($ec->getBranchType() != BranchType::EC){
            return $this->response->sendError(ResponseMessage::INVALID_EC_PROVIDED);
        }

        $hub = Branch::getParentById($ec_id);
        if ($hub == false){
            return $this->response->sendError(ResponseMessage::HUB_NOT_EXISTING);
        } else if ($hub->getBranchType() != BranchType::HUB){
            return $this->response->sendError(ResponseMessage::INVALID_HUB_PROVIDED);
        }

        $link = BranchMap::getByChildId($ec_id);
        if ($link == false){
            return $this->response->sendError(ResponseMessage::RELINK_NOT_POSSIBLE);
        }

        $link->setParentId($hub_id);
        if ($link->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }
} 