<?php

/**
 * Class AuthController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @author Rahman Shitu <rahman@cottacush.com>
 */
class AuthController extends ControllerBase
{

    /** @author Ademu Anthony E */
    private function login($identifier, $password, $company_id = null){
        /** @var UserAuth $authUser */
        $authUser = UserAuth::getByIdentifier($identifier);
        if ($authUser != false) {
            if ($this->security->checkHash($password, $authUser->getPassword())) {
                $userData = false;
                if ($authUser->getEntityType() == UserAuth::ENTITY_TYPE_ADMIN) {
                    $userData = Admin::fetchLoginData($authUser->getId());
                } else if ($authUser->getEntityType() == UserAuth::ENTITY_TYPE_CORPORATE) {
                    $userData = Company::fetchLoginData($authUser->getId());
                }

                if (empty($userData)) {
                    return $this->response->sendError('Could not fetch user login data');
                }

                if ($this->auth->clientTokenExists($authUser->getId())) {
                    $this->auth->loadTokenData($authUser->getId());
                    $token = $this->auth->getToken();
                } else {
                    $token = $this->auth->generateToken();
                }

                $userData['email'] = $authUser->getEmail();
                $userData['status'] = $authUser->getStatus();
                $userData['last_login_time'] = $authUser->getLastLoginTime();
                $role_id = ($authUser->getStatus() == Status::ACTIVE) ? $userData['role_id'] : Role::INACTIVE_USER;

                $this->auth->saveTokenData($authUser->getId(), [
                    Auth::L_EMAIL => $authUser->getEmail(),
                    Auth::L_USER_TYPE => $role_id,
                    Auth::L_TOKEN => $token,
                    Auth::L_DATA => $userData,
                    Auth::L_OPERATIONS => ResourcePermission::getOperationsForUser($authUser->getId())
                ]);

                $authUser->setLastLoginTime(time());
                $authUser->save();

                if ($company_id){
                    $userData['company_id'] = $company_id;
                }

                return $this->response->sendSuccess($userData);
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function loginAction()
    {
        if($this->request->getPost('api_login') == 1){
            return $this->apiLogin();
        }

        $identifier = $this->request->getPost('identifier'); //email
        $password = $this->request->getPost('password');

        if (in_array(null, array($identifier, $password))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        return $this->login($identifier, $password);

        /** @var UserAuth $authUser */
       /* $authUser = UserAuth::getByIdentifier($identifier);
        if ($authUser != false) {
            if ($this->security->checkHash($password, $authUser->getPassword())) {
                $userData = false;
                if ($authUser->getEntityType() == UserAuth::ENTITY_TYPE_ADMIN) {
                    $userData = Admin::fetchLoginData($authUser->getId());
                } else if ($authUser->getEntityType() == UserAuth::ENTITY_TYPE_CORPORATE) {
                    $userData = Company::fetchLoginData($authUser->getId());
                }

                if (empty($userData)) {
                    return $this->response->sendError('Could not fetch user login data');
                }

                if ($this->auth->clientTokenExists($authUser->getId())) {
                    $this->auth->loadTokenData($authUser->getId());
                    $token = $this->auth->getToken();
                } else {
                    $token = $this->auth->generateToken();
                }

                $userData['email'] = $authUser->getEmail();
                $userData['status'] = $authUser->getStatus();
                $userData['last_login_time'] = $authUser->getLastLoginTime();
                $role_id = ($authUser->getStatus() == Status::ACTIVE) ? $userData['role_id'] : Role::INACTIVE_USER;

                $this->auth->saveTokenData($authUser->getId(), [
                    Auth::L_EMAIL => $authUser->getEmail(),
                    Auth::L_USER_TYPE => $role_id,
                    Auth::L_TOKEN => $token,
                    Auth::L_DATA => $userData
                ]);

                $authUser->setLastLoginTime(time());
                $authUser->save();

                return $this->response->sendSuccess($userData);
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);*/
    }

    public function apiLogin(){
        $token = $this->request->getPost('private_key');
        $registration_number = $this->request->getPost('registration_number');

        $apiToken = CompanyAccess::findFirst([
           'conditions' => 'registration_number = :registration_number: AND token = :token:',
            'bind' => ['registration_number' => $registration_number, 'token' => $token]
        ]);

        if(!$apiToken){
            return $this->response->sendError('Invalid API credentials');
        }

        $company = Company::getByRegistrationNumber($registration_number);

        return $this->login($apiToken->getAuthUsername(), CompanyAccess::PASSWORD, $company->getId());

    }

    public function changePasswordAction()
    {
        $password = $this->request->getPost('password');

        if ($password == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            return $this->response->sendError(ResponseMessage::PASSWORD_TOO_SMALL);
        }

        $admin = UserAuth::findFirst(array(
            "id = :id: AND status IN (" . Status::ACTIVE . "," . Status::INACTIVE . ")",
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

    /**
     * Forgot password action
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function forgotPasswordAction()
    {
        $email = $this->request->getPost('identifier');

        if ($email == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $admin = UserAuth::findFirst([
            'email = :email:',
            'bind' => ['email' => $email]
        ]);

        if ($admin) {
            // Send password reset email
            EmailMessage::send(
                EmailMessage::RESET_PASSWORD,
                [
                    'name' => '',
                    'email' => $email,
                    'link' => $this->config->fe_base_url . '/site/resetpassword?token=' . md5($admin->getId()) . "&_key_=" . $admin->getId(),
                    'year' => date('Y')
                ],
                'Courier Plus',
                $email
            );
            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::ACCOUNT_DOES_NOT_EXIST);
    }

    /**
     * Reset password action
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function resetPasswordAction()
    {
        if($this->request->getPost('company_id') != null){
            $company_id = $this->request->getPost('company_id');
            $password = $this->request->getPost('password');

            if (in_array(null, [$company_id, $password])) {
                return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
            }

            $company = Company::findFirst($company_id);

            $users = $company->getUserAuth();
            $successful_emails = [];
            foreach ($users as $admin) {
                if($admin->getEmail() == 'noemailaddress@thiscorporate.com'){
                    return $this->response->sendError(ResponseMessage::COMPANY_USER_HAS_NO_EMAIL);
                }
                /** @var UserAuth */
                $admin->changePassword($password);

                if ($admin->save()) {
                    $successful_emails[] = $admin->getEmail();
                } else {
                    return $this->response->sendError(ResponseMessage::UNABLE_TO_RESET_PASSWORD);
                }
            }

            $message = "Password reset for ".implode(', ', $successful_emails);
            return $this->response->sendSuccess($message);

        }

        $userAuthId = $this->request->getPost('user_auth_id');
        $password = $this->request->getPost('password');

        if (in_array(null, [$userAuthId, $password])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $admin = UserAuth::findFirst($userAuthId);

        if ($admin) {
            $admin->changePassword($password);

            if ($admin->save()) {
                return $this->response->sendSuccess();
            } else {
                return $this->response->sendError(ResponseMessage::UNABLE_TO_RESET_PASSWORD);
            }
        }
        return $this->response->sendError(ResponseMessage::ACCOUNT_DOES_NOT_EXIST);
    }

    /**
     * Reset password action
     * @author Ademu Anthony <ademuanthony@gmail.com>
     */
    public function resetCompanyAdminPasswordAction(){
        $this->auth->allowOnly([Role::ADMIN]);
        $company_id = $this->request->getPost('company_id');
        $password = $this->request->getPost('password');

        if (in_array(null, [$company_id, $password])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $company = Company::findFirst($company_id);

        $users = $company->getUserAuth();
        $successful_emails = [];
        foreach ($users as $admin) {
            /** @var UserAuth */
            $admin->changePassword($password);

            if ($admin->save()) {
                $successful_emails[] = $admin->getEmail();
            } else {
                return $this->response->sendError(ResponseMessage::UNABLE_TO_RESET_PASSWORD);
            }
        }

        $message = "Password reset for ".implode(', ', $successful_emails);
        return $this->response->sendSuccess($message);

    }

    /**
     * Validates password reset token
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function validatePasswordResetTokenAction()
    {
        $token = $this->request->getPost('token');
        $key = $this->request->getPost('key');

        if (in_array(null, [$token, $key])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if ($token == md5($key)) {
            $admin = UserAuth::findFirst($key);
            if ($admin) {
                return $this->response->sendSuccess();
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_TOKEN);
    }

    public function validateAction()
    {
        $temp = $this->request->getQuery('identifier');
        $identifier = !empty($temp) ? $temp : $this->auth->getEmail(); //email or staff id
        $password = $this->request->getQuery('password');

        if (in_array(null, array($identifier, $password))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $officer = UserAuth::fetchByEmail($identifier);
        if ($officer != false) {
            if ($this->security->checkHash($password, $officer->getPassword())) {
                $officer_data = false;
                if ($officer->getEntityType() == UserAuth::ENTITY_TYPE_ADMIN) {
                    $officer_data = Admin::fetchLoginData($officer->getId());
                } else if ($officer->getEntityType() == UserAuth::ENTITY_TYPE_CORPORATE) {
                    $officer_data = CompanyUser::fetchLoginData($officer->getId());
                }

                if (empty($officer_data)) {
                    return $this->response->sendError();
                }

                $officer_data['email'] = $officer->getEmail();
                return $this->response->sendSuccess($officer_data);
            }
        }
        return $this->response->sendError(ResponseMessage::INVALID_CRED);
    }

    public function changeStatusAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $status = $this->request->getPost('status');

        if ($status == null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $auth = UserAuth::findFirst(array(
            "id = :id: AND status IN (" . Status::ACTIVE . "," . Status::INACTIVE . ")",
            'bind' => array('id' => $this->auth->getClientId())
        ));
        if ($auth != false) {
            $auth->changeStatus($status);
            if ($auth->save()) {
                return $this->response->sendSuccess('');
            }
        }
        return $this->response->sendError();
    }
} 