<?php


class ManifestController extends ControllerBase {
    public function receiveAction(){
        $this->auth->allowOnly([Role::OFFICER]);

        $manifest_id = $this->request->getPost('manifest_id');
        $status = $this->request->getPost('status');

        if (in_array(null, [$manifest_id, $status])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($status, [Status::MANIFEST_RESOLVED, Status::MANIFEST_HAS_ISSUE])){
            return $this->response->sendError(ResponseMessage::INVALID_STATUS);
        }

        $manifest = Manifest::getById($manifest_id);
        if ($manifest != false){
            $auth_data = $this->auth->getData();

            if ($manifest->getToBranchId() != $auth_data['branch_id']){
                return $this->response->sendError(ResponseMessage::MANIFEST_NOT_RECEIVABLE);
            }

            $manifest->recieve($this->auth->getClientId(), $status);
            if ($manifest->save()) {
                return $this->response->sendSuccess();
            }
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    public function getOneAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $manifest_id = $this->request->getQuery('manifest_id');

        $with_from_branch = $this->request->getQuery('with_from_branch');
        $with_to_branch = $this->request->getQuery('with_to_branch');
        $with_sender_admin = $this->request->getQuery('with_sender_admin');
        $with_receiver_admin = $this->request->getQuery('with_receiver_admin');
        $with_holder = $this->request->getQuery('with_holder');

        if (is_null($manifest_id)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $fetch_with = [];
        if (!is_null($with_from_branch)){ $fetch_with['with_from_branch'] = true; }
        if (!is_null($with_to_branch)){ $fetch_with['with_to_branch'] = true; }
        if (!is_null($with_sender_admin)){ $fetch_with['with_sender_admin'] = true; }
        if (!is_null($with_receiver_admin)){ $fetch_with['with_receiver_admin'] = true; }
        if (!is_null($with_holder)){ $fetch_with['with_holder'] = true; }

        $manifest = Manifest::fetchOne($manifest_id, $fetch_with);
        if ($manifest != false){
            return $this->response->sendSuccess($manifest);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $type_id = $this->request->getQuery('type_id');
        $from_branch_id = $this->request->getQuery('from_branch_id');
        $to_branch_id = $this->request->getQuery('to_branch_id');
        $sender_admin_id = $this->request->getQuery('sender_admin_id');
        $receiver_admin_id = $this->request->getQuery('receiver_admin_id');
        $held_by_id = $this->request->getQuery('held_by_id');
        $status = $this->request->getQuery('status');

        $with_from_branch = $this->request->getQuery('with_from_branch');
        $with_to_branch = $this->request->getQuery('with_to_branch');
        $with_sender_admin = $this->request->getQuery('with_sender_admin');
        $with_receiver_admin = $this->request->getQuery('with_receiver_admin');
        $with_holder = $this->request->getQuery('with_holder');

        $filter_by = [];
        if (!is_null($type_id)){ $filter_by['type_id'] = $type_id; }
        if (!is_null($from_branch_id)){ $filter_by['from_branch_id'] = $from_branch_id; }
        if (!is_null($to_branch_id)){ $filter_by['to_branch_id'] = $to_branch_id; }
        if (!is_null($sender_admin_id)){ $filter_by['sender_admin_id'] = $sender_admin_id; }
        if (!is_null($receiver_admin_id)){ $filter_by['receiver_admin_id'] = $receiver_admin_id; }
        if (!is_null($held_by_id)){ $filter_by['held_by_id'] = $held_by_id; }
        if (!is_null($status)){ $filter_by['status'] = $status; }

        $fetch_with = [];
        if (!is_null($with_from_branch)){ $fetch_with['with_from_branch'] = true; }
        if (!is_null($with_to_branch)){ $fetch_with['with_to_branch'] = true; }
        if (!is_null($with_sender_admin)){ $fetch_with['with_sender_admin'] = true; }
        if (!is_null($with_receiver_admin)){ $fetch_with['with_receiver_admin'] = true; }
        if (!is_null($with_holder)){ $fetch_with['with_holder'] = true; }

        return $this->response->sendSuccess(Manifest::fetchAll($offset, $count, $filter_by, $fetch_with));
    }
} 