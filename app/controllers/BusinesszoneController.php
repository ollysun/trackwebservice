<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 12/26/2016
 * Time: 11:05 PM
 */
class BusinessZoneController extends ControllerBase
{
    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);
        $with_region = $this->request->getQuery('with_region');

        $region_id = $this->request->getQuery('region_id');

        $filter_by = [];
        $fetch_with = [];

        if($region_id){
            $filter_by['region_id'] = $region_id;
        }

        if($with_region){
            $fetch_with['with_region'] = 1;
        }

        $bms = BusinessZone::fetchAll($offset, $count, $filter_by, $fetch_with, $paginate);
        $total_count = BusinessZone::getCount($filter_by);


        return $this->response->sendSuccess(['business_zones' => $bms, 'total_count' => $total_count]);
    }

    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $region_id = $this->request->getPost('region_id');
        $description = $this->request->getPost('description');

        if(in_array(null, [$name, $region_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        /** @var Region $region */
        $region = Region::findFirstById($region_id);
        if(!$region){
            return $this->response->sendError('Invalid region Id');
        }

        //name duplicate check
        if(BusinessZone::findFirstByName($name)){
            return $this->response->sendError('A zone with the same name exists');
        }


        $bm = new BusinessZone();
        $bm->init(['name' => $name, 'status' => Status::ACTIVE, 'region_id' => $region_id, 'description' => $description]);
        if($bm->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError('Error in saving Business Zone');
    }

    public function deleteAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $id = $this->request->getPost('id');
        if(!$id) return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        $zone = BusinessZone::findFirst($id);
        if(!$zone) return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
        $zone->delete();
        return $this->response->sendSuccess();
    }
}