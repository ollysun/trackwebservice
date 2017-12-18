<?php
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;

/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 23/10/15
 * Time: 1:33 PM
 */
class ParcelCommentValidation extends BaseValidation
{
    /**
     * validations setups
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    function initialize()
    {
        $this->setRequiredFields(['waybill_number', 'type', 'comment']);

        $this->add('comment', new PresenceOf([
            'allowEmpty' => false,
            'message' => 'Please add a comment'
        ]));

        $this->add('waybill_number', new Regex([
            'pattern' => '/^\d[A-Z](\d|\-)+[\d]$/i',
            'message' => 'Invalid waybill number'
        ]));

        $this->add('type', new InclusionIn([
            'domain' => ParcelComment::$comment_types,
            'message' => 'Invalid comment type. Comment type can only be one of ' . implode(',', ParcelComment::$comment_types)
        ]));

        $this->add('waybill_number', new Model([
            'model' => Parcel::class,
            'conditions' => 'waybill_number = :waybill_number:',
            'bind' => ['waybill_number' => $this->getValue('waybill_number')],
            'message' => 'Parcel with waybill_number ' . $this->getValue('waybill_number') . ' does not exist'
        ]));
    }
}