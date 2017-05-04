<?php
use Phalcon\Mvc\Model\Resultset;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class InvoiceController extends ControllerBase
{
    protected function createInvoice($data, $invoice_number = null){
        $invoiceRequestValidator = new InvoiceValidation($data);
        if (!$invoiceRequestValidator->validate()) {
            return ['success' => false, 'message' => $invoiceRequestValidator->getMessages()];
        }

        // Generate Invoice Number
        $data->invoice_number = $invoice_number != null? $invoice_number: Invoice::generateInvoiceNumber($data->company_id);

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
    public function createInvoiceForCompany($from_date, $to_date, $company, $invoice_number = null){

        $filter_by = [/*'payment_type' => '1', */'start_created_date' => $from_date,
            'end_created_date' => $to_date, 'company_id' => $company->getId(), 'remove_cancelled_shipments' => 1,  'send_all' => 1];
        $parcels = Parcel::fetchAll(0, 1000, $filter_by, []);
        if(!$parcels) return;

	//dd($parcels);

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

        $result = $this->createInvoice(json_decode(json_encode($invoiceData), FALSE), $invoice_number);
        if(!$result['success']){
            Util::slackDebug("Invoice Not Create", "Invoice not created for ".$company->getRegNo().'Because '.$result['message']);
        }
    }

    public function recreateInvoiceAction(){
        $this->auth->allowOnly(Role::ADMIN);
        set_time_limit(60);
       $invoice_number = $this->request->get('invoice_number');
        if(!$invoice_number){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $invoice = Invoice::fetchOne(['invoice_number' => $invoice_number], []);
        if(!$invoice){
            return $this->response->sendError('Invalid invoice number');
        }
        //keep all parcels in memory before deleting themads
        $invoice_parcels = InvoiceParcel::fetchAll(0, 0, [], ['invoice_number' => $invoice_number, 'no_paginate' => 1]);


        /*$sql = "DELETE FROM invoice_parcels WHERE invoice_number = :invoice_number:; DELETE FROM invoices WHERE invoice_number = :invoice_number:;";
        //execute the delete query dsaffd
        $modelManage = (new Invoice())->getModelsManager();
        $modelManage->createQuery($sql)->execute(['invoice_number' => $invoice_number]);*/


         $total = 0.00;
        $invoiceData['reference'] = $invoice['reference'];
        /** @var Company $company */
        $company = Company::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $invoice['company_id']]]);

        $invoiceData = [
            'reference' => $invoice['reference'],
            'company_id' => $invoice['company_id'], 'address' => $company->getName() . ', ' . $company->getAddress(),
            'to_address' => $company->getName() . ', ' . $company->getAddress(), 'stamp_duty' => 0,
            'account_number' => ['account_number'], 'company_name' => ['company_name'], 'currency' => 'NGN'
        ];

        foreach($invoice_parcels as $invoice_parcel){
            //delete the invoice parcel
            $invoice_parcel_obj = InvoiceParcel::find($invoice_parcel['id']);
            $invoice_parcel_obj->delete();

            $parcel = Parcel::getByWaybillNumber($invoice_parcel['waybill_number']);
            if(!$parcel) continue;
            $total += $parcel->getAmountDue();
            $invoiceData['parcels'][] = ['waybill_number' => $parcel->getWaybillNumber(),
                'net_amount' => $parcel->getAmountDue(), 'discount' => 0];
        }

        $invoiceData['total'] = $total;

        //Delete the invoice
        $old_invoice = Invoice::findFirstByInvoiceNumber($invoice_number);
        $old_invoice->delete();
        $result = $this->createInvoice(json_decode(json_encode($invoiceData), FALSE), $invoice_number);
        if(!$result['success']){
            Util::slackDebug("Invoice Not Create", "Invoice not created for ".$invoice_number.'Because '.$result['message']);
            return $this->response->sendError($result['message']);
        }
        return $this->response->sendSuccess();

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
	$invoice_number = $this->request->get('invoice_number');
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');

        $company = Company::getByRegistrationNumber($registration_number);
        $this->createInvoiceForCompany($from_date, $to_date, $company, $invoice_number);
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