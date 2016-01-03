<?php

/**
 * Created by PhpStorm.
 * @author Babatunde Otaru <tunde@cottacush.com>
 * Date: 12/30/15
 * Time: 8:35 PM
 */
class CloneBillingPlanForCorporatesTask extends BaseTask
{
    const DEFAULT_BILLING_TYPE = 4;
    const DEFAULT_BILLING_PLAN_COMPANY_NAME = "NO COMPANY";

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     */
    public function mainAction()
    {
        $dataFileName = 'clone_billing_plan.txt';
        $companies = file_get_contents(dirname(__FILE__) . "/../../data/corporate/$dataFileName", "r");
        $companies = explode("\n", $companies);
        $isBaseCompanySelected = false;
        $baseCompanyHasBillingPlan = false;
        $baseBillingPlanIds = null;
        $baseCompanyName = "";

        foreach ($companies as $company) {
            if (mb_strtoupper($company, 'utf-8') == $company && !empty($company)) {
                $baseCompanyName = $company;
                $baseCompany = Company::findFirstByName($baseCompanyName);
                if ($baseCompany) {
                    $isBaseCompanySelected = true;
                    /**
                     * @var Company $baseCompany
                     */
                    $baseCompanyId = $baseCompany->getId();
                    $baseBillingPlans = BillingPlan::findByCompanyId($baseCompanyId);
                    $baseBillingPlanIds = [];
                    if (count($baseBillingPlans) > 0) {
                        $baseCompanyHasBillingPlan = true;
                        foreach ($baseBillingPlans as $baseBillingPlan) {
                            /**
                             * @var BillingPlan $baseBillingPlan
                             */
                            $baseBillingPlanIds[] = $baseBillingPlan->getId();
                            $baseBillingPlanIds = implode(',', $baseBillingPlanIds);
                        }
                    } else {
                        $baseCompanyHasBillingPlan = false;
                        $this->printToConsole("no billing plan.");
                    }
                } else {
                    $this->printToConsole("$baseCompanyName does not exist");
                    $isBaseCompanySelected = false;
                }
            }
            if ($isBaseCompanySelected && $baseCompanyHasBillingPlan) {
                if ($company != $baseCompanyName && !empty($company)) {
                    $company = strtoupper($company);
                    $this->cloneBillingPlanForCorporate($baseBillingPlanIds, $company);
                }
            }
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $baseBillingPlanId
     * @param $companyName
     */
    public function cloneBillingPlanForCorporate($baseBillingPlanId, $companyName)
    {
        $company = Company::findFirstByName($companyName);

        if (!$company) {
            $this->printToConsole("$companyName does not exists");
        } else {
            /**
             * @var Company $company
             */
            $companyId = $company->getId();
            $this->clearCurrentBillingPlanForCorporate($companyId,$companyName);
            $this->cloneBillingPlan($baseBillingPlanId, $companyName, $companyId);
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $weight_ranges_ids
     * @param $onforwarding_charge_ids
     * @param $billing_plan_id
     * @param $companyName
     */
    public function deleteCurrentBilingPlanDetailsForCorporate($weight_ranges_ids, $onforwarding_charge_ids, $billing_plan_ids, $billing_plan_id, $companyName)
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

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $baseBillingPlanId
     * @param $companyName
     * @param $companyId
     */
    public function cloneBillingPlan($baseBillingPlanId, $companyName, $companyId)
    {
        $newBillingPlan = new BillingPlan();
        $newBillingPlan->initData($companyName, self::DEFAULT_BILLING_TYPE, $companyId);
        if ($newBillingPlan->save()) {
            $this->clonePricingAndWeightRanges($baseBillingPlanId, $newBillingPlan->getId());
            $this->printToConsole("clonning Onforwarding Charge for $companyName ..");
            $onforwardingCharges = OnforwardingCharge::findByBillingPlanId($baseBillingPlanId);
            foreach ($onforwardingCharges as $onForwardingCharge) {
                /**
                 * @var OnforwardingCharge $onForwardingCharge
                 */
                $onforwardingCharge = new OnforwardingCharge();
                $onforwardingCharge->initData($onForwardingCharge->getName(), $onForwardingCharge->getCode(), $onForwardingCharge->getDescription(), $onForwardingCharge->getAmount(), $onForwardingCharge->getPercentage(), $newBillingPlan->getId());
                $onforwardingCharge->save();
                $onforwardingChargeId = $onforwardingCharge->getId();
                $this->printToConsole("clonning onforwarding citites");
                $onforwardingCities = OnforwardingCity::findByOnforwardingChargeId($onForwardingCharge->getId());
                foreach ($onforwardingCities as $onforwardingCity) {
                    /**
                     * @var OnforwardingCity $onforwardingCity
                     */
                    $newOnforwardingCity = new OnforwardingCity();
                    $newOnforwardingCity->initData($onforwardingCity->getCityId(), $onforwardingChargeId);
                    $newOnforwardingCity->save();
                }
            }

            $this->printToConsole("Onforwarding charge clone for $companyName completed.");
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $baseBillingPlanId
     * @param $newBillinPlanId
     */
    public function clonePricingAndWeightRanges($baseBillingPlanId, $newBillinPlanId)
    {
        $weightRanges = WeightRange::findByBillingPlanId($baseBillingPlanId);
        foreach ($weightRanges as $weightRange) {
            $newWeightRange = new WeightRange();
            /**
             * @var WeightRange $weightRange
             */
            $newWeightRange->initData($weightRange->getMinWeight(), $weightRange->getMaxWeight(), $weightRange->getIncrementWeight(), $newBillinPlanId);
            $newWeightRange->save();
            $weightBilings = WeightBilling::findByWeightRangeId($weightRange->getId());
            foreach ($weightBilings as $weightBilling) {
                $newWeightBilling = new WeightBilling();
                /**
                 * @var WeightBilling $weightBilling
                 */
                $newWeightBilling->initData($weightBilling->getZoneId(), $newWeightRange->getId(), $weightBilling->getBaseCost(),
                    $weightBilling->getBasePercentage(), $weightBilling->getIncrementCost(), $weightBilling->getBasePercentage());
                $newWeightBilling->save();
            }
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $companyId
     * @param $companyName
     */
    public function clearCurrentBillingPlanForCorporate($companyId, $companyName)
    {
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
                $this->deleteCurrentBilingPlanDetailsForCorporate($weight_ranges_ids, $onforwarding_charge_ids, $ids, $billing_plan_id, $companyName);
            }
        } else {
            $this->printToConsole("$companyName does not have a billing plan");
        }
    }
}