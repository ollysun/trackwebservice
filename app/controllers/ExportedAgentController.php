<?php

class ExportedAgentController extends ControllerBase {

    public function getAllAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $parcel_id = $this->request->get('parcel_id');
        $export_agent_id = $this->request->get('export_agent_id');

        $with_exported_parcel = $this->request->get('with_ExportedParcel');
        //$with_branch = $this->request->get('with_branch');

        $filter_by = [];
        $fetch_with = [];

        if($parcel_id) $filter_by['parcel_id'] = $export_agent_id;
        if($export_agent_id) $filter_by['export_agent_id' ] = $export_agent_id;

        if($with_exported_parcel) $fetch_with['with_exported_parcel'] = true;
        //if($with_staff) $fetch_with['with_staff'] = true;

        return $this->response->sendSuccess(ExportAgent::fetchAll(0, 0, $filter_by, $fetch_with, false));
    }
}