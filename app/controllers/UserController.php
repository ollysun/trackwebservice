<?php


class UserController extends ControllerBase {

    public function getByPhoneAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $phone = $this->request->getQuery('phone');
        $fetch_parcel = $this->request->getQuery('fetch_parcel');
        $order_parcel_by_modified = $this->request->getQuery('order_parcel_by_modified'); //works with fetch_parcel

        if (is_null($phone)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $user = User::fetchByPhone($phone);
        if ($user != false){
            $address = Address::fetchDefault($user->getId(), OWNER_TYPE_CUSTOMER);

            $result = $user->getData();
            $result['address'] = ($address == false) ? null : $address->getData();

            if (!is_null($fetch_parcel)){
                $result['parcel'] = Parcel::fetchAll(
                    DEFAULT_OFFSET,
                    DEFAULT_COUNT,
                    array('user_id' => $user->getId()),
                    array('with_sender'=>true, 'with_receiver'=>true),
                    (!is_null($order_parcel_by_modified)) ? 'Parcel.modified_date DESC' : 'Parcel.created_date DESC'
                );
            }
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