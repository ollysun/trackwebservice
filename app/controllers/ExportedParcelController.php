<?php

class ExportedParcelController extends ControllerBase {

    public function getAllAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $export_agent_id = $this->request->get('export_agent_id');

        $with_Parcel = $this->request->get('with_Parcel');

        $waybill_number = $this->request->get('waybill_number');

        $filter_by = [];
        $fetch_with = [];

        if($waybill_number) $filter_by['waybill_number'] = $waybill_number;
        if($export_agent_id) $filter_by['export_agent_id' ] = $export_agent_id;

        if($with_Parcel) $fetch_with['with_Parcel'] = true;
        //if($with_staff) $fetch_with['with_staff'] = true;

        return $this->response->sendSuccess(ExportedParcel::fetchAll(0, 0, $filter_by, $fetch_with, false));
    }
}