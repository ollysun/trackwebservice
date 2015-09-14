<?php


class AdminController extends ControllerBase {
    public function registerAction(){
        $this->auth->allowOnly([Role::ADMIN]);
        //todo: validate phone number

        $role_id = $this->request->getPost('role_id');
        $branch_id = $this->request->getPost('branch_id');
        $staff_id = $this->request->getPost('staff_id');
        $email = $this->request->getPost('email');
        $fullname = $this->request->getPost('fullname');
        $phone = $this->request->getPost('phone');
        $password = $this->auth->generateToken(6);

        if (in_array(null, array($email, $role_id, $staff_id, $fullname, $password))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $email = strtolower(trim($email));
        $staff_id = strtoupper(trim($staff_id));
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            return $this->response->sendError(ResponseMessage::INVALID_EMAIL);
        }

        $branch = Branch::fetchById($branch_id);
        if ($branch == false){
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        if ($role_id == Role::SWEEPER){
            if ($branch->getBranchType() != BranchType::HUB){
                return $this->response->sendError(ResponseMessage::SWEEPER_ONLY_TO_HUB);
            }
        }

        $admin = Admin::fetchByIdentifier($email, $staff_id);
        if ($admin != false){
            if ($admin->getEmail() == $email){
                return $this->response->sendError(ResponseMessage::EXISTING_EMAIL);
            }else if ($admin->getStaffId() == $staff_id){
                return $this->response->sendError(ResponseMessage::EXISTING_STAFF_ID);
            }
        }

        $admin = new Admin();
        $admin->initData($branch_id, $role_id, $staff_id, $email, $password, $fullname, $phone);
        if ($admin->save()){
            EmailMessage::send(
                EmailMessage::USER_ACCOUNT_CREATION,
                [
                    'name' => $fullname,
                    'email' => $email,
                    'password' => $password,
                    'link' => $this->config->fe_base_url.'/site/changePassword?ican='.md5($admin->getId()).'&salt='.$admin->getId(),
                    'year'=> date('Y')
                ],
                'Courier Plus',
                $email
            );
            return $this->response->sendSuccess(['id' => $admin->getId()]);
        }
        return $this->response->sendError();
    }

    public function loginAction(){
        $identifier = $this->request->getPost('identifier'); //email or staff id
        $password = $this->request->getPost('password');

        if (in_array(null, array($identifier, $password))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $admin = Admin::fetchLoginData($identifier);
        if ($admin != false){
            if ($this->security->checkHash($password, $admin['password'])){
                if ($this->auth->clientTokenExists($admin['id'])) {
                    $this->auth->loadTokenData($admin['id']);
                    $token = $this->auth->getToken();
                } else {
                    $token = $this->auth->generateToken();
                }

                unset($admin['password']);
                $role_id = ($admin['status'] == Status::ACTIVE) ? $admin['role_id'] : Role::INACTIVE_USER;

                $this->auth->saveTokenData($admin['id'],[
                    Auth::L_EMAIL => $admin['email'],
                    Auth::L_USER_TYPE => $role_id,
                    Auth::L_TOKEN => $token,
                    Auth::L_DATA => $admin
                ]);

                return $this->response->sendSuccess($admin);
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);
    }

    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);


        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $role_id = $this->request->getQuery('role_id');
        $branch_id = $this->request->getQuery('branch_id');
        $status = $this->request->getQuery('status');
        $staff_id = $this->request->getQuery('staff_id');
        $email = $this->request->getQuery('email');

        $filter_by = [];
        if (!is_null($role_id)){ $filter_by['role_id'] = $role_id; }
        if (!is_null($branch_id)){ $filter_by['branch_id'] = $branch_id; }
        if (!is_null($status)){ $filter_by['status'] = $status; }
        if (!is_null($staff_id)){ $filter_by['staff_id'] = $staff_id; }
        if (!is_null($email)){ $filter_by['email'] = $email; }

        return $this->response->sendSuccess(Admin::fetchAll($offset, $count, $filter_by));
    }

    public function getOneAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $staff_id = $this->request->getQuery('staff_id');
        $email = $this->request->getQuery('email');
        $id = $this->request->getQuery('id');

        $filter_by = [];
        if (!is_null($staff_id)){ $filter_by['staff_id'] = $staff_id; }
        else if (!is_null($email)){ $filter_by['email'] = $email; }
        else if (!is_null($id)){ $filter_by['id'] = $id; }

        $admin_data = Admin::fetchOne($filter_by);
        if ($admin_data != false){
            return $this->response->sendSuccess($admin_data);
        }
        return $this->response->sendError(ResponseMessage::RECORD_DOES_NOT_EXIST);
    }

    public function changePasswordAction(){
        $password = $this->request->getPost('password');

        if ($password == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            return $this->response->sendError(ResponseMessage::PASSWORD_TOO_SMALL);
        }

        $admin = Admin::findFirst(array(
            "id = :id: AND status IN (". Status::ACTIVE . "," . Status::INACTIVE . ")",
            'bind' => array('id' => $this->auth->getClientId())
        ));
        if ($admin != false) {
            $admin->changePassword($password);
            if ($admin->save()) {
                return $this->response->sendSuccess('');
            }
        }
        return $this->response->sendError();
    }

    public function changeStatusAction(){
        $status = $this->request->getPost('status');

        if ($status == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $admin = Admin::findFirst(array(
            "id = :id: AND status IN (". Status::ACTIVE . "," . Status::INACTIVE . ")",
            'bind' => array('id' => $this->auth->getClientId())
        ));
        if ($admin != false) {
            $admin->changeStatus($status);
            if ($admin->save()) {
                return $this->response->sendSuccess('');
            }
        }
        return $this->response->sendError();
    }

    public function resetPasswordAction(){

    }

    public function validateAction(){
        $temp = $this->request->getQuery('identifier');
        $identifier = !empty($temp) ? $temp : $this->auth->getEmail(); //email or staff id
        $password = $this->request->getQuery('password');

        if (in_array(null, array($identifier, $password))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $admin = Admin::fetchLoginData($identifier);
        if ($admin != false){
            if ($this->security->checkHash($password, $admin['password'])){
                unset($admin['password']);
               return $this->response->sendSuccess($admin);
           }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);
    }

}