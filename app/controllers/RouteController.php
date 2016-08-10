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
        if (!$branch) {
            return $this->response->sendError(ResponseMessage::INVALID_BRANCH);
        }

        $route = Route::createRoute($name, $branchId);
        if (!$route) {
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
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN, Role::COMPANY_ADMIN, Role::COMPANY_OFFICER, Role::SALES_AGENT]);

        $branchId = $this->request->getQuery('branch_id');
        $with_total_count = $this->request->getQuery('with_total_count');
        $send_all = $this->request->getQuery('send_all');
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $filter = $this->request->getQuery('search');

        $routes = Route::getAll($branchId, $offset, $count, $send_all, $filter);

        $result = [];
        if ($with_total_count != null) {
            $count = Route::routeCount($branchId, $filter);
            $result = [
                'total_count' => $count,
                'routes' => $routes
            ];
        } else {
            $result = $routes;
        }

        return $this->response->sendSuccess($result);
    }

    /**
     * Updates the details of a route
     * @author Olawale Lawal <walee@cottacush.com>
     */
    public function editAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);

        $route_id = $this->request->getPost('route_id');
        $name = $this->request->getPost('name');
        $branch_id = $this->request->getPost('branch_id');

        if (in_array(null, array($route_id, $name, $branch_id))) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        // Validate Branch
        $branch = Branch::findFirst($branch_id);
        if (!$branch) {
            return $this->response->sendError(ResponseMessage::INVALID_BRANCH);
        }

        $route = Route::findFirst($route_id);
        if ($route == false) {
            return $this->response->sendError(ResponseMessage::INVALID_ROUTE);
        }

        $route->editDetails($name, $branch_id);
        if ($route->save()) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::UNABLE_TO_EDIT_ROUTE);
    }
}