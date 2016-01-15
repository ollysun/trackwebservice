<?php
use Phalcon\Text;

/**
 * Class CorporateUploadTask
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class CorporateUploadTask extends BaseTask
{

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $params
     */
    public function mainAction(array $params)
    {
        $noOfFiles = count($params);
        for ($i = 0; $i < $noOfFiles; $i++) {
            $handle = fopen(dirname(__FILE__) . "/../../data/corporate/$params[$i]", "r");

            if (!$handle) {
                $this->printToConsole("Unable to read data file");
            }
            $line = fgets($handle);
            $data = $this->cleanData(str_getcsv($line));
            $headerLength = count($data);
            $count = 0;
            while (($line = fgets($handle)) !== false) {
                if ($count > 0) {
                    $this->uploadCorporate($line, $headerLength);
                }
                $count++;
            }
            fclose($handle);
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $line
     * @param $headerLength
     * @return bool
     */
    private
    function uploadCorporate($line, $headerLength)
    {
        $data = $this->cleanData(str_getcsv($line));
        if (count($data) < $headerLength && $data) {
            $this->printToConsole('ERROR: Invalid data: ');
            $this->printStringArray($data);
            return false;
        }

        $company_name = isset($data[0]) ? $data[0] : '';
        $account_number = isset($data[1]) ? $data[1] : '';
        $phone_number = isset($data[2]) ? $data[2] : '';
        $address = isset($data[3]) ? $data[3] : '';
        $company_city = isset($data[4]) ? strtolower($data[4]) : '';
        $primary_contact_first_name = isset($data[6]) ? $data[6] : '';
        $primary_contact_last_name = isset($data[7]) ? $data[7] : '';
        $primary_contact_phone_number = isset($data[8]) && !empty($data[8]) ? $data[8] : '08000000000';
        $email_address = (isset($data[9]) && !empty($data[9])) ? $data[9] : 'noemailaddress@thiscorporate.com';
        $clone_billing_plan_name = isset($data[10]) ? strtolower($data[10]) : 'standard tariff';

        if (strlen($company_name) == 0) {
            return false;
        }

        $this->db->begin();
        $company = new Company();
        $company->setName($company_name);
        $company->setRegNo($account_number);
        $company->setEmail($email_address);
        $company->setPhoneNumber($phone_number);
        if (strlen($address) == 0) {
            $company->setAddress(new \Phalcon\Db\RawValue(''));
        } else {
            $company->setAddress($address);
        }

        /** @var City $companyCity */
        $companyCity = City::findFirstByName($company_city);
        if (!$companyCity) {
            $companyCity = City::findFirstByName('no city');
        }
        $company->setCityId($companyCity->getId());

        $company->setCreditLimit(null);
        $company->setDiscount(null);

        /** @var UserAuth $primaryContact */
        $primaryContact = UserAuth::findFirstByEmail($email_address);
        if (!$primaryContact) {
            $primaryContact = new UserAuth();
            $primaryContact->setEmail($email_address);
            $primaryContact->setPassword(Text::random(Text::RANDOM_ALNUM, 8));
            $primaryContact->setEntityType(UserAuth::ENTITY_TYPE_CORPORATE);
            $primaryContact->setCreatedDate(Util::getCurrentDateTime());
            $primaryContact->setModifiedDate(Util::getCurrentDateTime());
            $primaryContact->setStatus(Status::INACTIVE);
            if (!$primaryContact->save()) {
                $this->printToConsole('FAILED: Could not save primary contact. REASON:' . $this->printStringArray($primaryContact->getMessages(), true) . ' Data: ');
                $this->printStringArray($data);
                return false;
            }
            $this->printToConsole('created');
        }

        $company->setPrimaryContactId(new \Phalcon\Db\RawValue(null));
        $company->setSecContactId(new \Phalcon\Db\RawValue(null));

        /** @var Admin $relationsOfficer */
        $relationsOfficer = Admin::findFirstByStaffId('CSL/0004');
        if (!$relationsOfficer) {
            $this->printToConsole('FAILED: Could not find relations officer. Data: ' . $this->printStringArray($data));
            return false;
        }

        $company->setRelationsOfficerId($relationsOfficer->getId());
        $company->setCreatedDate(Util::getCurrentDateTime());
        $company->setModifiedDate(Util::getCurrentDateTime());
        $company->setStatus(Status::ACTIVE);
        if (!$company->save()) {
            $this->printToConsole('FAILED: Could not save company. REASON:' . $this->printStringArray($company->getMessages(), true) . 'Data: ');
            $this->printStringArray($data);
            return false;
        }

        $companyUser = new CompanyUser();
        $companyUser->setCompanyId($company->getId());
        if ($primary_contact_first_name == 'No First Name Available' || strlen($primary_contact_first_name) == 0) {
            $companyUser->setFirstname(new \Phalcon\Db\RawValue(''));
        } else {
            $companyUser->setFirstname($primary_contact_first_name);
        }
        if (strlen($primary_contact_last_name) == 0) {
            $companyUser->setLastname(new \Phalcon\Db\RawValue(''));
        } else {
            $companyUser->setLastname($primary_contact_last_name);
        }
        $companyUser->setPhoneNumber($primary_contact_phone_number);
        $companyUser->setRoleId(Role::COMPANY_ADMIN);
        $companyUser->setCreatedDate(Util::getCurrentDateTime());
        $companyUser->setModifiedDate(Util::getCurrentDateTime());
        $companyUser->setUserAuthId($primaryContact->getId());
        if (!$companyUser->save()) {
            $this->printToConsole('FAILED: Could not save company user. REASON:' . $this->printStringArray($companyUser->getMessages(), true) . 'Data : ');
            $this->printStringArray($data);
            return false;
        }

        $company->setPrimaryContactId($companyUser->getId());
        if (!$company->save()) {
            $this->printToConsole('FAILED: Could not link primary contact to company. REASON:' . $this->printStringArray($company->getMessages(), true) . 'Data : ');
            $this->printStringArray($data);
            return false;
        }

        $plan = new BillingPlan();
        $plan->initData($company->getName(), BillingPlan::TYPE_WEIGHT_AND_ON_FORWARDING, $company->getId());
        if ($plan->save()) {
            if ($clone_billing_plan_name != 'standard tariff') {
                {
                    $baseBillingPlan = BillingPlan::findFirstByName($clone_billing_plan_name);
                    if ($baseBillingPlan) {
                        $baseBillingPlanId = $baseBillingPlan->toArray()['id'];
                        $plan->cloneBillingPlan($baseBillingPlanId, $plan);
                    } else {
                        $this->printToConsole('plan does not exists, creating the default billing plan...');
                        BillingPlan::cloneDefaultBilling($plan->getId());
                    }
                }
            } else {
                BillingPlan::cloneDefaultBilling($plan->getId());
            }
        } else {
            $this->printToConsole('FAILED: Could not save billing plan for company. REASON:' . $this->printStringArray($plan->getMessages(), true) . ' Data: ');
            $this->printStringArray($data);
            return false;
        }

        $this->db->commit();
        $this->printToConsole('SUCCESS: Uploaded ' . $company_name);
        return true;
    }

    private
    function cleanData(array $data)
    {
        foreach ($data as $key => &$value) {
            $value = trim($value, '\'\ \"');
        }
        return $data;
    }
}