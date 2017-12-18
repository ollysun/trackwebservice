<?php
/**
 * Created by PhpStorm.
 * User: Moses Olalere
 * Date: 11/13/2017
 * Time: 10:48 AM
 */

/**
 * @author Moses Olalere <moses_olalere@superfluxnigeria.com>
 */
class MoveTransactionWorker extends BaseWorker
{
    const QUEUE_MOVE_TRANSACTION = 'move_transactions';

    public function __construct()
    {
        parent::__construct(self::QUEUE_MOVE_TRANSACTION);
    }


    public function initializeJob()
    {
        return new MoveTransactionJob($this->getCurrentJob());
    }
}