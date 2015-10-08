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
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return $this
     */
    public function getRequestsAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $company_id = $this->request->getQuery('company_id', null, null);
        $status = $this->request->getQuery('status', null, null);
        $with_total_count = $this->request->getQuery('with_total_count', null, null);
        $request_type = $this->request->getQuery('type', null, 'shipment');

        if (!in_array(strtolower($request_type), ['shipment', 'pickup'])) {
            return $this->response->sendError('Invalid request type');
        }

        $criteria = [];
        $pagination_params = ['limit' => intval($count), 'offset' => $offset];
        if (!is_null($status)) {
            $criteria['conditions'] = "status=:status:";
            $criteria['bind']['status'] = $status;
        }

        if (!is_null($company_id)) {
            $company = Company::findFirst($company_id);
            if (!$company) {
                return $this->response->sendError(ResponseMessage::INVALID_COMPANY_ID_SUPPLIED);
            } else if ($company) {
                $criteria['conditions'] = (isset($criteria['conditions'])) ? $criteria['conditions'] . ' AND company_id = :company_id:' : 'company_id = :company_id:';
                $criteria['bind']['company_id'] = $company->getId();
            }
        }

        $params = array_merge($criteria, $pagination_params);
        $requests = ($request_type == 'shipment') ? ShipmentRequest::getRequests($params, $offset, $count) : PickupRequest::getRequests($params, $offset, $count);


        if (!empty($with_total_count)) {
            $data = [
                'total_count' => ($request_type == 'shipment') ? ShipmentRequest::count($criteria) : PickupRequest::count($criteria),
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
}


