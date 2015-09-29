<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class RouteController extends ControllerBase
{

    /**
     * Creates Route
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function createAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $branchId = $this->request->getPost('branch_id');
        $name = $this->request->getPost('name');

        if (in_array(null, array($name, $branchId))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        // Validate Branch
        $branch = Branch::findFirst($branchId);
        if(!$branch) {
            return $this->response->sendError(ResponseMessage::INVALID_BRANCH);
        }

        $route = Route::createRoute($name, $branchId);
        if(!$route) {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_ROUTE);
        }
        return $this->response->sendSuccess($route);
    }

    /**
     * Gets All Routes
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getAllAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN]);

        $branchId = $this->request->getQuery('branch_id');
        return $this->response->sendSuccess(Route::getAll($branchId));
    }
}