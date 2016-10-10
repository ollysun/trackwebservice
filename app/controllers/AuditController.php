<?php

/**
 * Created by Ademu Anthony.
 * User: ELACHI
 * Date: 6/1/2016
 * Time: 7:45 PM
 */
class AuditController extends ControllerBase
{

    /**
     * @author Ademu Anthony <mail@ademuanthony.com>
     * @return $this
     */
    public function getAllAuditAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);

        $username = $this->request->getQuery('username');
        $service = $this->request->getQuery('service');
        $action = $this->request->getQuery('action');
        $ip_address = $this->request->getQuery('ip_address');
        $start_time = $this->request->getQuery('start_time');
        $end_time = $this->request->getQuery('end_time');

        $filter_by = [];
        if (!is_null($username)) {
            $filter_by['username'] = $username;
        }
        if (!is_null($service)) {
            $filter_by['service'] = $service;
        }
        if (!is_null($action)) {
            $filter_by['action'] = $action;
        }
        if (!is_null($ip_address)) {
            $filter_by['ip_address'] = $ip_address;
        }
        if (!is_null($start_time)) {
            $filter_by['start_time'] = $start_time;
        }
        if (!is_null($end_time)) {
            $filter_by['end_time'] = $end_time;
        }

        $audit_trail = Audit::fetchAll($offset, $count, $filter_by, $paginate);
        $total_count = Audit::logCount($filter_by);


        return $this->response->sendSuccess(['audit_trails' => $audit_trail, 'total_count' => $total_count]);
    }

}