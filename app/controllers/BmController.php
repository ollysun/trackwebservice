<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 10/30/2016
 * Time: 12:13 PM
 */

class BmController extends ControllerBase
{
    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);

        $region_id = $this->request->getQuery('region_id');

        $filter_by = [];
        if($region_id){
            $filter_by['region_id'] = $region_id;
        }

        $bms = BusinessManager::getAll($offset, $count, $filter_by, $paginate);
        $total_count = BusinessManager::getCount($filter_by);

        foreach($bms as $bm){
            $fetch_with=[];
            $fetch_with['with_branch']=1;
            $filter_by = [];
            $filter_by['staff_id'] = $bm->staff_id;
            $bmc[$bm->staff_id]=BmCentre::fetchAll($offset, $count, $filter_by, $fetch_with, $paginate = false);
        }

        return $this->response->sendSuccess([
            'bmc'=>$bmc,
            'business_managers' => $bms, 'total_count' => $total_count]);
    }

    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $staff_id = $this->request->getPost('staff_id');
        $region_id = $this->request->getPost('region_id');
        $business_zone_id = $this->request->getPost('business_zone_id');

        if(in_array(null, [$staff_id, $region_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        /** @var Admin $admin */
        $admin = Admin::findFirstByStaff_id($staff_id);
        if(!$admin){
            return $this->response->sendError('Invalid staff Id');
        }

        /** @var Region $region */
        $region = Region::findFirstById($region_id);
        if(!$region){
            return $this->response->sendError('Invalid region Id');
        }

        if(BusinessManager::findFirstByStaff_id($staff_id)){
            return $this->response->sendError('This staff has already been added');
        }

        $bm = new BusinessManager();
        $bm->init(['status' => Status::ACTIVE, 'staff_id' => $staff_id, 'region_id' => $region_id,
            'region_name' => $region->getName(), 'name' => $admin->getFullname(), 'business_zone_id' => $business_zone_id]);
        if($bm->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError('Error in saving BM');
    }

    public function changeregionAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $staff_id = $this->request->getPost('staff_id');
        $region_id = $this->request->getPost('region_id');

        if(in_array(null, [$staff_id, $region_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $admin = Admin::findFirstByStaff_id($staff_id);
        if(!$admin){
            return $this->response->sendError('Invalid staff Id');
        }

        /** @var Region $region */
        $region = Region::findFirstById($region_id);
        if(!$region){
            return $this->response->sendError('Invalid region Id');
        }

        /** @var BusinessManager $bm */
        $bm = BusinessManager::findFirstByStaff_id($staff_id);
        if(!$bm){
            return $this->response->sendError('The provided staff is not a BM');
        }

        $bm->setRegionId($region_id);
        $bm->setRegionName($region->getName());
        if($bm->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError('Error in saving BM');

    }

}