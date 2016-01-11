<?php
use Phalcon\Exception;

/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 08/01/2016
 * Time: 2:29 PM
 */
class BulkParcelCreationJob extends BaseJob
{
    /** @var Job $bulkShipmentJob */
    private $bulkShipmentJob;

    /**
     * action to perform on start
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function onStart()
    {
        $this->bulkShipmentJob = Job::findFirstByServerJobId($this->id);
        if (!$this->bulkShipmentJob) {
            return false;
        }
        $this->bulkShipmentJob->started_at = Util::getCurrentDateTime();
        $this->bulkShipmentJob->status = Job::STATUS_IN_PROGRESS;
        return $this->bulkShipmentJob->save();
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
                $parcelData->billing_plan_id = $jobData->billing_plan_id;
                $parcelData->created_by = $jobData->created_by;

                if ($this->bulkShipmentJob) {
                    $bulkShipmentJobDetail = new BulkShipmentJobDetail();
                    $bulkShipmentJobDetail->job_id = $this->bulkShipmentJob->id;
                    $bulkShipmentJobDetail->data = json_encode($parcelData);
                    $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_IN_PROGRESS;
                    $bulkShipmentJobDetail->started_at = Util::getCurrentDateTime();
                    $bulkShipmentJobDetail->save();
                }

                $parcel = $this->createParcel($parcelData, $jobData->billing_plan_id, $jobData->created_by);
                if (!$parcel) {
                    if ($this->bulkShipmentJob) {
                        $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_FAILED;
                    }
                } else {
                    if ($this->bulkShipmentJob) {
                        $bulkShipmentJobDetail->waybill_number = $parcel->getWaybillNumber();
                        $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_SUCCESS;
                    }
                }
            } catch (Exception $ex) {
                if ($this->bulkShipmentJob) {
                    $bulkShipmentJobDetail->status = BulkShipmentJobDetail::STATUS_FAILED;
                    $bulkShipmentJobDetail->error_message = $ex->getMessage();
                }
            }

            if ($this->bulkShipmentJob) {
                $bulkShipmentJobDetail->completed_at = Util::getCurrentDateTime();
                $bulkShipmentJobDetail->save();
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
        $status = $parcel_obj->saveForm($creatorBranch->getId(), $sender, $sender_address, $receiver, $receiver_address,
            '', $parcelData, $to_branch_id, $createdBy->getId());
        return ($status) ? $parcel_obj : false;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param null $error
     * @return bool
     */
    public function onFail($error = null)
    {
        if (!$this->bulkShipmentJob) {
            return false;
        }
        $this->bulkShipmentJob->status = Job::STATUS_FAILED;
        $this->bulkShipmentJob->error_message = null;
        return $this->bulkShipmentJob->save();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function onSuccess()
    {
        if (!$this->bulkShipmentJob) {
            return false;
        }
        $this->bulkShipmentJob->status = Job::STATUS_SUCCESS;
        return $this->bulkShipmentJob->save();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function onComplete()
    {
        if (!$this->bulkShipmentJob) {
            return false;
        }
        $this->bulkShipmentJob->completed_at = Util::getCurrentDateTime();
        return $this->bulkShipmentJob->save();
    }
}