<?php

class ExportedParcelController extends ControllerBase {

    public function getAllAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $agent_id = $this->request->get('agent_id');

        $with_Parcel = $this->request->get('with_Parcel');

        $waybill_number = $this->request->get('waybill_number');
        $with_total_count = $this->request->get('with_total_count');

        $filter_by = [];
        $fetch_with = [];

        if($waybill_number) $filter_by['waybill_number'] = $waybill_number;
        if($agent_id) $filter_by['agent_id' ] = $agent_id;

        if($with_Parcel) $fetch_with['with_Parcel'] = true;
        //if($with_staff) $fetch_with['with_staff'] = true;

        $result = ExportedParcel::fetchAll(0, 0, $filter_by, $fetch_with, false);
        if($with_total_count){
            $count = ExportedParcel::countExportedParcels($filter_by);
            $result = ['total_count' => $count, 'parcels' => $result];
        }

        return $this->response->sendSuccess($result);
    }
    public function getAllUnassignedAction(){

        $this->auth->allowOnly(Role::ADMIN);

        $start_created_date = $this->request->get('start_created_date');

        $with_Parcel = $this->request->get('with_Parcel');

        $end_created_date = $this->request->get('end_created_date');
        $with_total_count = $this->request->get('with_total_count');

        $filter_by = [];
        $fetch_with = [];

        if($start_created_date) $filter_by['start_created_date'] = $start_created_date;
        if($end_created_date) $filter_by['end_created_date' ] = $end_created_date;

        if($with_Parcel) $fetch_with['with_Parcel'] = true;
        //if($with_staff) $fetch_with['with_staff'] = true;

        $result = ExportedParcel::fetchAllUnlinkedParcels(0, 0, $filter_by, false);
        if($with_total_count){
            $count = ExportedParcel::countAllUnlinkedParcels($filter_by);
            $result = ['total_count' => $count, 'parcels' => $result];
        }
        return $this->response->sendSuccess($result);
    }

    public function addAction()
    {
        $parcel_id = $this->request->getPost('parcel_id');
        $agent_id = $this->request->getPost('agent_id');
        $agent_tracking_number= $this->request->getPost('agent_tracking_number');
       // return $this->response->sendSuccess($this->request->getPost());
        if(in_array(null,[$parcel_id,$agent_id, $agent_tracking_number])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $expotedParcel = new ExportedParcel();
        $expotedParcel->setExportAgentId($agent_id);
        $expotedParcel->setParcelId($parcel_id);
        $expotedParcel->setAgentTrackingNumber($agent_tracking_number);
        if($expotedParcel->save())return $this->response->sendSuccess();
        return $this->response->sendError();
    }
}