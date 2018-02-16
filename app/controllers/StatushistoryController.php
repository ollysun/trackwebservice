<?php

class StatushistoryController extends \Phalcon\Mvc\Controller
{

    public function getAllHistoryAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);

        $waybill = $this->request->getQuery('waybill_number');
        if (is_null($waybill)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $filter_by = [];
        if (!is_null($waybill)) {
            $filter_by['waybill_number'] = $waybill;
        }

        $status_history = StatusHistory::fetchAll($offset, $count, $filter_by, $paginate);

        return $this->response->sendSuccess([$status_history]);
    }

}

