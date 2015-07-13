<?php


class UserController extends ControllerBase {
    public function getByPhoneAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $phone = $this->request->getQuery('phone');

        if (is_null($phone)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $user = User::fetchByPhone($phone);
        if ($user != false){
            $address = Address::fetchDefault($user->getId(), OWNER_TYPE_CUSTOMER);

            $result = $user->getData();
            $result['address'] = ($address == false) ? null : $address->getData();
            return $this->response->sendSuccess($result);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    private function getByEmailAction(){
        //todo: to be recoded then made public
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $email = $this->request->getQuery('email');

        if (is_null($email)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $user = User::fetchByEmail($email);
        if ($user != false){
            return $this->response->sendSuccess($user->getData());
        }
        return $this->response->sendError();
    }
} 