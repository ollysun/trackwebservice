<?php


class BankaccountController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $owner_id = $this->request->getPost('owner_id');
        $owner_type = $this->request->getPost('owner_type');
        $bank_id = $this->request->getPost('bank_id');
        $account_name = $this->request->getPost('account_name');
        $account_no = $this->request->getPost('account_no');
        $sort_code = $this->request->getPost('sort_code');

        if (in_array(null, array($owner_id, $owner_type, $bank_id, $account_name, $account_no))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($owner_type, array(OWNER_TYPE_COMPANY, OWNER_TYPE_CUSTOMER))){
            return $this->response->sendError();
        }

        $bank_account = new BankAccount();
        $bank_account->initData($owner_id, $owner_type, $bank_id, $account_name, $account_no, $sort_code);
        if ($bank_account->save()){
            return $this->response->sendSuccess(['id' => $bank_account->getId()]);
        }
        return $this->response->sendError();
    }

    public function editAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $bank_account_id = $this->request->getPost('bank_account_id');
        $owner_id = $this->request->getPost('owner_id');
        $owner_type = $this->request->getPost('owner_type');

        $bank_id = $this->request->getPost('bank_id');
        $account_name = $this->request->getPost('account_name');
        $account_no = $this->request->getPost('account_no');
        $sort_code = $this->request->getPost('sort_code');

        if (in_array(null, array($bank_account_id, $owner_id, $owner_type, $bank_id, $account_name, $account_no))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $bank_account = BankAccount::fetchActive($bank_account_id, $owner_id, $owner_type);
        if ($bank_account != false){
            $bank_account->edit($bank_id, $account_name, $account_no, $sort_code);
            if ($bank_account->save()){
                return $this->response->sendSuccess();
            }
        }
        return $this->response->sendError();
    }

    public function removeAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $bank_account_id = $this->request->getPost('bank_account_id');
        $owner_id = $this->request->getPost('owner_id');
        $owner_type = $this->request->getPost('owner_type');

        if (in_array(null, array($bank_account_id, $owner_id, $owner_type))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $bank_account = BankAccount::fetchActive($bank_account_id, $owner_id, $owner_type);
        if ($bank_account != false){
            $bank_account->changeStatus(Status::REMOVED);
            if ($bank_account->save()){
                return $this->response->sendSuccess();
            }
        }
        return $this->response->sendError();
    }

    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $owner_id = $this->request->getQuery('owner_id');
        $owner_type = $this->request->getQuery('owner_type');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_by = [];
        if (!is_null($owner_id)){ $filter_by['owner_id'] = $owner_id; }
        if (!is_null($owner_type)){ $filter_by['owner_type'] = $owner_type; }

        return $this->response->sendSuccess(BankAccount::fetchAll($offset, $count, $filter_by));
    }
} 