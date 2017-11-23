<?php
/**
 * Created by PhpStorm.
 * User: Moses Olalere
 * Date: 11/13/2017
 * Time: 4:07 PM
 * @property int job_id
 * @property string data
 * @property string status
 * @property string started_at
 * @property string error_message
 * @property string completed_at
 * @property string company_id
 * @author Moses Olalere <moses_olalere@superfluxnigeria.com>
 */

class MoveTransactionsJobDetails extends BaseModel
{

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    public function initialize()
    {
        $this->setSource('move_transactions');
    }
}