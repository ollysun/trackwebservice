<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class AddInvoiceNumberToBulkInvoicingTaskDetail
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class AddInvoiceNumberToBulkInvoicingTaskDetail extends AbstractMigration
{

    public function up()
    {
        $this->table('bulk_invoice_job_details')
            ->addColumn('invoice_number', 'string', ['length' => 50, 'null' => true, 'default' => null, 'after' => 'data'])
            ->update();
    }

    public function down()
    {
        $this->table('bulk_invoice_job_details')
            ->removeColumn('invoice_number')
            ->update();
    }
}
