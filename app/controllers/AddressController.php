<?php


class AddressController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $owner_id = $this->request->getPost('owner_id');
        $owner_type = $this->request->getPost('owner_type');
        $street_address1 = $this->request->getPost('street_address1');
        $street_address2 = $this->request->getPost('street_address2');
        $state_id = $this->request->getPost('state_id');
        $country_id = $this->request->getPost('country_id');
        $city = $this->request->getPost('city');

        if (in_array(null, array($owner_id, $owner_type, $street_address1, $street_address2, $state_id, $country_id, $city))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($owner_type, array(OWNER_TYPE_COMPANY, OWNER_TYPE_CUSTOMER))){
            return $this->response->sendError();
        }

        $address = new Address();
        $address->initData($owner_id, $owner_type, $street_address1, $street_address2, $state_id, $country_id, $city);
        if ($address->save()){
            return $this->response->sendSuccess(['id' => $address->getId()]);
        }
        return $this->response->sendError();
    }

    public function editAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $address_id = $this->request->getPost('address_id');
        $owner_id = $this->request->getPost('owner_id');
        $owner_type = $this->request->getPost('owner_type');

        $street_address1 = $this->request->getPost('street_address1');
        $street_address2 = $this->request->getPost('street_address2');
        $state_id = $this->request->getPost('state_id');
        $country_id = $this->request->getPost('country_id');
        $city = $this->request->getPost('city');

        if (in_array(null, array($address_id, $owner_id, $owner_type, $street_address1, $street_address2, $state_id, $country_id, $city))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $address = Address::fetchActive($address_id, $owner_id, $owner_type);
        if ($address != false){
            $address->edit($street_address1, $street_address2, $state_id, $country_id, $city);
            if ($address->save()){
                return $this->response->sendSuccess();
            }
        }
        return $this->response->sendError();
    }

    public function removeAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $address_id = $this->request->getPost('address_id');
        $owner_id = $this->request->getPost('owner_id');
        $owner_type = $this->request->getPost('owner_type');

        if (in_array(null, array($address_id, $owner_id, $owner_type))){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $address = Address::fetchActive($address_id, $owner_id, $owner_type);
        if ($address != false){
            $address->changeStatus(Status::REMOVED);
            if ($address->save()){
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

        return $this->response->sendSuccess(Address::fetchAll($offset, $count, $filter_by));
    }

    public function setDefaultAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $address_id = $this->request->getPost('address_id');

        if ($address_id === null){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $address = Address::fetchActive($address_id);
        if ($address != false){
            $address->setAsDefault();
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }
} 