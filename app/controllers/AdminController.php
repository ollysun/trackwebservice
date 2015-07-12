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
        $password = '123456'; //auto-generated

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

                $this->auth->saveTokenData($admin['id'],[
                    Auth::L_EMAIL => $admin['email'],
                    Auth::L_USER_TYPE => $admin['role_id'],
                    Auth::L_TOKEN => $token,
                    Auth::L_DATA => $admin
                ]);

                return $this->response->sendSuccess($admin);
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);
    }

    public function changePasswordAction(){

    }

    public function changeStatusAction(){

    }

    public function resetPasswordAction(){

    }
} 