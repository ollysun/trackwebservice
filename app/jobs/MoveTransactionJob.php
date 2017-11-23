<?php
/**
 * Created by PhpStorm.
 * User: Moses Olalere
 * Date: 11/13/2017
 * @author Moses Olalere <moses_olalere@superfluxnigeria.com>
 * Time: 11:04 AM
 */

use Phalcon\Exception;


class MoveTransactionJob extends BaseJob
{


    /**
     * execute current job
     * @author moses olalere <moses_olalere@superfluxnigeria.com>
     * @return bool
     */
    public function execute()
    {
            $waybills = Parcel::sanitizeWaybillNumbers($this->data->waybills);
            $companyId = $this->data->toCompanyId;
            if (!$this->jobLog) {
                return false;
            }
            $jobStatus = false;
            foreach ($waybills as $wb)
            {
                print 'moving waybill no ' . $wb . '...' . "\n";
                $moveTransaction = new MoveTransactionsJobDetails();
                $moveTransaction->job_id = $this->jobLog->id;
                $moveTransaction->data = json_encode($wb);
                $moveTransaction->status = MoveTransactionsJobDetails::STATUS_IN_PROGRESS;
                $moveTransaction->started_at = Util::getCurrentDateTime();
                $moveTransaction->save();
                try{
                    $parcel = $this->moveTransaction($companyId, $wb);
                    if (!$parcel) {
                        $moveTransaction->status = MoveTransactionsJobDetails::STATUS_FAILED;
                    } else {
                        $moveTransaction->waybill_number = $parcel->getWaybillNumber();
                        $moveTransaction->company_id = $parcel->getCompanyId();
                        $moveTransaction->status = MoveTransactionsJobDetails::STATUS_SUCCESS;
                    }
                }catch (Exception $ex)
                {
                    Util::slackDebug('Move Transaction worker error', $ex->getMessage());
                    $moveTransaction->status = BulkShipmentJobDetail::STATUS_FAILED;
                    $moveTransaction->error_message = $ex->getMessage();
                }
                $moveTransaction->completed_at = Util::getCurrentDateTime();
                $moveTransaction->save();
                $jobStatus = ($moveTransaction->status == MoveTransactionsJobDetails::STATUS_SUCCESS) || $jobStatus;
            }
            return $jobStatus;
    }


    public function moveTransaction($companyId,$wb)
    {
        $parcel = Parcel::getByWaybillNumber($wb);
        if ($parcel === false) {
            if(!Parcel::isWaybillNumber($wb)){
                throw new Exception(ResponseMessage::PARCEL_NOT_EXISTING);
                return false;
            }
            throw new Exception(ResponseMessage::PARCEL_NOT_EXISTING);
            return false;
        }else{
            $parcel->setCompanyId($companyId);
            $parcel->update();
            if (!$parcel->update()) {
                throw new Exception(ResponseMessage::CANNOT_MOVE_PARCEL);
                return false;
            }

            print 'Waybill Number: ' . $wb . ' successfully moved' . "\n";
            return $parcel;
        }
    }
}