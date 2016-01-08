<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\InlineValidator;
use PhalconUtils\Validation\Validators\Model;

/**
 * Class BulkShipmentCreationValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BulkShipmentCreationValidation extends BaseValidation
{

    /**
     * validations setups
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    function initialize()
    {
        $this->setRequiredFields(['data', 'billing_plan_id', 'company_id']);

        $this->add('data', new InlineValidator([
            'function' => function () {
                return is_array($this->getValue('data'));
            },
            'message' => 'data must be an array of shipments'
        ]));

        $this->add('company_id', new Model([
            'model' => Company::class
        ]));

        $this->add('billing_plan_id', new Model([
            'model' => BillingPlan::class
        ]));

        $this->add('billing_plan_id', new Model([
            'model' => BillingPlan::class,
            'conditions' => 'id=:id: AND company_id=:company_id:',
            'bind' => ['id' => $this->getValue('billing_plan_id'), 'company_id' => $this->getValue('company_id')],
            'message' => 'Billing Plan does not belong to the company supplied'
        ]));
    }
}