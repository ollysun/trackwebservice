<?php

/**
 * Changes all onforwarding charges for companies listed to zero
 * Requires A list of companies in the data folder
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class ZeroOnforwardingChargesTask extends BaseTask
{
    /**
     * Main Action
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function mainAction()
    {
        // Find company by name
        $dataFileName = 'companies_zeroised_on_forwarding_charges_2.txt';
        $companies = file_get_contents(dirname(__FILE__) . "/../../data/corporate/$dataFileName", "r");
        $companies = explode("\n", $companies);

        foreach ($companies as $company) {
            $this->zeroiseOnforwarding(trim($company));
        }
        // Use company_id to find billing plans for company

    }

    /**
     *
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $companyName
     */
    private function zeroiseOnforwarding($companyName)
    {
        $company = Company::findFirstByName($companyName);

        // If company exists
        if ($company) {
            /**
             * @var Company $company
             */
            $companyId = $company->getId();
            // Use company_id to find billing plans for company

            /**
             * @var \Phalcon\Mvc\Model\Resultset $billingPlans
             */
            $billingPlans = BillingPlan::find("company_id=$companyId");
            if($billingPlans->count() > 0) {
                // Billing plans exist, use billing plan id to zeroise onforwarding charges
                $ids = [];
                foreach($billingPlans as $billingPlan) {
                    /**
                     * @var BillingPlan $billingPlan
                     */
                    $ids[] = $billingPlan->getId();
                }
                $ids = implode(',', $ids);
                $connection = (new BaseModel())->getWriteConnection();
                $status = $connection->update('onforwarding_charge', ['amount', 'percentage', 'modified_date'], [0, 0, Util::getCurrentDateTime()], "billing_plan_id IN ($ids)");
                if(!$status) {
                    $this->printToConsole("Unable to update onforwarding charge for *$companyName*");
                }
            } else {
                // Company does not have a billing plan
                $this->printToConsole("Company *{$company->getName()}* does not have any billing plans");
            }
        } else {
            // Invalid company name
            $this->printToConsole("Company with name *$companyName* does not exists");
        }
    }
}