<?php

/**
 * @author Babatunde Otaru <tunde@cottacush.com>
 */
class ChangeBillingPlanTask extends BaseTask
{

    const DEFAULT_BILLING_TYPE = 4;
    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     */
    public function mainAction()
    {
        $dataFileName = 'change_billing_plan.txt';
        $companies = file_get_contents(dirname(__FILE__) . "/../../data/corporate/$dataFileName", "r");
        $companies = explode("\n", $companies);

        foreach ($companies as $company) {
            if(!empty($company)){
                $this->ChangeBillingPlanForCooperatesToDefault($company);
            }
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $companyName
     */
    public function ChangeBillingPlanForCooperatesToDefault($companyName)
    {
        $company = Company::findFirstByName($companyName);
        /**
         * @var Company $company
         */
        if (!$company) {
            $this->printToConsole("$companyName does not exists");
        } else {
            $companyId = $company->getId();
            /**
             * @var \Phalcon\Mvc\Model\Resultset $billingPlans
             */
            $billingPlans = BillingPlan::findByCompanyId($companyId);

            if ($billingPlans->count() > 0) {
                $ids = [];
                foreach ($billingPlans as $billingPlan) {
                    /**
                     * @var BillingPlan $billingPlan
                     */
                    $billing_plan_id = $billingPlan->getId();
                    $ids[] = $billing_plan_id;
                    $onforwarding_charge_ids = [];
                    $weight_ranges_ids = [];

                    $onforwarding_charges = OnforwardingCharge::findByBillingPlanId($billing_plan_id);
                    foreach ($onforwarding_charges as $onforwarding_charge) {
                        /**
                         * @var OnforwardingCharge $onforwarding_charge
                         */
                        $onforwarding_charge_ids[] = $onforwarding_charge->getId();
                    }

                    $weightRanges = WeightRange::findByBillingPlanId($billing_plan_id);
                    foreach ($weightRanges as $weightRange) {
                        /**
                         * @var WeightRange $weightRange
                         */
                        $weight_ranges_ids[] = $weightRange->getId();
                    }
                    $this->deleteCurrentBilingPlanForCorporate($weight_ranges_ids,$onforwarding_charge_ids,$ids,$billing_plan_id,$companyName);
                }
            } else {
                $this->printToConsole("$companyName does not have a billing plan");
            }
            $this->createDefaultBillingPlanForCorporate($companyName,$companyId);
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $companyName
     * @param $companyId
     */
    public function createDefaultBillingPlanForCorporate($companyName,$companyId)
    {
        $this->printToConsole("Creating the default billing Plan for $companyName ...");
        $newBillingPlan = new BillingPlan();
        $newBillingPlan->initData($companyName,self::DEFAULT_BILLING_TYPE,$companyId);
        if($newBillingPlan->save()) {
            BillingPlan::cloneDefaultBilling($newBillingPlan->getId());
            $this->printToConsole("default billing plan for $companyName created");
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $weight_ranges_ids
     * @param $onforwarding_charge_ids
     * @param $billing_plan_id
     * @param $companyName
     */
    public function deleteCurrentBilingPlanForCorporate($weight_ranges_ids, $onforwarding_charge_ids, $billing_plan_ids,$billing_plan_id, $companyName)
    {
        $connection = (new BaseModel())->getWriteConnection();
        $connection->begin();
        try {

            if (!empty($weight_ranges_ids)) {
                $weight_ranges_ids = implode(',', $weight_ranges_ids);
                $connection->delete('weight_billing', "weight_range_id IN ($weight_ranges_ids)");
                $connection->delete('weight_range', "id IN ($weight_ranges_ids)");
            }

            if (!empty($onforwarding_charge_ids)) {
                $onforwarding_charge_ids = implode(',', $onforwarding_charge_ids);
                $connection->delete('onforwarding_city', "onforwarding_charge_id IN ($onforwarding_charge_ids)");
            }

            $ids = implode(',', $billing_plan_ids);
            $connection->delete('onforwarding_charge', "billing_plan_id IN ($ids)");
            $connection->delete('billing_plan', "id=$billing_plan_id");
            $connection->commit();
            $this->printToConsole("billing plan for $companyName deleted");

        } catch (PDOException $e) {
            $connection->rollback();
            $this->printToConsole("could not delete $companyName billing plan ");
        }
    }
}
