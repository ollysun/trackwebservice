<?php


class AdminController extends ControllerBase
{
    public function registerAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::SUPER_ADMIN]);
        //todo: validate phone number


        $role_id = $this->request->getPost('role_id');
        $branch_id = $this->request->getPost('branch_id');
        $staff_id = $this->request->getPost('staff_id');
        $email = $this->request->getPost('email');
        $fullname = $this->request->getPost('fullname');
        $phone = $this->request->getPost('phone');
        $password = $this->auth->generateToken(6);

        if (in_array(null, array($email, $role_id, $staff_id, $fullname, $password, $branch_id))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $email = strtolower(trim($email));
        $staff_id = strtoupper(trim($staff_id));
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            return $this->response->sendError(ResponseMessage::INVALID_EMAIL);
        }

        $branch = Branch::fetchById($branch_id);
        if ($branch == false) {
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        if ($role_id == Role::SUPER_ADMIN && $this->auth->getUserType() != Role::SUPER_ADMIN) {
            return $this->response->sendAccessDenied();
        }

        if ($role_id == Role::SWEEPER) {
            if (!($branch->getBranchType() == BranchType::HUB OR $branch->getBranchType() == BranchType::EC)) {
                return $this->response->sendError(ResponseMessage::SWEEPER_ONLY_TO_HUB_OR_EC);
            }
        }

        $admin_details = Admin::fetchByIdentifier($email, $staff_id);
        if ($admin_details != false) {
            if ($admin_details->userAuth->getEmail() == $email) {
                return $this->response->sendError(ResponseMessage::EXISTING_EMAIL);
            } else if ($admin_details->admin->getStaffId() == $staff_id) {
                return $this->response->sendError(ResponseMessage::EXISTING_STAFF_ID);
            }
        }

        $admin = new Admin();
        $check = $admin->createWithAuth($branch_id, $role_id, $staff_id, $email, $password, $fullname, $phone);
        if ($check) {
            EmailMessage::send(
                EmailMessage::USER_ACCOUNT_CREATION,
                [
                    'name' => $fullname,
                    'email' => $email,
                    'password' => $password,
                    'link' => $this->config->fe_base_url . '/site/changePassword?ican=' . md5($admin->getId()) . '&salt=' . $admin->getId(),
                    'year' => date('Y')
                ],
                'Courier Plus',
                $email,
                EmailMessage::DEFAULT_FROM_EMAIL,
                ['cc' => ['itsupport@courierplus-ng.com' => 'CourierPlus IT Support']]
            );
            return $this->response->sendSuccess(['id' => $admin->getId()]);
        }
        return $this->response->sendError();
    }

    public function editAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        //todo: validate phone number
        /*
         * array(8) {
  ["admin_id"]=>
  string(2) "52"
  ["role_id"]=>
  string(2) "10"
  ["branch_id"]=>
  string(3) "110"
  ["staff_id"]=>
  string(8) "CSL/0360"
  ["email"]=>
  string(26) "a.adesi@courierplus-ng.com"
  ["fullname"]=>
  string(12) "aminat adesi"
  ["phone"]=>
  string(13) "2348163781325"
  ["status"]=>
  string(1) "1"
}
*/

        $admin_id = $this->request->getPost('admin_id');
        $role_id = $this->request->getPost('role_id');
        $role_ids = $this->request->getPost('role_ids') | [$role_id];
        $branch_id = $this->request->getPost('branch_id');
        $staff_id = $this->request->getPost('staff_id');
        $email = $this->request->getPost('email');
        $fullname = $this->request->getPost('fullname');
        $phone = $this->request->getPost('phone');
        $status = $this->request->getPost('status');

        if (in_array(null, array($email, $role_id, $staff_id, $fullname, $status, $admin_id, $branch_id,$role_ids))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $email = strtolower(trim($email));
        $staff_id = strtoupper(trim($staff_id));
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            return $this->response->sendError(ResponseMessage::INVALID_EMAIL);
        }

        $branch = Branch::fetchById($branch_id);
        if ($branch == false) {
            return $this->response->sendError(ResponseMessage::BRANCH_NOT_EXISTING);
        }

        if ($role_id == Role::SWEEPER) {
            if (!($branch->getBranchType() == BranchType::HUB OR $branch->getBranchType() == BranchType::EC)) {
                return $this->response->sendError(ResponseMessage::SWEEPER_ONLY_TO_HUB_OR_EC);
            }
        }

        $admin_details = Admin::fetchByIdentifier($email, $staff_id, $admin_id);
        if ($admin_details != false) {
            if ($admin_details->userAuth->getEmail() == $email) {
                return $this->response->sendError(ResponseMessage::EXISTING_EMAIL);
            } else if ($admin_details->admin->getStaffId() == $staff_id) {
                return $this->response->sendError(ResponseMessage::EXISTING_STAFF_ID);
            }
        }
        $admin = Admin::findFirst(['id = :id:', 'bind' => ['id' => $admin_id]]);
        if ($admin != false) {
            $admin->changeDetails($branch_id, $role_id, $staff_id, $fullname, $phone);
            if ($admin->save()) {
                $auth = UserAuth::findFirst(['id = :id:', 'bind' => ['id' => $admin->getUserAuthId()]]);
                $auth->changeDetails($email, $status);
                if ($auth->save()) {
                $rolesOfUser=UserRoles::find(["user_id=:user_id:",'bind' => ['user_id' => $admin->getUserAuthId()],"columns"=>"user_id,role_id"]);
                    $rolesOfUserFromDb=[];
                     foreach($rolesOfUser as $roleOfUser){
                         $rolesOfUserFromDb[]=$roleOfUser->getRoleId();
                    }
                    foreach(json_decode($role_ids) as $role) {
                        if(!in_array($role,$rolesOfUserFromDb)){
                          $userRole=new UserRoles();
                          $userRole->setRoleId($role);
                          $userRole->setUserId($admin->getUserAuthId());
                          $userRole->save();
                      }
      }
                    foreach($rolesOfUserFromDb as $roleDb) {
                        if(!in_array($roleDb,json_decode($role_ids) )){
                            UserRoles::findFirst(["user_id=:user_id: and role_id=:role_id:",'bind' => ['user_id' => $admin->getUserAuthId(),'role_id' =>$roleDb]])
                            ->delete();
                        }
                    }
                    return $this->response->sendSuccess();
                }
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::STAFF_DOES_NOT_EXIST);
    }

    public function getAllAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);


        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $role_id = $this->request->getQuery('role_id');
        $branch_id = $this->request->getQuery('branch_id');
        $status = $this->request->getQuery('status');
        $staff_id = $this->request->getQuery('staff_id');
        $email = $this->request->getQuery('email');

        $filter_by = [];
        if (!is_null($role_id)) {
            $filter_by['role_id'] = $role_id;
        }
        if (!is_null($branch_id)) {
            $filter_by['branch_id'] = $branch_id;
        }
        if (!is_null($status)) {
            $filter_by['status'] = $status;
        }
        if (!is_null($staff_id)) {
            $filter_by['staff_id'] = $staff_id;
        }
        if (!is_null($email)) {
            $filter_by['email'] = $email;
        }

        return $this->response->sendSuccess(Admin::fetchAll($offset, $count, $filter_by));
    }

    public function getOneAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::GROUNDSMAN]);

        $staff_id = $this->request->getQuery('staff_id');
        $email = $this->request->getQuery('email');
        $id = $this->request->getQuery('id');

        $filter_by = [];
        if (!is_null($staff_id)) {
            $filter_by['staff_id'] = $staff_id;
        } else if (!is_null($email)) {
            $filter_by['email'] = $email;
        } else if (!is_null($id)) {
            $filter_by['id'] = $id;
        }

        $admin_data = Admin::fetchOne($filter_by);
        if ($admin_data != false) {
            return $this->response->sendSuccess($admin_data);
        }
        return $this->response->sendError(ResponseMessage::RECORD_DOES_NOT_EXIST);
    }
}