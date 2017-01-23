<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 1/23/2017
 * Time: 11:20 AM
 */
class RemittanceController extends ControllerBase
{
    public function getDueParcelsAction(){
        $company_id = $this->request->getQuery('company_id');
        $start_delivery_date = $this->request->getQuery('start_delivery_date');
        $end_delivery_date = $this->request->getQuery('end_delivery_date');
        $send_all = $this->request->getQuery('send_all', false);
        $with_total_count = $this->request->getQuery('with_total_count');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_by = [];
        if($company_id) $filter_by['company_id'] = $company_id;
        if($start_delivery_date) $filter_by['start_delivery_date'] = $start_delivery_date;
        if($end_delivery_date) $filter_by['end_delivery_date'] = $end_delivery_date;
        if($send_all) $filter_by['send_all'] = true;

        $parcels = Remittance::fetchUnPaidParcels($offset, $count, $filter_by);
        if($with_total_count){
            $count = Remittance::unpaidParcelCount($filter_by);
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

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $with_parcel = $this->request->getQuery('with_parcel');
        $with_company = $this->request->getQuery('with_company');
        $with_payer = $this->request->getQuery('with_payer');

        $filter_by = [];

        if($company_registration_number) $filter_by['company_registration_number'] = 1;
        if($start_date) $filter_by['start_date'] = 1;
        if($end_date) $filter_by['end_date'] = 1;
        if($min_amount) $filter_by['min_amount'] = 1;
        if($max_amount) $filter_by['max_amount'] = 1;
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

    public function saveRemittanceAction(){
         $waybill_number = $this->request->getQuery('waybill_number');
         $payer = $this->request->getQuery('payer_id');
         $company_registration_number = $this->request->getQuery('company_registration_number');

         if(in_array(null, [$waybill_number, $payer, $company_registration_number]))
             return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);

         $parcel = Parcel::getByWaybillNumber($waybill_number);
         if(!$parcel)
             return $this->response->sendError('Invalid waybill number');

         $remittance = new Remittance();
         $remittance->init($waybill_number, $parcel->getCashOnDeliveryAmount(), $company_registration_number, $payer);
         if($remittance->save()){
             return $this->response->sendSuccess();
         }else{
             return $this->response->sendError('Error in saving changes '. implode(', ', $remittance->getMessages()));
         }
    }
}