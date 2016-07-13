<?php
use Phalcon\Exception;

/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 08/01/2016
 * Time: 2:29 PM
 */
class BulkParcelCreationJob extends BaseJob
{
    /**
     * execute current job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function execute()
    {
        $jobData = $this->data;
        $shipmentData = $jobData->data;

        if (!$this->jobLog) {
            return false;
        }

        $jobStatus = false;

        foreach ($shipmentData as $parcelData) {
            $bulkShipmentJobDetail = new BulkShipmentJobDetail();
            $bulkShipmentJobDetail->job_id = $this->jobLog->id;
            $bulkShipmentJobDetail->data = json_encode($parcelData);
            $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_IN_PROGRESS;
            $bulkShipmentJobDetail->started_at = Util::getCurrentDateTime();
            $bulkShipmentJobDetail->save();
            try {
                $parcelData->billing_plan_id = $jobData->billing_plan_id;
                $parcelData->created_by = $jobData->created_by;
                $parcel = $this->createParcel($parcelData, $jobData->billing_plan_id, $jobData->created_by);
                if (!$parcel) {
                    $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_FAILED;
                } else {
                    $bulkShipmentJobDetail->waybill_number = $parcel->getWaybillNumber();
                    $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_SUCCESS;
                }
            } catch (Exception $ex) {
                $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_FAILED;
                $bulkShipmentJobDetail->error_message = $ex->getMessage();
            }

            $bulkShipmentJobDetail->completed_at = Util::getCurrentDateTime();
            $bulkShipmentJobDetail->save();
            $jobStatus = ($bulkShipmentJobDetail->status == BulkShipmentJobDetail::STATUS_SUCCESS) || $jobStatus;
        }

        return $jobStatus;
    }

    private function createParcel($parcelData, $billingPlanId, $createdBy)
    {
        $parcelData = (array)$parcelData;
        $createdBy = Admin::findFirst($createdBy);
        $creatorBranch = $createdBy->getBranch();


        if ($creatorBranch->getBranchType() == BranchType::EC) {
            $to_branch = Branch::getParentById($creatorBranch->getId());
            if ($to_branch == null) {
                throw new Exception(ResponseMessage::EC_NOT_LINKED_TO_HUB);
            }
            $to_branch_id = $to_branch->getId();
        } else {
            $to_branch_id = $createdBy->getBranchId();
        }

        //parcel no_of_package validation
        $parcelData['no_of_package'] = intval($parcelData['no_of_package']);
        if ($parcelData['no_of_package'] < 1) {
            throw new Exception(ResponseMessage::INVALID_PACKAGE_COUNT);
        }

        $parcel_obj = new Parcel();
        $receiver = [];
        $receiver['phone'] = empty($parcelData['receiver_phone_number']) ? Parcel::NOT_APPLICABLE : $parcelData['receiver_phone_number'];
        $receiver['firstname'] = $parcelData['receiver_name'];
        $receiver['lastname'] = null;
        $receiver['email'] = $parcelData['receiver_email'];

        $sender = [];
        $sender['phone'] = empty($parcelData['sender_phone_number']) ? Parcel::NOT_APPLICABLE : $parcelData['sender_phone_number'];
        $sender['firstname'] = $parcelData['sender_name'];
        $sender['lastname'] = null;
        $sender['email'] = $parcelData['sender_email'];

        $billingFromBranch = City::findFirst($parcelData['sender_city']);
        $billingToBranch = City::findFirst($parcelData['receiver_city']);
        $calc_weight_billing = WeightBilling::calcBilling($billingFromBranch->getBranchId(), $billingToBranch->getBranchId(), $parcelData['weight'], $billingPlanId);
        if ($calc_weight_billing == false) {
            throw new Exception(ResponseMessage::CALC_BILLING_WEIGHT);
        }

        $onforwarding_charge = OnforwardingCharge::fetchByCity($parcelData['receiver_city'], $billingPlanId);
        if ($onforwarding_charge == false) {
            throw new Exception(ResponseMessage::CALC_BILLING_ONFORWARDING);
        }
        $parcelData['amount_due'] = $calc_weight_billing + $onforwarding_charge->getAmount();
        $parcelData['cash_on_delivery'] = 0;
        $parcelData['cash_on_delivery_amount'] = null;
        $parcelData['delivery_type'] = DeliveryType::DISPATCH;
        $parcelData['shipping_type'] = ShippingType::EXPRESS;
        $parcelData['package_value'] = '';
        $parcelData['cash_amount'] = 0;
        $parcelData['is_billing_overridden'] = 0;
        $parcelData['pos_amount'] = 0;
        $parcelData['pos_trans_id'] = '';
        $parcelData['request_type'] = RequestType::OTHERS;
        $parcelData['billing_type'] = 'corporate';
        $parcelData['weight_billing_plan'] = $billingPlanId;
        $parcelData['onforwarding_billing_plan'] = $billingPlanId;
        $parcelData['is_freight_included'] = 1;
        $parcelData['qty_metrics'] = 'weight';
        $parcelData['insurance'] = 0;
        $parcelData['duty_charge'] = 0;
        $parcelData['handling_charge'] = 0;
        $parcelData['cost_of_crating'] = 0;
        $parcelData['storage_demurrage'] = 0;
        $parcelData['others'] = 0;
        $parcelData['is_bulk_shipment'] = 1;

        $sender_address = [];
        $sender_address['street1'] = $parcelData['sender_address'];
        $sender_address['street2'] = '';
        $sender_address['country_id'] = $parcelData['sender_country'];
        $sender_address['city_id'] = $parcelData['sender_city'];
        $sender_address['state_id'] = $parcelData['sender_state'];
        $sender_address['id'] = null;

        $receiver_address = [];
        $receiver_address['street1'] = $parcelData['receiver_address'];
        $receiver_address['street2'] = '';
        $receiver_address['country_id'] = $parcelData['receiver_country'];
        $receiver_address['city_id'] = $parcelData['receiver_city'];
        $receiver_address['state_id'] = $parcelData['receiver_state'];
        $receiver_address['id'] = null;

        print 'Processing:: ' . json_encode($parcelData) . "\n";
        $status = $parcel_obj->saveForm($creatorBranch->getId(), $sender, $sender_address, $receiver, $receiver_address,
            '', $parcelData, $to_branch_id, $createdBy->getId());
        return ($status) ? $parcel_obj : false;
    }
}