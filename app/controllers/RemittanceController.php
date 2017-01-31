<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 1/23/2017
 * Time: 11:20 AM
 */


use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class RemittanceController extends ControllerBase
{
    public function getDueParcelsAction(){
        $company_registration_number = $this->request->getQuery('registration_number');
        if($company_registration_number){
            $company = Company::getByRegistrationNumber($company_registration_number);
            if(!$company) return $this->response->sendError('Invalid Company Registration Number');
            $company_id = $company->getId();
        }
        $start_delivery_date = $this->request->getQuery('start_delivery_date');
        $end_delivery_date = $this->request->getQuery('end_delivery_date');
        $send_all = $this->request->getQuery('send_all', false);
        $with_total_count = $this->request->getQuery('with_total_count');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_by = [];
        if($company_registration_number) $filter_by['company_id'] = $company_id;
        if($start_delivery_date) $filter_by['start_delivery_date'] = $start_delivery_date;
        if($end_delivery_date) $filter_by['end_delivery_date'] = $end_delivery_date;
        if($send_all) $filter_by['send_all'] = true;


        $parcels = Remittance::fetchDueForPaymentParcels($offset, $count, $filter_by);
        if($with_total_count){
            $count = Remittance::dueForPaymentParcelCount($filter_by);
            $result = [
                'total_count' => $count,
                'parcels' => $parcels
            ];
        }else $result = $parcels;

        return $this->response->sendSuccess($result);
    }

    public function getAllAction(){
        $company_registration_number = $this->request->getQuery('company_registration_number');
        $start_date = $this->request->getQuery('start_date');
        $end_date = $this->request->getQuery('end_date');
        $min_amount = $this->request->getQuery('min_amount');
        $max_amount = $this->request->getQuery('max_amount');
        $send_all = $this->request->getQuery('send_all');
        $with_total_count = $this->request->getQuery('with_total_count');
        $ref  = $this->request->getQuery('ref');
        $status = $this->request->getQuery('status');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $with_parcel = $this->request->getQuery('with_parcel');
        $with_company = $this->request->getQuery('with_company');
        $with_payer = $this->request->getQuery('with_payer');

        $filter_by = [];

        if($company_registration_number) $filter_by['company_registration_number'] = $company_registration_number;
        if($start_date) $filter_by['start_date'] = $start_date;
        if($end_date) $filter_by['end_date'] = $end_date;
        if($min_amount) $filter_by['min_amount'] = $min_amount;
        if($max_amount) $filter_by['max_amount'] = $max_amount;
        if($ref) $filter_by['ref'] = $ref;
        if($status) $filter_by['status'] = $status;
        if($send_all) $filter_by['send_all'] = 1;

        $fetch_with = [];
        if($with_company) $fetch_with['with_company'] = 1;
        if($with_parcel) $fetch_with['with_parcel'] = 1;
        if($with_payer) $fetch_with['with_payer'] = 1;

        $remittances = Remittance::fetchAll($offset, $count, $filter_by, $fetch_with);
        if($with_total_count){
            $count = Remittance::remittanceCount($filter_by);
            $result = ['total_count' => $count, 'remittances' => $remittances];
        }else{
            $result = $remittances;
        }
        return $this->response->sendSuccess($result);
    }

    public function getPaymentAdviceAction(){
        $company_registration_number = $this->request->getQuery('registration_number');
        $status = $this->request->getQuery('status');
        $ref = $this->request->getQuery('ref');

        if($company_registration_number){
            $company = Company::getByRegistrationNumber($company_registration_number);
            if(!$company) return $this->response->sendError('Invalid Company Registration Number');
        }
        $start_delivery_date = $this->request->getQuery('start_delivery_date');
        $end_delivery_date = $this->request->getQuery('end_delivery_date');
        $send_all = $this->request->getQuery('send_all', null, false);
        $with_total_count = $this->request->getQuery('with_total_count');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_by = [];
        if($company_registration_number) $filter_by['company_registration_number'] = $company_registration_number;
        $filter_by['status'] = $status?$status:Status::REMITTANCE_READY_FOR_PAYOUT;
        $filter_by['ref'] = $ref;
        if($start_delivery_date) $filter_by['start_delivery_date'] = $start_delivery_date;
        if($end_delivery_date) $filter_by['end_delivery_date'] = $end_delivery_date;
        if($send_all) $filter_by['send_all'] = true;

        $parcels = Remittance::fetchPaymentAdvice($offset, $count, $filter_by);
        if($with_total_count){
            $count = Remittance::countPaymentAdvice($filter_by);
            $result = [
                'total_count' => $count,
                'payments' => $parcels
            ];
        }else $result = $parcels;

        return $this->response->sendSuccess($result);
    }

    public function saveAction(){
        $company_ids = $this->request->getPost('company_ids');
        $current_status = $this->request->getPost('current_status');
        if(in_array(null, [$company_ids, $current_status])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $company_ids = explode(',', $company_ids);

        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();

        $ref = time();
        try{
            foreach ($company_ids as $company_id) {
                $company = Company::fetchOne(['id' => $company_id], []);
                $parcels = Remittance::fetchAll(0, 0, ['send_all' => 1, 'status' => $current_status,
                    'company_registration_number' => $company['reg_no']], []);

                if($parcels && count($parcels) > 0){
                    foreach ($parcels as $parcel) {
                        $remittance = Remittance::findFirst(['id = :id:',
                            'bind' => ['id' => $parcel['id']]]);
                        if(!$remittance) continue;
                        $remittance->setStatus(Status::REMITTANCE_PAID);
                        $remittance->setPayerId($this->auth->getPersonId());
                        $remittance->setRef($ref);
                        $remittance->setTransaction($transaction);
                        if(!$remittance->save()) {
                            $transaction->rollback();
                            return $this->response->sendError('Cannot create remittance. Please try again');
                        }
                    }
                }
            }
            $transaction->commit();
        }catch (Exception $exception){
            Util::slackDebug('Cannot Create Remittance', $exception->getTraceAsString());
            $transaction->rollback();
            return $this->response->sendError('Error in creating remittance');
        }


        return $this->response->sendSuccess($ref);
    }

    public function getPaymentAdviceForDownloadAction(){
        $ref = $this->request->get('ref');
        $company_id = $this->request->get('company_id');
        if(in_array(null, [$ref])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $filter_by = ['send_all' => 1];
        if($company_id) $filter_by['company_id'] = $company_id;
        $payments = Remittance::fetchDuePaymentAdvice(0, 0, $ref, $filter_by);
        return $this->response->sendSuccess($payments);
    }

    public function getPendingPaymentsAction(){
        $company_registration_number = $this->request->getQuery('registration_number');
        if($company_registration_number){
            $company = Company::getByRegistrationNumber($company_registration_number);
            if(!$company) return $this->response->sendError('Invalid Company Registration Number');
            $company_id = $company->getId();
        }
        $start_delivery_date = $this->request->getQuery('start_delivery_date');
        $end_delivery_date = $this->request->getQuery('end_delivery_date');
        $send_all = $this->request->getQuery('send_all', null, false);
        $with_total_count = $this->request->getQuery('with_total_count');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_by = [];
        if($company_registration_number) $filter_by['company_id'] = $company_id;
        if($start_delivery_date) $filter_by['start_delivery_date'] = $start_delivery_date;
        if($end_delivery_date) $filter_by['end_delivery_date'] = $end_delivery_date;
        if($send_all) $filter_by['send_all'] = true;

        $parcels = Remittance::fetchPendingDuePaymentAdvice($offset, $count, $filter_by);
        if($with_total_count){
            $count = Remittance::countPendingDuePaymentAdvice($filter_by);
            $result = [
                'total_count' => $count,
                'payments' => $parcels
            ];
        }else $result = $parcels;

        return $this->response->sendSuccess($result);
    }

    public function importPaidParcelsAction(){
        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $parcels = Parcel::getByWaybillNumberList(implode(',', $waybill_numbers));
    }
}