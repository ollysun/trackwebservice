<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;

/**
 * Class BulkWaybillPrintingValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BulkWaybillPrintingValidation extends BaseValidation
{

    /**
     * validations setups
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function initialize()
    {
        $this->setRequiredFields(['bulk_shipment_task_id']);

        $this->add('bulk_shipment_task_id', new Model([
            'model' => Job::class,
            'conditions' => 'id=:id: AND queue=:queue:',
            'bind' => ['id' => $this->getValue('bulk_shipment_task_id'), 'queue' => ParcelCreationWorker::QUEUE_BULK_SHIPMENT_CREATION],
            'message' => 'Invalid Bulk Shipment Task supplied. Please try again'
        ]));
    }
}