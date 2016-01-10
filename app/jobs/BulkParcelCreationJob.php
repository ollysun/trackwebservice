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
     * action to perform on start
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onStart()
    {

    }

    /**
     * execute current job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function execute()
    {
        $jobData = json_decode($this->data);
        $shipmentData = $jobData->data;

        foreach ($shipmentData as $parcelData) {
            try {
                $this->createParcel($parcelData, $jobData->billing_plan_id, $jobData->created_by);
            } catch (Exception $ex) {
                // log task failed or something
            }
        }

        return true;
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

        $parcelData['payment_type'] = PaymentType::DEFERRED;

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

        $calc_weight_billing = WeightBilling::calcBilling($creatorBranch->getId(), $to_branch_id, $parcelData['weight'], $billingPlanId);
        if ($calc_weight_billing == false) {
            throw new Exception(ResponseMessage::CALC_BILLLING_WEIGHT);
        }

        $onforwarding_charge = OnforwardingCharge::fetchByCity($parcelData['receiver_city'], $billingPlanId);
        if ($onforwarding_charge == false) {
            throw new Exception(ResponseMessage::CALC_BILLLING_ONFORWARDING);
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
        $parcelData['request_type'] = RequestType::ECOMMERCE;
        $parcelData['billing_type'] = 'auto';
        $parcelData['weight_billing_plan'] = $billingPlanId;
        $parcelData['onforwarding_billing_plan'] = $billingPlanId;

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
        $waybill_numbers = $parcel_obj->saveForm($creatorBranch->getId(), $sender, $sender_address, $receiver, $receiver_address,
            '', $parcelData, $to_branch_id, $createdBy->getId());

        var_dump($waybill_numbers);
        if ($waybill_numbers) {
            //return ['id' => $parcel_obj->getId(), 'waybill_number' => $waybill_numbers];
        } else {
            //return false;
        }
    }

    public function onFail($error = null)
    {
        print 'Job Failed ';
        if (!is_null($error)) {
            print 'Reason: ' . $error;
        } else {
            print "\n";
        }
    }

    public function onSuccess()
    {
        print 'Parcels Successfully Created';
    }
}