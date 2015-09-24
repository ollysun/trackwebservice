<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class RouteController extends ControllerBase
{
    /**
     * Gets All Routes
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getAllAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $branchId = $this->request->getQuery('branch_id');
        return $this->response->sendSuccess(Route::getAll($branchId));
    }
}