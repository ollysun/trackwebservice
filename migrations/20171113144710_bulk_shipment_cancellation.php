<?php

use Phinx\Migration\AbstractMigration;

class BulkShipmentCancellation extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('bulk_shipment_cancellation')
            ->addColumn('job_id', 'integer', ['null' => false])
            ->addColumn('data', 'text')
            ->addColumn('status', 'string', ['null' => false])
            ->addColumn('waybill_number', 'string', ['null' => true])
            ->addColumn('started_at', 'datetime', ['null' => false])
            ->addColumn('completed_at', 'datetime', ['null' => true])
            ->addColumn('error_message', 'string', ['null' => true])
            ->addForeignKey('job_id', 'jobs', 'id', ['constraint' => 'fk_bulk_shipment_cancellation_job_id'])
            ->create();
    }
}
