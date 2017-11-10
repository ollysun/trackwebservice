<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 1/20/2017
 * Time: 3:03 PM
 */
class BmcentresController extends ControllerBase
{
    public function getAllAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $staff_id = $this->request->get('staff_id');
        $branch_id = $this->request->get('branch_id');

        $with_staff = $this->request->get('with_staff');
        $with_branch = $this->request->get('with_branch');

        $filter_by = [];
        $fetch_with = [];

        if($staff_id) $filter_by['staff_id'] = $staff_id;
        if($branch_id) $filter_by['branch_id' ] = $branch_id;

        if($with_branch) $fetch_with['with_branch'] = true;
        if($with_staff) $fetch_with['with_staff'] = true;

        return $this->response->sendSuccess(BmCentre::fetchAll(0, 0, $filter_by, $fetch_with, false));
    }

    public function addAction(){
        $staff_id = $this->request->getPost('staff_id');
        $branch_id = $this->request->getPost('branch_id');

        if(in_array(null, [$staff_id, $branch_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $staff = Admin::getById($staff_id);
        $branch = Branch::fetchById($branch_id);

      //  if(!$staff) return $this->response->sendError('Staff not found');
        if(!$branch) return $this->response->sendError('Branch not found');

        if($exists=BmCentre::findFirst(['conditions' => 'branch_id = :branch_id:',
            'bind' =>['branch_id' => $branch_id]])){
            $exists->setStaffId($staff_id);
            if($exists->save())
                return $this->response->sendSuccess();
            //return $this->response->sendError('Link exists');
        }

        $bmCentre = new BmCentre();
        $bmCentre->init(['staff_id' => $staff_id, 'branch_id' => $branch_id]);
        if(!$bmCentre->save()) return $this->response->sendError();
        return $this->response->sendSuccess();
    }

    public function centersForBmAction()
    {
        $this->auth->allowOnly(Role::ADMIN);
        $staff_id = $this->request->getPost('staff_id');
        $centerList=BmCentre::find(['conditions' => 'staff_id = :staff_id:',
            'bind' =>['staff_id' => $staff_id]]);
        return $this->response->sendSuccess($centerList);
    }

}