<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2/17/2017
 * Time: 1:48 PM
 */
class ExportController extends ControllerBase
{
    public function getAgentsAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER]);
        $agents = ExportAgent::find();
        return $this->response->sendSuccess($agents);
    }

    public function getExportedParcelsAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER]);
        $agent_id = $this->request->get('agent_id');
        $start_created_date = $this->request->get('start_created_date');
        $end_created_date = $this->request->get('end_created_date');
        $waybill_number = $this->request->get('waybill_number');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);

        $filter_by = [];
        if($agent_id) $filter_by['agent_id' ] = $agent_id;
        if($start_created_date) $filter_by['start_created_date'] = $start_created_date;
        if($end_created_date) $filter_by['end_created_date'] = $end_created_date;
        if($waybill_number) $filter_by['waybill_number'] = $waybill_number;

        $parcels = ExportedParcel::fetchAll($offset, $count, $filter_by, [], $paginate);
        if($paginate){
            $total_count = ExportedParcel::countParcels($filter_by);
            return $this->response->sendSuccess(['total' => $total_count, 'parcels' => $parcels]);
        }
        return $this->response->sendSuccess($parcels);
    }

    public function getUnmappedExportedParcels(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER]);
        $start_created_date = $this->request->get('start_created_date');
        $end_created_date = $this->request->get('end_created_date');
        $waybill_number = $this->request->get('waybill_number');

        $filter_by = [];
        if($start_created_date) $filter_by['start_created_date'] = $start_created_date;
        if($end_created_date) $filter_by['end_created_date'] = $end_created_date;
        if($waybill_number) $filter_by['waybill_number'] = $waybill_number;
    }
}