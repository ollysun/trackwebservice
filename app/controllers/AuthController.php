<?php


class AuthController extends ControllerBase {
    public function loginAction(){
        $identifier = $this->request->getPost('identifier'); //email
        $password = $this->request->getPost('password');

        if (in_array(null, array($identifier, $password))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $officer = UserAuth::fetchByEmail($identifier);
        if ($officer != false){
            if ($this->security->checkHash($password, $officer->getPassword())){
                $officer_data = false;
                if ($officer->getEntityType() == UserAuth::ENTITY_TYPE_ADMIN){
                    $officer_data = Admin::fetchLoginData($officer->getId());
                }

                if (empty($officer_data)){
                    return $this->response->sendError();
                }

                if ($this->auth->clientTokenExists($officer_data['id'])) {
                    $this->auth->loadTokenData($officer_data['id']);
                    $token = $this->auth->getToken();
                } else {
                    $token = $this->auth->generateToken();
                }

                $officer_data['email'] = $officer->getEmail();
                $role_id = ($officer->getStatus() == Status::ACTIVE) ? $officer_data['role_id'] : Role::INACTIVE_USER;

                $this->auth->saveTokenData($officer_data['id'],[
                    Auth::L_EMAIL => $officer_data['email'],
                    Auth::L_USER_TYPE => $role_id,
                    Auth::L_TOKEN => $token,
                    Auth::L_DATA => $officer
                ]);

                return $this->response->sendSuccess($officer_data);
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);
    }

    public function changePasswordAction(){
        $password = $this->request->getPost('password');

        if ($password == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            return $this->response->sendError(ResponseMessage::PASSWORD_TOO_SMALL);
        }

        $admin = UserAuth::findFirst(array(
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

    public function resetPasswordAction(){

    }

    public function validateAction(){
        $temp = $this->request->getQuery('identifier');
        $identifier = !empty($temp) ? $temp : $this->auth->getEmail(); //email or staff id
        $password = $this->request->getQuery('password');

        if (in_array(null, array($identifier, $password))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $officer = UserAuth::fetchByEmail($identifier);
        if ($officer != false){
            if ($this->security->checkHash($password, $officer->getPassword())){
                $officer_data = false;
                if ($officer->getEntityType() == UserAuth::ENTITY_TYPE_ADMIN){
                    $officer_data = Admin::fetchLoginData($officer->getId());
                }

                if (empty($officer_data)){
                    return $this->response->sendError();
                }

                $officer_data['email'] = $officer->getEmail();
                return $this->response->sendSuccess($officer_data);
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);
    }
} 