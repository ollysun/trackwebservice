<?php

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
        $data = $this->request->getJsonRawBody(true);

        if (!isset($data['primary_contact'], $data['company'])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $company_data = $data['company'];
        $primary_contact_data = $data['primary_contact'];

        $companyRequestValidator = new RequestValidator($company_data, ['name', 'reg_no', 'email', 'phone_number', 'address', 'relations_officer_id', 'city_id']);
        if (!$companyRequestValidator->validateFields()) {
            return $this->response->sendError($companyRequestValidator->printValidationMessage());
        }

        $contactRequiredFields = ['firstname', 'lastname', 'phone_number', 'email'];
        $contactRequestValidator = new RequestValidator($primary_contact_data, $contactRequiredFields);
        if (!$contactRequestValidator->validateFields()) {
            return $this->response->sendError($contactRequestValidator->printValidationMessage());
        }

        if (isset($data['secondary_contact'])) {
            $contactRequestValidator->setParameters($data['secondary_contact']);
            if (!$contactRequestValidator->validateFields()) {
                return $this->response->sendError($contactRequestValidator->printValidationMessage());
            }
        }

        if (!City::findFirst($company_data['city_id'])) {
            return $this->response->sendError(ResponseMessage::INVALID_CITY_SUPPLIED);
        }

        $company = Company::getByName($company_data['name']);
        if ($company) {
            return $this->response->sendError(ResponseMessage::COMPANY_EXISTING);
        }

        $relations_officer = Admin::findFirst($company_data['relations_officer_id']);
        if (!$relations_officer) {
            return $this->response->sendError(ResponseMessage::INVALID_RELATIONS_OFFICER_ID);
        }

        $primary_contact_data['password'] = $this->auth->generateToken(6);
        if (isset($data['secondary_contact'])) {
            $data['secondary_contact']['password'] = $this->auth->generateToken(6);
        }


        $this->db->begin();

        /** @var Company $company */
        if (!($company = Company::add($company_data))) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_COMPANY);
        }

        if (UserAuth::findFirstByEmail($primary_contact_data['email'])) {
            return $this->response->sendError(ResponseMessage::PRIMARY_CONTACT_EXISTS);
        }
        //create contacts and link to company
        $primary_contact = $company->createUser(Role::COMPANY_ADMIN, $primary_contact_data);
        if (!$primary_contact) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_COMPANY_PRIMARY_CONTACT);
        }
        $company->setPrimaryContactId($primary_contact->getId());


        if (isset($data['secondary_contact'])) {
            if (UserAuth::findFirstByEmail($data['secondary_contact']['email'])) {
                return $this->response->sendError(ResponseMessage::SECONDARY_CONTACT_EXISTS);
            }
            $secondary_contact = $company->createUser(Role::COMPANY_OFFICER, $data['secondary_contact']);
            if (!$secondary_contact) {
                return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_COMPANY_SECONDARY_CONTACT);
            }
            $company->setSecContactId($secondary_contact->getId());
        } else {
            $secondary_contact = null;
            $company->setSecContactId(null);
        }

        if (!$company->save()) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_LINK_CONTACTS_TO_COMPANY);
        }


        $primary_contact_data['id'] = $primary_contact->getId();
        $company->notifyContact($primary_contact_data);

        if (isset($data['secondary_contact'])) {
            $data['secondary_contact']['id'] = $secondary_contact->getId();
            $company->notifyContact($data['secondary_contact']);
        }

        $this->db->commit();

        return $this->response->sendSuccess($company->toArray());
    }

    /**
     * This fetches a paginated list of company using filter params. More info can be hydrated using certain params starting with 'with'.
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function getAllCompanyAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_params = ['status', 'name'];
        $fetch_params = ['with_city', 'with_total_count'];

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

        $requestValidator = new RequestValidator($postData, $requiredFields);
        if (!$requestValidator->validateFields()) {
            return $this->response->sendError($requestValidator->printValidationMessage());
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
     * make a corporate request
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function makeShipmentRequestAction()
    {
        $this->auth->allowOnly([Role::COMPANY_ADMIN, Role::COMPANY_OFFICER]);

        $postData = $this->request->getJsonRawBody();
        $required_fields = ['receiver_firstname',
            'receiver_address', 'company_id',
            'receiver_state_id', 'receiver_city_id',
            'estimated_weight', 'no_of_packages',
            'shipment_value', 'shipping_cost', 'created_by'];

        $requestValidator = new RequestValidator($postData, $required_fields);
        if (!$requestValidator->validateFields()) {
            return $this->response->sendError($requestValidator->printValidationMessage());
        }

        $company = Company::findFirst($postData->company_id);
        if (!$company) {
            return $this->response->sendError(ResponseMessage::INVALID_COMPANY_ID_SUPPLIED);
        }

        $company_user = CompanyUser::findFirst(['conditions' => 'id = :id: AND company_id = :company_id:', 'bind' =>
            ['id' => $postData->created_by, 'company_id' => $company->getId()]]);
        if (!$company_user) {
            return $this->response->sendError('Invalid company user');
        }

        $receiver_city = City::findFirst($postData->receiver_city_id);
        if (!$receiver_city) {
            return $this->response->sendError(ResponseMessage::INVALID_RECEIVER_CITY_SUPPLIED);
        }

        $receiver_state = State::findFirst($postData->receiever_state_id);
        if (!$receiver_state) {
            return $this->response->sendError(ResponseMessage::INVALID_RECEIVER_STATE_SUPPLIED);
        }

        $postData->status = CorporateShipmentRequest::STATUS_PENDING;

        if (($request = CorporateShipmentRequest::add($postData))) {
            return $this->response->sendSuccess($request);
        } else {
            return $this->response->sendError(ResponseMessage::COULD_NOT_CREATE_REQUEST);
        }

    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return $this
     */
    public function getShipmentRequestsAction()
    {
        $this->auth->allowOnly([Role::COMPANY_ADMIN, Role::COMPANY_OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $company_id = $this->request->getQuery('company_id', null, null);
        $status = $this->request->getQuery('status', null, null);
        $with_total_count = $this->request->getQuery('with_total_count', null, null);

        if (is_null($company_id)) {
            return $this->response->sendError('company_id is required');
        }

        $company = Company::findFirst($company_id);
        if (!$company) {
            return $this->response->sendError(ResponseMessage::INVALID_COMPANY_ID_SUPPLIED);
        }

        $params = ['limit' => intval($count), 'offset' => $offset];
        if (!is_null($status)) {
            $params['conditions'] = "status=:status:";
            $params['bind']['status'] = $status;
        }

        $requests = $company->getCorporateShipmentRequests($params)->toArray();

        if (!empty($with_total_count)) {
            $data = [
                'total_count' => $company->getCorporateShipmentRequests(null)->count(),
                'requests' => $requests
            ];
        } else {
            $data = $requests;
        }
        return $this->response->sendSuccess($data);
    }
}

