<?php


class CodtellerController extends ControllerBase {


    /**
     * Creates a teller using the specified details.
     * It returns the id of the created teller, if successful or the error, if not.
     *
     * @return PackedResponse
     */
    public function addAction(){
        $bank_id = $this->request->getPost('bank_id');
        $account_name = $this->request->getPost('account_name');
        $account_no = $this->request->getPost('account_no');
        $teller_no = $this->request->getPost('teller_no');
        $amount_paid = $this->request->getPost('amount_paid');
        $paid_by = $this->request->getPost('paid_by');
        $waybill_numbers = $this->request->getPost('waybill_numbers');

        $auth_data = $this->auth->getData();
        $branch_id = $auth_data['branch']['id'];
        $created_by = $this->auth->getPersonId();
        $paid_by = isset($paid_by) ? $paid_by: $created_by;


        if (!isset($bank_id, $account_no, $teller_no, $amount_paid, $waybill_numbers)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        //amount paid validation
        $amount_paid = intval($amount_paid);
        if ($amount_paid < 1){
            return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
        }

        $payer = Admin::getById($paid_by);
        if (false == $payer){
            return $this->response->sendError(ResponseMessage::INVALID_OFFICER);
        }


        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);

        //amount check
        /** @var Parcel[] $parcels */
        $parcels = Parcel::getByWaybillNumberList($waybill_number_arr);
        $amount = 0;
        foreach($parcels as $parcel){
            /** $parcel Parcel */
            $amount += $parcel->getCashOnDeliveryAmount();
        }

        if($amount_paid <= ($amount - 5)){
            return $this->response->sendError(ResponseMessage::INVALID_AMOUNT . ". Expected Amount: $amount");
        }

        $bad_parcels = array();
        $good_parcels = array();

        //checking the waybill_numbers for validity
        foreach ($waybill_number_arr as $waybill_number) {
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false) {
                $bad_parcels[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
            }
            else {
                $good_parcels[] = $parcel->getId();
            }
        }
        if(!empty($bad_parcels)){
            return $this->response->sendError($bad_parcels);
        }

        //check for the pre-existence of the teller no before the creation of the teller
        $teller = CodTeller::getTeller($bank_id, $teller_no);
        if($teller === false) {
            $teller = new CodTeller();
            $teller_id = $teller->saveForm($bank_id, $account_name, $account_no, $teller_no, $amount_paid, $good_parcels, $paid_by, $created_by, $branch_id);
            if ($teller_id){
                return $this->response->sendSuccess(['id' => $teller_id]);
            }
        }
        else{
            return $this->response->sendError(ResponseMessage::TELLER_ALREADY_USED);
        }
        return $this->response->sendError('Cannot add teller');
    }

    public function approveAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::BUSINESS_MANAGER]);
        $id = $this->request->getPost('id');
        if(in_array(null, [$id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        /** @var CodTeller $teller */
        $teller = CodTeller::findFirst("id = $id");
        if(!$teller){
            return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
        }
        $teller->setStatus(Status::TELLER_APPROVED);
        if($teller->save()){
            $tellerParcels = CodTellerParcel::fetchAll($teller->getId());
            if($tellerParcels){
                foreach ($tellerParcels as $tellerParcel) {
                    $waybill = $tellerParcel['parcel']['waybill_number'];
                    $remittance = Remittance::getByWaybillNumber($waybill);
                    if($remittance) {
                        $remittance->setStatus(Status::REMITTANCE_READY_FOR_PAYOUT);
                    }else{
                        /** @var Company $company */
                        $company = Company::findFirstById($tellerParcel['parcel']['company_id']);
                        if(!$company) {
                            Util::slackDebug('Remittance not save for '.$tellerParcel['parcel']['waybill_number'], 'Company not set');
                            continue;
                        }
                        $remittance = new Remittance();
                        $remittance->init($waybill, $tellerParcel['parcel']['delivery_amount'], $company->getRegNo(),
                            null, null, Status::REMITTANCE_READY_FOR_PAYOUT);
                    }
                    $remittance->save();
                }
            }
        }else{
            Util::slackDebug("Teller not approved", implode(',', $teller->getMessages()));
            return $this->response->sendError('Error in approving teller please try again later');
        }
        return $this->response->sendSuccess();
    }

    public function declineAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::BUSINESS_MANAGER]);
        $id = $this->request->getPost('id');
        if(in_array(null, [$id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        /** @var CodTeller $teller */
        $teller = CodTeller::findFirst("id = $id");
        if(!$teller){
            return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
        }
        $teller->setStatus(Status::TELLER_DECLINED);
        $teller->save();
        return $this->response->sendSuccess();
    }

    /**
     * Returns the details of a teller.
     *
     * @param id
     * @author  Olawale Lawal
     * @return int
     */
    public function getOneAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::BUSINESS_MANAGER]);

        $id = $this->request->getQuery('id');

        if (is_null($id)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }


        $teller = CodTeller::fetchOne($id);
        if ($teller != false){
            $teller['teller_parcels'] = CodTellerParcel::fetchAll($id);
            return $this->response->sendSuccess($teller);
        }

        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    /**
     * Arranges the filter for use
     *
     * @author  Olawale Lawal
     * @return array
     */
    private function getFilterParams(){
        $bank_id = $this->request->getQuery('bank_id');
        $teller_number = $this->request->getQuery('teller_number');
        $teller_number_arr = $this->request->getQuery('teller_number_arr');
        $paid_by = $this->request->getQuery('paid_by');
        $created_by = $this->request->getQuery('created_by');
        $branch_id = $this->request->getQuery('branch_id');
        $status = $this->request->getQuery('status');
        $min_amount_paid = $this->request->getQuery('min_amount_paid');
        $max_amount_paid = $this->request->getQuery('max_amount_paid');
        $start_created_date = $this->request->getQuery('start_created_date');
        $end_created_date = $this->request->getQuery('end_created_date');
        $start_modified_date = $this->request->getQuery('start_modified_date');
        $end_modified_date = $this->request->getQuery('end_modified_date');

        $filter_by = [];
        if (!is_null($bank_id)){ $filter_by['bank_id'] = $bank_id; }
        if (!is_null($teller_number)){ $filter_by['teller_number'] = $teller_number; }
        if (!is_null($teller_number_arr)){ $filter_by['teller_number_arr'] = $teller_number_arr; }
        if (!is_null($paid_by)){ $filter_by['paid_by'] = $paid_by; }
        if (!is_null($created_by)){ $filter_by['created_by'] = $created_by; }
        if (!is_null($branch_id)){ $filter_by['branch_id'] = $branch_id; }
        if (!is_null($status)){ $filter_by['status'] = $status; }
        if (!is_null($min_amount_paid)){ $filter_by['min_amount_paid'] = $min_amount_paid; }
        if (!is_null($max_amount_paid)){ $filter_by['max_amount_paid'] = $max_amount_paid; }
        if (!is_null($start_created_date)){ $filter_by['start_created_date'] = $start_created_date; }
        if (!is_null($end_created_date)){ $filter_by['end_created_date'] = $end_created_date; }
        if (!is_null($start_modified_date)){ $filter_by['start_modified_date'] = $start_modified_date; }
        if (!is_null($end_modified_date)){ $filter_by['end_modified_date'] = $end_modified_date; }

        return $filter_by;
    }


    /**
     * Returns the details of teller meeting a set of filter criteria.
     *
     * @author  Olawale Lawal
     * @return array
     */
    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::BUSINESS_MANAGER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $with_snapshot = $this->request->getQuery('with_snapshot');
        $with_bank = $this->request->getQuery('with_bank');
        $with_payer = $this->request->getQuery('with_payer');
        $with_creator = $this->request->getQuery('with_creator');
        $with_branch = $this->request->getQuery('with_branch');
        $with_total_count = $this->request->getQuery('with_total_count');
        $send_all = $this->request->getQuery('send_all');

        $order_by = $this->request->getQuery('order_by'); //'Parcel.created_date DESC'

        $filter_by = $this->getFilterParams();

        if (!is_null($send_all)){ $filter_by['send_all'] = true; }

        $fetch_with = [];
        if (!is_null($with_bank)){ $fetch_with['with_bank'] = true; }
        if (!is_null($with_payer)){ $fetch_with['with_payer'] = true; }
        if (!is_null($with_creator)){ $fetch_with['with_creator'] = true; }
        if (!is_null($with_snapshot)){ $fetch_with['with_snapshot'] = true; }
        if (!is_null($with_branch)){ $fetch_with['with_branch'] = true; }

        $tellers = CodTeller::fetchAll($offset, $count, $filter_by, $fetch_with, $order_by);
        $result = [];
        if ($with_total_count != null){
            $count = CodTeller::tellerCount($filter_by);
            $result = [
                'total_count' => $count,
                'tellers' => $tellers
            ];
        }else{
            $result = $tellers;
        }

        return $this->response->sendSuccess($result);
    }

    /**
     * Returns the number of tellers that meet a set of criteria.
     *
     * @author  Olawale Lawal
     * @return int
     */
    public function countAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::BUSINESS_MANAGER]);

        $filter_by = $this->getFilterParams();

        $count = CodTeller::tellerCount($filter_by);
        if ($count === null){
            return $this->response->sendError();
        }
        return $this->response->sendSuccess($count);
    }

    //same implentation as ParcelController
    private function sanitizeWaybillNumbers($waybill_numbers){
        $waybill_number_arr = explode(',', $waybill_numbers);

        $clean_arr = [];
        foreach ($waybill_number_arr as $number){
            $clean_arr[trim(strtoupper($number))] = true;
        }

        return array_keys($clean_arr);
    }
}
