<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class BulkShipmentJobsInstall
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class JobsInstall extends AbstractMigration
{

    /**
     * Create bulk shipment jobs and details tables
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $this->table('jobs')
            ->addColumn('server_job_id', 'integer', ['null' => false])
            ->addColumn('queue', 'string', ['null' => false])
            ->addColumn('job_data', 'text')
            ->addColumn('created_by', 'integer', ['null' => false])
            ->addColumn('status', 'string', ['null' => false])
            ->addColumn('error_message', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('started_at', 'datetime', ['null' => true])
            ->addColumn('completed_at', 'datetime', ['null' => true])
            ->addForeignKey('created_by', 'admin', 'id', ['constraint' => 'fk_jobs_admin_created_by'])
            ->create();

        $this->table('bulk_shipment_job_details')
            ->addColumn('job_id', 'integer', ['null' => false])
            ->addColumn('data', 'text')
            ->addColumn('status', 'string', ['null' => false])
            ->addColumn('waybill_number', 'string', ['null' => true])
            ->addColumn('started_at', 'datetime', ['null' => false])
            ->addColumn('completed_at', 'datetime', ['null' => true])
            ->addColumn('error_message', 'string', ['null' => true])
            ->addForeignKey('job_id', 'jobs', 'id', ['constraint' => 'fk_jobs_bulk_shipment_job_details_job_id'])
            ->create();
    }
}
