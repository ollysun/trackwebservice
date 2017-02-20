<?php
/**
 * Created by PhpStorm.
 * User: Kalu
 * Date: 13/02/2017
 * Time: 13:45
 */
class RoleResourceController extends ControllerBase
{
    public function rolesAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $roles=Role::find(array('order'=>'name asc'));
        return $this->response->sendSuccess($roles);
    }
    public function createRoleAction()
    {
        $name = $this->request->getPost('name');
        $permissions = $this->request->getPost('permissions');
        if (in_array(null, [$name])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        if(Role::findFirst(['name = :name:',
            'bind' => [
                'name' => $name
            ]])){
            return $this->response->sendError('The role you want to create already exists.');
        }
        $role = new Role();
        $role->setName($name);
        if ($role->save()) {
            if(!empty($permissions)){
                foreach(json_decode($permissions) as $permission){
                    $permissionModel=new ResourcePermission();
                    $permissionModel->setRoleId($role->getId());
                    $permissionModel->setResourceOperationId($permission);
                    $permissionModel->save();
                }
            }
            return $this->response->sendSuccess(['id' => $role->getId()]);
        }
        return $this->response->sendError();
    }
    public function ResourcesAndOperationsByRoleIdAction()
    {
        $role_id = $this->request->getPost('role_id');
        if (in_array(null, [$role_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $role =  Role::findFirst(['id = :id:',
            'bind' => [
                'id' => $role_id
            ]]);
        $roleArray=[];
        if(!empty($role)){
            $permissions=ResourcePermission::find(['role_id = :role_id:',
                'bind' => [
                    'role_id' => $role->getId()
                ]]);
            $roleArray["role"]=$role;
           /* $operations=[];
            foreach($permissions as $permission){
                $resourceOperation=ResourceOperation::findFirst(['id = :id:',
                    'bind' => [
                        'id' => $permission->getResourceOperationId()
                    ]]);
                $operations[]=$resourceOperation;
            }*/
            $roleArray["permissions"]=$permissions;

            $resourcesAndOperations=[];
            $resources=Resource::find(array('order'=>'name asc'));
            foreach($resources as $resource){
                $itemsArray=[];
                $itemsArray["resource"]=$resource;
                $itemsArray["operations"]=ResourceOperation::find(array("resource_id=:resource_id:",'order'=>'name asc',
                    'bind'=>["resource_id"=>$resource->getId()]));
                $resourcesAndOperations[]=$itemsArray;
            }
            $roleArray["operations"]=$resourcesAndOperations;
            return $this->response->sendSuccess($roleArray);

        }
        return $this->response->sendError();
    }
    public function updateRolePermissionsAction()
    {
        $role_id = $this->request->getPost('role_id');
        $permissions = $this->request->getPost('permissions');
        if (in_array(null, [$role_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $arrayOfPermissionsFromDb= ResourcePermission::find(['role_id = :role_id:',
            'bind' => [
                'role_id' => $role_id
            ],'columns' => 'resource_operation_id']);
        $permissionsFromDb=[];
        foreach($arrayOfPermissionsFromDb as $permissionFromDb){
            $permissionsFromDb[]=$permissionFromDb->resource_operation_id;
        }
        $permissions = json_decode($permissions);
        if(!empty($permissions)){
                //get permissions to add
                foreach($permissions as $permissionSent){
                    if(!in_array($permissionSent,$permissionsFromDb)){
                        $permissionModel=new ResourcePermission();
                       $permissionModel->setRoleId($role_id);
                       $permissionModel->setResourceOperationId($permissionSent);
                       $permissionModel->save();
                   }
                }
                //get permissions to remove
               $this::deletedRemovedPermissions($permissionsFromDb,$permissions,$role_id);
            return $this->response->sendSuccess($role_id);
        }else{
            //Remove permissions of all are deselected from the form
if($this::deletedRemovedPermissions($permissionsFromDb,$permissions,$role_id)){
    return $this->response->sendSuccess($role_id);
}
           }
        return $this->response->sendError();
    }
private static function deletedRemovedPermissions($permissionsFromDb,$permissionsPosted,$role_id){
    foreach($permissionsFromDb as $permissionDb){
        if(!in_array($permissionDb,$permissionsPosted)){
            $permissionModel= ResourcePermission::findFirst(["resource_operation_id=:id: and role_id=:role_id:",
                "bind"=>["id"=>$permissionDb,"role_id"=>$role_id]]);
            $permissionModel->delete();
        }
    }
    return true;
}
    public function updateRoleAction()
    {
        $roleId = $this->request->getQuery('role_id');
        $role=Role::findFirst(['id = :id:',
            'bind' => [
                'id' => $roleId
            ]]);
        if(empty($role)){
            return $this->response->sendError('The role was not found.');
        }
        return $this->response->sendSuccess($role);
    }
    public function updateRolePostAction()
    {
        $newName = $this->request->getPost('role_name');
        $roleId = $this->request->getPost('role_id');
        if (in_array(null, [$newName,$roleId])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $role=Role::findFirst(['id = :id:',
            'bind' => [
                'id' => $roleId
            ]]);
        if(empty($role)){
            return $this->response->sendError('The role you want to edit was not found.');
        }
        $role->setName($newName);
        if($role->save()){
            return $this->response->sendSuccess(["role"=>$role,"message"=>'Role successfully updated.']);
        }
        return $this->response->sendError("Error occurred.");
    }
    public function deleteRoleAction()
    {
        $roleId = $this->request->getPost('role_id');
        if (in_array(null, [$roleId])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $role=Role::findFirst(['id = :id:',
            'bind' => [
                'id' => $roleId
            ]]);
        if(empty($role)){
            return $this->response->sendError('The role you want to delete was not found.');
        }
        if($role->delete()){
            return $this->response->sendSuccess('Role successfully removed.');
        }
        return $this->response->sendError("Error occurred.");
    }


    //RESOURCES
    public function resourcesAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $resources=Resource::find(array('order'=>'name asc'));
        return $this->response->sendSuccess($resources);
    }
    public function resourcesAndOperationsAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $resourcesArray=[];
        $resources=Resource::find(array('order'=>'name asc'));
       foreach($resources as $resource){
           $items=[];
           $items["resource"]=$resource;
           $items["operations"]=$resource->ResourceOperation;
           $resourcesArray[]=$items;
       }
        return $this->response->sendSuccess($resourcesArray);
    }

    public function createresourceAction()
    {
        $name = $this->request->getPost('name');
        if (in_array(null, [$name])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        if(Resource::findFirst(['name = :name:',
            'bind' => [
                'name' => $name
            ]])){
            return $this->response->sendError('The role you want to create already exists.');
        }
        $role = new Resource();
        $role->setName($name);
        if ($role->save()) {
            return $this->response->sendSuccess(['id' => $role->getId()]);
        }
        return $this->response->sendError();
    }
    public function updateresourcepostAction()
    {
        $newName = $this->request->getPost('resource_name');
        $resourceId = $this->request->getPost('resource_id');
        if (in_array(null, [$newName,$resourceId])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $resource=Resource::findFirst(['id = :id:',
            'bind' => [
                'id' => $resourceId
            ]]);
        if(empty($resource)){
            return $this->response->sendError('The role you want to edit was not found.');
        }
        $resource->setName($newName);
        if($resource->save()){
            return $this->response->sendSuccess(["resource"=>$resource,"message"=>'Resource successfully updated.']);
        }
        return $this->response->sendError("Error occurred.");
    }
    public function deleteresourceAction()
    {
        $resourceId = $this->request->getPost('resource_id');
        if (in_array(null, [$resourceId])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $resource=Resource::findFirst(['id = :id:',
            'bind' => [
                'id' => $resourceId
            ]]);
        if(empty($resource)){
            return $this->response->sendError('The role you want to delete was not found.');
        }
        if($resource->delete()){
            return $this->response->sendSuccess('Role successfully removed.');
        }
        return $this->response->sendError("Error occurred.");
    }

    //OPERATIONS
    public function operationsAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $items=ResourceOperation::find(array('order'=>'name asc'));
        return $this->response->sendSuccess($items);
    }
    public function operationsByResourceAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $resource_id = $this->request->get('resource');
        if (in_array(null, [$resource_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $itemsArray=[];
        $itemsArray["operations"]=ResourceOperation::find(array("resource_id=:resource_id:",'order'=>'name asc',
            'bind'=>["resource_id"=>$resource_id]));
        $itemsArray["resources"]=Resource::find();
        return $this->response->sendSuccess($itemsArray);
    }
    public function createoperationAction()
    {
        $resource_id = $this->request->getPost('resource_id');
        $name = $this->request->getPost('name');
         if (in_array(null, [$name,$resource_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        if(ResourceOperation::findFirst(['name = :name: and resource_id=:resource_id:',
            'bind' => [
                'name' => $name,
                'resource_id'=>$resource_id
            ]])){
            return $this->response->sendError('The operation you want to create already exists for the resource.');
        }
        $model= new ResourceOperation();
        $model->setName($name);
        $model->setResourceId($resource_id);
        if ($model->save()) {
            return $this->response->sendSuccess(['id' => $model->getId()]);
        }
        return $this->response->sendError();
    }
    public function updateoperationpostAction()
    {
        $newName = $this->request->getPost('operation_name');
        $itemId = $this->request->getPost('operation_id');
        $resource_id = $this->request->getPost('resource_id');
        if (in_array(null, [$newName,$itemId,$resource_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $model=ResourceOperation::findFirst(['id = :id: and resource_id=:resource_id:',
            'bind' => [
                'id' => $itemId,
                'resource_id' => $resource_id,
            ]]);
        if(empty($model)){
            return $this->response->sendError('The operation you want to edit was not found.');
        }
        $model->setName($newName);
        $model->setResourceId($resource_id);
        if($model->save()){
            return $this->response->sendSuccess(["resource"=>$model,"message"=>'Operation successfully updated.']);
        }
        return $this->response->sendError("Error occurred.");
    }
    public function deleteoperationAction()
    {
        $itemId = $this->request->getPost('operation_id');
        if (in_array(null, [$itemId])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $model=Resource::findFirst(['id = :id:',
            'bind' => [
                'id' => $itemId
            ]]);
        if(empty($model)){
            return $this->response->sendError('The operation you want to delete was not found.');
        }
        if($model->delete()){
            return $this->response->sendSuccess('Operation successfully removed.');
        }
        return $this->response->sendError("Error occurred.");
    }
    public function addUserToRolesAction()
    {
        $itemId = $this->request->getPost('operation_id');
        if (in_array(null, [$itemId])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $model=Resource::findFirst(['id = :id:',
            'bind' => [
                'id' => $itemId
            ]]);
        if(empty($model)){
            return $this->response->sendError('The operation you want to delete was not found.');
        }
        if($model->delete()){
            return $this->response->sendSuccess('Operation successfully removed.');
        }
        return $this->response->sendError("Error occurred.");
    }
//
}