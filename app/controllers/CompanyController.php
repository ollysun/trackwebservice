<?php


use PhalconUtils\Validation\RequestValidation;

/**
 * Class CompanyController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @author Rahman Shitu <rahman@cottacush.com>
 */
class CompanyController extends ControllerBase
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function createCompanyAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $postData = $this->request->getJsonRawBody();

        if (!isset($postData->primary_contact, $postData->company)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $companyRequestValidator = new CompanyRequestValidation($postData, 'company');
        if (!$companyRequestValidator->validate()) {
            return $this->response->sendError($companyRequestValidator->getMessages());
        }

        $this->db->begin();
        /** @var Company $company */
        if (!($company = Company::add((array)$postData->company))) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_COMPANY);
        }

        $companyContactValidator = new CompanyContactValidation($postData, 'primary_contact');
        if (!$companyContactValidator->validate()) {
            return $this->response->sendError($companyContactValidator->getMessages());
        }

        $postData->primary_contact->password = $this->auth->generateToken(6);
        //create contacts and link to company
        $primary_contact = $company->createUser(Role::COMPANY_ADMIN, (array)$postData->primary_contact);
        if (!$primary_contact) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_COMPANY_PRIMARY_CONTACT);
        }
        $company->setPrimaryContactId($primary_contact->getId());

        if (property_exists($postData, 'secondary_contact')) {
            $companyContactValidator->setNamespace('secondary_contact');
            if (!$companyContactValidator->validate()) {
                return $this->response->sendError($companyContactValidator->getMessages());
            }
            $postData->secondary_contact->password = $this->auth->generateToken(6);
            $secondary_contact = $company->createUser(Role::COMPANY_OFFICER, (array)$postData->secondary_contact);
            if (!$secondary_contact) {
                return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_COMPANY_SECONDARY_CONTACT);
            }
            $company->setSecContactId($secondary_contact->getId());
        } else {
            $company->setSecContactId(null);
        }

        if (!$company->save()) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_LINK_CONTACTS_TO_COMPANY);
        }

        $postData->primary_contact->id = $primary_contact->getId();
        $company->notifyContact((array)$postData->primary_contact);

        if (property_exists($postData, 'secondary_contact')) {
            $postData->secondary_contact->id = $secondary_contact->getId();
            $company->notifyContact((array)$postData->secondary_contact);
        }

        $this->db->commit();
        return $this->response->sendSuccess($company->toArray());
    }

    /**
     * Edit Company API
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function editCompanyAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $postData = $this->request->getJsonRawBody();

        if (!isset($postData->company)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $companyRequestValidator = new CompanyUpdateRequestValidation($postData, 'company');
        if (!$companyRequestValidator->validate()) {
            return $this->response->sendError($companyRequestValidator->getMessages());
        }

        if(Company::edit((array) $postData->company)) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::UNABLE_TO_EDIT_COMPANY);
    }

    /**
     * This fetches a paginated list of company using filter params. More info can be hydrated using certain params starting with 'with'.
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function getAllCompanyAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_params = ['status', 'name'];
        $fetch_params = ['with_city', 'with_total_count', 'no_paginate'];

        $possible_params = array_merge($filter_params, $fetch_params);

        foreach ($possible_params as $param) {
            $$param = $this->request->getQuery($param);
        }

        $filter_by = [];
        foreach ($filter_params as $param) {
            if (!is_null($$param)) {
                $filter_by[$param] = $$param;
            }
        }

        $fetch_with = [];
        foreach ($fetch_params as $param) {
            if (!is_null($$param)) {
                $fetch_with[$param] = true;
            }
        }

        $companies = Company::fetchAll($offset, $count, $filter_by, $fetch_with);
        $result = [];
        if (!empty($with_total_count)) {
            $count = Company::getTotalCount($filter_by);
            $result = [
                'total_count' => $count,
                'companies' => $companies
            ];
        } else {
            $result = $companies;
        }

        return $this->response->sendSuccess($result);
    }

    /**
     * Fetches the details of a company. More info can be hydrated using certain params starting with 'with'.
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function getCompanyInfoAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $filter_params = ['id'];
        $fetch_params = ['with_city', 'with_primary_contact', 'with_secondary_contact', 'with_relations_officer'];

        $possible_params = array_merge($filter_params, $fetch_params);

        foreach ($possible_params as $param) {
            $$param = $this->request->getQuery($param);
        }

        $filter_by = [];
        foreach ($filter_params as $param) {
            if (!is_null($$param)) {
                $filter_by[$param] = $$param;
            }
        }

        $fetch_with = [];
        foreach ($fetch_params as $param) {
            if (!is_null($$param)) {
                $fetch_with[$param] = true;
            }
        }

        $company = Company::fetchOne($filter_params, $fetch_with);
        if ($company != false) {
            return $this->response->sendSuccess($company);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    /**
     * Gets the total company count based on possible filters.
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function getCompanyCountAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $filter_params = ['status', 'name'];

        $filter_by = [];
        foreach ($filter_params as $param) {
            $$param = $this->request->getQuery($param);
            if (!is_null($$param)) {
                $filter_by[$param] = $$param;
            }
        }

        $count = Company::getTotalCount($filter_by);
        if ($count === null) {
            return $this->response->sendError();
        }
        return $this->response->sendSuccess($count);
    }

    /**
     * create a company officer or admin
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function createUserAction()
    {
        $this->auth->allowOnly([Role::COMPANY_ADMIN]);

        $postData = $this->request->getJsonRawBody(true);
        $requiredFields = ['firstname', 'lastname', 'phone_number', 'email', 'company_id', 'role_id'];

        $requestValidator = new RequestValidation($postData);
        $requestValidator->setRequiredFields($requiredFields);
        if (!$requestValidator->validate()) {
            return $this->response->sendError($requestValidator->getMessages());
        }

        $company = Company::findFirst($postData['company_id']);
        if (!$company) {
            return $this->response->sendError('Invalid company id');
        }

        if (UserAuth::findFirstByEmail($postData['email'])) {
            return $this->response->sendError('User already exists');
        }

        if (!in_array($postData['role_id'], [Role::COMPANY_ADMIN, Role::COMPANY_OFFICER])) {
            return $this->response->sendError('Invalid company user role');
        }

        $postData['password'] = $this->auth->generateToken(6);
        $user = $company->createUser($postData['role_id'], $postData);

        if (!$user) {
            return $this->response->sendError('Could not create user');
        }

        $postData['id'] = $user->getId();
        $company->notifyContact($postData);

        $userData = $user->toArray();
        return $this->response->sendSuccess($userData);
    }

    /**
     * Edit Company User API
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function editUserAction()
    {
        $this->auth->allowOnly([Role::COMPANY_ADMIN, Role::ADMIN]);
        $postData = $this->request->getJsonRawBody();

        if (!isset($postData->company_user)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $companyContactValidator = new CompanyContactUpdateValidation($postData, 'company_user');
        if (!$companyContactValidator->validate()) {
            return $this->response->sendError($companyContactValidator->getMessages());
        }
        $this->db->begin();

        if(CompanyUser::updateUser($postData->company_user) && UserAuth::updateEmailAndStatus($postData->company_user->user_auth_id, $postData->company_user->email, $postData->company_user->status)) {
            $this->db->commit();
            return $this->response->sendSuccess();
        }

        $this->db->rollback();

        return $this->response->sendError(ResponseMessage::UNABLE_TO_UPDATE_COMPANY_USER);
    }

    /**
     * Get company user
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return $this
     */
    public function getUserAction()
    {
        $filter_params = ['id', 'email'];
        $fetch_params = ['with_company'];

        $possible_params = array_merge($filter_params, $fetch_params);

        foreach ($possible_params as $param) {
            $$param = $this->request->getQuery($param);
        }

        $filter_by = [];
        foreach ($filter_params as $param) {
            if (!is_null($$param)) {
                $filter_by[$param] = $$param;
            }
        }

        $fetch_with = [];
        foreach ($fetch_params as $param) {
            if (!is_null($$param)) {
                $fetch_with[$param] = true;
            }
        }

        $company = CompanyUser::fetchOne($filter_params, $fetch_with);
        if ($company != false) {
            return $this->response->sendSuccess($company);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    /**
     * Get all company users
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function getAllUsersAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_params = ['company_id', 'role_id', 'email'];
        $fetch_params = ['with_company', 'with_total_count'];

        $possible_params = array_merge($filter_params, $fetch_params);

        foreach ($possible_params as $param) {
            $$param = $this->request->getQuery($param);
        }

        $filter_by = [];
        foreach ($filter_params as $param) {
            if (!is_null($$param)) {
                $filter_by[$param] = $$param;
            }
        }

        $fetch_with = [];
        foreach ($fetch_params as $param) {
            if (!is_null($$param)) {
                $fetch_with[$param] = true;
            }
        }

        $users = CompanyUser::fetchAll($offset, $count, $filter_by, $fetch_with);
        if (!empty($with_total_count)) {
            $count = CompanyUser::getTotalCount($filter_by);
            $result = [
                'total_count' => $count,
                'users' => $users
            ];
        } else {
            $result = $users;
        }

        return $this->response->sendSuccess($result);
    }

    /**
     * make a corporate shipment request
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function makeShipmentRequestAction()
    {
        $this->auth->allowOnly([Role::COMPANY_ADMIN, Role::COMPANY_OFFICER]);

        $postData = $this->request->getJsonRawBody();
        $postData->created_by = $this->auth->getPersonId();
        $requestValidator = new ShipmentRequestValidation($postData);

        if (!$requestValidator->validate()) {
            return $this->response->sendError($requestValidator->getMessages());
        }

        $postData->status = ShipmentRequest::STATUS_PENDING;
        if (($request = ShipmentRequest::add($postData))) {
            return $this->response->sendSuccess($request);
        } else {
            return $this->response->sendError(ResponseMessage::COULD_NOT_CREATE_REQUEST);
        }
    }

    /**
     * make a corporate bulk shipment request
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function makeBulkShipmentRequestAction()
    {
        $this->auth->allowOnly([Role::COMPANY_ADMIN, Role::COMPANY_OFFICER]);
        $postData = $this->request->getJsonRawBody();

        if (!is_array($postData) || !$postData) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $rowValidator = new ShipmentRequestValidation();

        $count = 0;
        foreach ($postData as $row) {
            $count++;
            $row->created_by = $this->auth->getPersonId();
            $rowValidator->setData($row);
            if (!$rowValidator->validate()) {
                return $this->response->sendError($rowValidator->getMessages() . ' in shipment request ' . $count);
            }
        }

        try {
            ShipmentRequest::addBulkRequests($postData);
            return $this->response->sendSuccess();
        } catch (Exception $ex) {
            return $this->response->sendError($ex->getMessage());
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return $this
     */
    public function getRequestsAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $with_total_count = $this->request->getQuery('with_total_count', null, null);
        $request_type = $this->request->getQuery('type', null, 'shipment');
        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());
        $filter_by = $this->request->getQuery();

        if (!in_array(strtolower($request_type), ['shipment', 'pickup'])) {
            return $this->response->sendError('Invalid request type');
        }

        $requests = ($request_type == 'shipment') ? ShipmentRequest::getRequests($offset, $count, $fetch_with, $filter_by) : PickupRequest::getRequests($offset, $count, $fetch_with, $filter_by);

        if (!empty($with_total_count)) {
            $data = [
                'total_count' => ($request_type == 'shipment') ? ShipmentRequest::getTotalCount($filter_by) : PickupRequest::getTotalCount($filter_by),
                'requests' => $requests
            ];
        } else {
            $data = $requests;
        }

        return $this->response->sendSuccess($data);
    }

    /**
     * make a corporate pickup request
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function makePickupRequestAction()
    {
        $this->auth->allowOnly([Role::COMPANY_ADMIN, Role::COMPANY_OFFICER]);

        $postData = $this->request->getJsonRawBody();
        $postData->created_by = $this->auth->getPersonId();
        $pickupRequestValidation = new PickupRequestValidation($postData);

        if (!$pickupRequestValidation->validate()) {
            return $this->response->sendError($pickupRequestValidation->getMessages());
        }

        $contactValidator = new PickupContactValidation($postData, 'pickup');
        if (!$contactValidator->validate()) {
            return $this->response->sendError($contactValidator->getMessages());
        }

        $contactValidator->setNamespace('destination');
        if (!$contactValidator->validate()) {
            return $this->response->sendError($contactValidator->getMessages());
        }

        $postData->status = PickupRequest::STATUS_PENDING;

        if (($request = PickupRequest::add($postData))) {
            return $this->response->sendSuccess($request->toArray());
        } else {
            return $this->response->sendError(ResponseMessage::COULD_NOT_CREATE_REQUEST);
        }
    }

    /**
     * Gets the detail of a shipment request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function getShipmentRequestAction()
    {
        $request_id = $this->request->getQuery('request_id', null);
        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());

        if (is_null($request_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $request = ShipmentRequest::getOne($request_id, $fetch_with);

        return $this->response->sendSuccess($request);
    }

    /**
     * Get the details of a pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function getPickupRequestAction()
    {
        $request_id = $this->request->getQuery('request_id', null);
        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());

        if (is_null($request_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $request = null;
        $request = PickupRequest::getOne($request_id, $fetch_with);

        return $this->response->sendSuccess($request);
    }

    /**
     * Cancels a pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function cancelPickupRequestAction()
    {
        $postData = $this->request->getJsonRawBody();

        if (!property_exists($postData, 'request_id')) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        /**
         * @var PickupRequest $pickupRequest
         */
        $pickupRequest = PickupRequest::findFirst($postData->request_id);

        if(!$pickupRequest) {
            return $this->response->sendError(ResponseMessage::RECORD_DOES_NOT_EXIST);
        }

        if($pickupRequest->cancelRequest()) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError(ResponseMessage::UNABLE_TO_CANCEL_REQUEST);
    }

    /**
     * Declines a pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function declinePickupRequestAction()
    {
        $postData = $this->request->getJsonRawBody();

        if (!property_exists($postData, 'request_id') || !property_exists($postData, 'comment')) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        /**
         * @var PickupRequest $pickupRequest
         */
        $pickupRequest = PickupRequest::findFirst($postData->request_id);

        if(!$pickupRequest) {
            return $this->response->sendError(ResponseMessage::RECORD_DOES_NOT_EXIST);
        }

        $this->db->begin();

        if($pickupRequest->declineRequest() && PickupRequestComment::addDeclineComment($postData->request_id, $postData->comment)) {
            $this->db->commit();
            return $this->response->sendSuccess();
        }

        $this->db->rollback();

        return $this->response->sendError(ResponseMessage::UNABLE_TO_DECLINE_REQUEST);
    }

    /**
     * Cancels a pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function cancelShipmentRequestAction()
    {
        $postData = $this->request->getJsonRawBody();

        if (!property_exists($postData, 'request_id')) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        /**
         * @var ShipmentRequest $shipmentRequest
         */
        $shipmentRequest = ShipmentRequest::findFirst($postData->request_id);

        if(!$shipmentRequest) {
            return $this->response->sendError(ResponseMessage::RECORD_DOES_NOT_EXIST);
        }

        if($shipmentRequest->cancelRequest()) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError(ResponseMessage::UNABLE_TO_CANCEL_REQUEST);
    }

    /**
     * Declines a pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function declineShipmentRequestAction()
    {
        $postData = $this->request->getJsonRawBody();

        if (!property_exists($postData, 'request_id') || !property_exists($postData, 'comment')) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        /**
         * @var ShipmentRequest $shipmentRequest
         */
        $shipmentRequest = ShipmentRequest::findFirst($postData->request_id);

        if(!$shipmentRequest) {
            return $this->response->sendError(ResponseMessage::RECORD_DOES_NOT_EXIST);
        }

        $this->db->begin();

        if($shipmentRequest->declineRequest() && ShipmentRequestComment::addDeclineComment($postData->request_id, $postData->comment)) {
            $this->db->commit();
            return $this->response->sendSuccess();
        }

        $this->db->rollback();

        return $this->response->sendError(ResponseMessage::UNABLE_TO_DECLINE_REQUEST);
    }

    /**
     * Gets the details of a company
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getCompanyAction()
    {
        $company_id = $this->request->getQuery('company_id', null);

        if (is_null($company_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());
        $filter_by = [
            'id' => $company_id
        ];

        $company = Company::fetchOne($filter_by, $fetch_with);

        if ($company != false) {
            return $this->response->sendSuccess(Company::fetchOne($filter_by, $fetch_with));
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }
}


