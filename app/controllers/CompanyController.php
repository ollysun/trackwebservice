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
        $company->setPrimaryContactId($primary_contact->id);


        if (isset($data['secondary_contact'])) {
            if (UserAuth::findFirstByEmail($data['secondary_contact']['email'])) {
                return $this->response->sendError(ResponseMessage::SECONDARY_CONTACT_EXISTS);
            }
            $secondary_contact = $company->createUser(Role::COMPANY_OFFICER, $data['secondary_contact']);
            if (!$secondary_contact) {
                return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_COMPANY_SECONDARY_CONTACT);
            }
            $company->setSecContactId($secondary_contact->id);
        } else {
            $secondary_contact = null;
            $company->setSecContactId(null);
        }

        if (!$company->save()) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_LINK_CONTACTS_TO_COMPANY);
        }


        $primary_contact_data['id'] = $primary_contact->id;
        $company->notifyContact($primary_contact_data);

        if (isset($data['secondary_contact'])) {
            $data['secondary_contact']['id'] = $secondary_contact->id;
            $company->notifyContact($data['secondary_contact']);
        }

        $this->db->commit();

        return $this->response->sendSuccess($company->toArray());
    }
} 