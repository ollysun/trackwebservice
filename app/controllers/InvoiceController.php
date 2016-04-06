<?php
use Phalcon\Mvc\Model\Resultset;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class InvoiceController extends ControllerBase
{

    /**
     * Adds a new invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $postData = $this->request->getJsonRawBody();

        $invoiceRequestValidator = new InvoiceValidation($postData);
        if (!$invoiceRequestValidator->validate()) {
            return $this->response->sendError($invoiceRequestValidator->getMessages());
        }

        // Generate Invoice Number
        $postData->invoice_number = Invoice::generateInvoiceNumber();
        $invoice = Invoice::generate((array)$postData);

        if ($invoice) {
            // Add Invoice Parcels
            if (!InvoiceParcel::validateParcels($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::ONE_OF_THE_PARCEL_DOES_NOT_EXIST);
            }

            if (!InvoiceParcel::validateInvoiceParcel($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::INVOICE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS);
            }

            InvoiceParcel::addParcels($postData->invoice_number, $postData->parcels);

            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_INVOICE);
    }

    /**
     * Get paginated list of invoices
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getAllAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $with_total_count = $this->request->getQuery('with_total_count', null, null);

        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());
        $filter_by = $this->request->getQuery();

        $invoices = Invoice::fetchAll($offset, $count, $fetch_with, $filter_by);

        if (!empty($with_total_count)) {
            $data = [
                'total_count' => Invoice::getTotalCount($filter_by),
                'invoices' => $invoices
            ];
        } else {
            $data = $invoices;
        }

        return $this->response->sendSuccess($data);
    }

    /**
     * Get's the details of an Invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return $this
     */
    public function getAction()
    {
        $filter_by = $this->request->getQuery();
        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());
        $invoice = Invoice::fetchOne($filter_by, $fetch_with);
        if ($invoice) {
            return $this->response->sendSuccess($invoice);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    /**
     * Get's the parcels attached to an invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getInvoiceParcelsAction()
    {
        $filter_by = $this->request->getQuery();
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());
        $invoice = Invoice::fetchOne($filter_by, []);
        if ($invoice) {
            return $this->response->sendSuccess(InvoiceParcel::fetchAll($offset, $count, $fetch_with, $filter_by));
        }
        return $this->response->sendError(ResponseMessage::INVOICE_DOES_NOT_EXISTS);
    }

    /**
     * bulk Invoice printing task
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     */
    public function createBulkInvoiceAction()
    {
        $postData = $this->request->getJsonRawBody();
        $validation = new BulkInvoiceCreationValidation($postData);

        if (!$validation->validate()) {
            return $this->response->sendError($validation->getMessages());
        }

        $postData->created_by = $this->auth->getPersonId();
        $postData->creator = $this->auth->getData();

        $worker = new InvoicePrintingWorker();
        $job_id = $worker->addJob(json_encode($postData));
        if (!$job_id) {
            return $this->response->sendError('Could not generate bulk invoice job. Please try again');
        }
        return $this->response->sendSuccess($job_id);
    }


    /**
     * Get Bulk Invoice Tasks
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getBulkInvoiceTasksAction()
    {
        /** @var Resultset $tasks */
        $tasks = Job::findByQueue(InvoicePrintingWorker::QUEUE_BULK_INVOICE_PRINTING);
        return $this->response->sendSuccess($tasks->toArray());
    }

    /**
     * Get Bulk Invoice Task
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getBulkInvoiceTaskAction()
    {
        $task_id = $this->request->get('task_id', null, false);
        if (!$task_id) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        /** @var Job $task */
        $task = Job::findFirst($task_id);
        if (!$task) {
            return $this->response->sendError('Task not found');
        }

        /** @var Resultset $taskDetails */
        $taskDetails = BulkInvoiceJobDetails::findByJobId($task->id);
        $task = $task->toArray();
        $task['details'] = $taskDetails->toArray();
        return $this->response->sendSuccess($task);
    }
}