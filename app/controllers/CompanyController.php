<?php


class CompanyController extends ControllerBase {
    public function createCompanyAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $payload = $this->request->getJsonRawBody(true);

        if (!isset($data['primary_contact'], $data['company'])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!empty($data['company']['name'])){
            $company = Company::getByName($data['company']['name']);
            if (!empty($company)){
                return $this->response->sendError(ResponseMessage::COMPANY_EXISTING);
            }
        }

        $data['primary_contact']['password'] = $this->auth->generateToken(6);
        if (isset($data['primary_contact'])){
            $data['secondary_contact']['password'] = $this->auth->generateToken(6);
        }

        $company = new Company();
        if ($company->createWithUsers($payload)){
            $user_keys = ['primary_contact', 'secondary_contact'];
            foreach ($user_keys as $key){
                if (!isset($data[$key])){
                    continue;
                }
                if (!empty($data[$key]['id'])) {
                    EmailMessage::send(
                        EmailMessage::USER_ACCOUNT_CREATION,
                        [
                            'name' => $data[$key]['firstname'] . ' '. $data[$key]['lastname'],
                            'email' => $data[$key]['email'],
                            'password' => $data[$key]['password'],
                            'link' => $this->config->fe_base_url . '/site/changePassword?ican=' . md5($data[$key]['id']) . '&salt=' . $data[$key]['id'],
                            'year' => date('Y')
                        ],
                        'Courier Plus',
                        $data[$key]['email']
                    );
                }
            }
            return $this->response->sendSuccess($company->getId());
        }
        return $this->response->sendError();
    }
} 