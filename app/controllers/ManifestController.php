<?php


class ManifestController extends ControllerBase {
    /**
     * This action is used to acknowledge the receipt of the manifest at the destination and can set the status of the
     * manifest to resolved (if all is well) or has_issue(if there is an issue maybe the parcels are not completely
     * cleared).
     * @return $this
     */
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

    /**
     * Fetches the details of a manifest. More info can be hydrated using certain params starting with 'with'.
     * @return $this
     */
    public function getOneAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $fetch_params = ['with_from_branch', 'with_to_branch', 'with_sender_admin', 'with_receiver_admin', 'with_holder', 'with_parcels'];

        foreach ($fetch_params as $param){
            $$param = $this->request->getQuery($param);
        }

        $manifest_id = $this->request->getQuery('manifest_id');
        if (is_null($manifest_id)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $fetch_with = [];
        foreach ($fetch_params as $param){
            if (!is_null($$param)){ $fetch_with[$param] = true; }
        }

        $manifest = Manifest::fetchOne($manifest_id, $fetch_with);
        if ($manifest != false){
            return $this->response->sendSuccess($manifest);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    /**
     * This fetches a paginated list of manifest using filter params. More info can be hydrated using certain params starting with 'with'.
     * @return $this
     */
    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_params = [
            'type_id', 'from_branch_id', 'to_branch_id', 'sender_admin_id', 'receiver_admin_id', 'held_by_id', 'status',
            'start_created_date', 'end_created_date', 'id'
        ];

        $fetch_params = ['with_total_count', 'with_from_branch', 'with_to_branch', 'with_sender_admin', 'with_receiver_admin', 'with_holder'];

        $possible_params = array_merge($filter_params, $fetch_params);

        foreach ($possible_params as $param){
            $$param = $this->request->getQuery($param);
        }

        $filter_by = [];
        foreach ($filter_params as $param){
            if (!is_null($$param)){ $filter_by[$param] = $$param; }
        }

        $fetch_with = [];
        foreach ($fetch_params as $param){
            if (!is_null($$param)){ $fetch_with[$param] = true; }
        }

        $manifests = Manifest::fetchAll($offset, $count, $filter_by, $fetch_with);
        $result = [];
        if (!empty($with_total_count)) {
            $count = Manifest::getTotalCount($filter_by);
            $result = [
                'total_count' => $count,
                'manifests' => $manifests
            ];
        } else {
            $result = $manifests;
        }

        return $this->response->sendSuccess($result);
    }

    /**
     * Gets the total manifest count based on possible filters.
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function countAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $filter_params = [
            'type_id', 'from_branch_id', 'to_branch_id', 'sender_admin_id', 'receiver_admin_id', 'held_by_id', 'status',
            'start_created_date', 'end_created_date', 'id'
        ];

        $filter_by = [];
        foreach ($filter_params as $param){
            $$param = $this->request->getQuery($param);
            if (!is_null($$param)){ $filter_by[$param] = $$param; }
        }

        $count = Manifest::getTotalCount($filter_by);
        if ($count === null) {
            return $this->response->sendError();
        }
        return $this->response->sendSuccess($count);
    }
} 