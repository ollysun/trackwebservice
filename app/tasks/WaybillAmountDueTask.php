<?php

/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 27/06/2016
 * Time: 1:41 PM
 */
class WaybillAmountDueTask extends BaseTask
{

    public function mainAction()
    {
        $fileResource = fopen(__DIR__ . '/../../data/zero_amount_due_parcels.csv', 'r');

        while ($waybillNumber = fgets($fileResource)) {
            try {
                $waybillNumber = trim($waybillNumber);
                /** @var Parcel $parcel */
                $parcel = Parcel::findFirstByWaybillNumber($waybillNumber);
                if (!$parcel) {
                    $this->printToConsole('Could not find parcel with waybill number ' . $waybillNumber);
                    continue;
                }

                /** @var ReceiverAddress $receiverAddress */
                $receiverAddress = ReceiverAddress::findFirst($parcel->getReceiverAddressId());
                $receiverCity = ReceiverAddressCity::findFirst($receiverAddress->getCityId());

                /** @var SenderAddress $receiverAddress */
                $senderAddress = SenderAddress::findFirst($parcel->getSenderAddressId());
                $senderCity = SenderAddressCity::findFirst($senderAddress->getCityId());

                $amountDue = Zone::calculateBilling(
                    $receiverCity->getBranchId(),
                    $senderCity->getBranchId(),
                    $parcel->getWeight(),
                    $parcel->getWeightBillingPlanId(),
                    $receiverAddress->getCityId(),
                    $parcel->getOnforwardingBillingPlanId()
                );
                
                file_put_contents(__DIR__ . '/../../data/zero_amount_due_parcels_output.csv', $waybillNumber . ', ' . $amountDue . "\n", FILE_APPEND);
                $this->printToConsole($waybillNumber . ', ' . $amountDue);
            } catch (Exception $ex) {
                file_put_contents(__DIR__ . '/../../data/zero_amount_due_parcels_output.csv', $waybillNumber . ', ' . $ex->getMessage() . "\n", FILE_APPEND);
                $this->printToConsole($waybillNumber . ', ' . $ex->getMessage());
            }
        }
    }

}