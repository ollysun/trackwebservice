<?php
use Phalcon\Mvc\Model\Resultset;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class InvoiceController extends ControllerBase
{
    protected function createInvoice($data){
        $invoiceRequestValidator = new InvoiceValidation($data);
        if (!$invoiceRequestValidator->validate()) {
            return $this->response->sendError($invoiceRequestValidator->getMessages());
        }

        // Generate Invoice Number
        $data->invoice_number = Invoice::generateInvoiceNumber($data->company_id);

        $invoice = Invoice::generate((array)$data);

        if ($invoice) {
            // Add Invoice Parcels
            if (!InvoiceParcel::validateParcels($data->parcels)) {
                return ['success' => false, 'message' => ResponseMessage::ONE_OF_THE_PARCEL_DOES_NOT_EXIST];
            }

            if (!InvoiceParcel::validateInvoiceParcel($data->parcels)) {
                return ['success' => false, 'message' => ResponseMessage::INVOICE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS];
            }

            InvoiceParcel::addParcels($data->invoice_number, $data->parcels);

            return ['success' => true];
        }
        return ['success' => false, 'message' => ResponseMessage::UNABLE_TO_CREATE_INVOICE];
    }

    /**
     * Adds a new invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::BILLING, Role::FINANCE]);
        $postData = $this->request->getJsonRawBody();

        $result = $this->createInvoice($postData);
        return $result['success']?$this->response->sendSuccess():$this->response->sendError($result['message']);
    }

    /**
     * @param $from_date
     * @param $to_date
     * @param Company $company
     */
    public function createInvoiceForCompany($from_date, $to_date, $company){

        $filter_by = [/*'payment_type' => '1', */'start_created_date' => $from_date,
            'end_created_date' => $to_date, 'company_id' => $company->getId(), 'send_all' => 1];
        $parcels = Parcel::fetchAll(0, 1000, $filter_by, []);
        if(!$parcels) return;

        $invoiceData = [
            'company_id' => $company->getId(), 'address' => $company->getName() . ', ' . $company->getAddress(),
            'to_address' => $company->getName() . ', ' . $company->getAddress(), 'stamp_duty' => 0,
            'account_number' => $company->getRegNo(), 'company_name' => $company->getName(), 'currency' => 'NGN'
        ];
        $total = 0;

        foreach($parcels as $parcel){
            $total += $parcel['amount_due'];
            $invoiceData['parcels'][] = ['waybill_number' => $parcel['waybill_number'],
                'net_amount' => $parcel['amount_due'], 'discount' => 0];
            $invoiceData['reference'] = $parcel['reference_number']|$parcel['waybill_number'];
        }

        $invoiceData['total'] = $total;

        $result = $this->createInvoice(json_decode(json_encode($invoiceData), FALSE));
        if(!$result['success']){
            Util::slackDebug("Invoice Not Create", "Invoice not created for ".$company->getRegNo().'Because '.$result['message']);
        }
    }

    public function recreateCompanyInvoiceAction(){
        $from_date = $this->request->get('from_date');
        $to_date = $this->request->get('to_date');
        $company_id = $this->request->get('company_id');
        $invoice_number = $this->request->get('invoice_number');
        //delete the already existing invoice
        $sql = "DELETE from invoice_parcel WHERE invoice_number = '$invoice_number';
                DELETE FROM invoice WHERE invoice_number = '$invoice_number'";

        $company = Company::findFirst(['id = :id:', ['id' => $company_id]]);
    }

    public function createAllInvoiceAction(){
        ini_set('memory_limit', -1);//to be removed
        set_time_limit(-1);//to be removed
        $from_date = $this->request->getPost("from_date");
        $to_date = $this->request->getPost("to_date");
        if(in_array(null, [$from_date, $to_date])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        /** @var Company[] $companies */
        $companies = Company::find();
        foreach($companies as $company){
            $this->createInvoiceForCompany($from_date, $to_date, $company);
        }
        return $this->response->sendSuccess();
    }

    public function createInvoiceForCompanyAction(){
        $registration_number = $this->request->get('registration_number');
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');

        $company = Company::getByRegistrationNumber($registration_number);
        $this->createInvoiceForCompany($from_date, $to_date, $company);
        return $this->response->sendSuccess('done');
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