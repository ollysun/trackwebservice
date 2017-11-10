<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class UpdateBulkParcelCreationJobStatus
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class UpdateBulkParcelCreationJobStatus extends AbstractMigration
{

    public function change()
    {
        $jobIds = $this->fetchAll(
            "SELECT DISTINCT j.id FROM jobs j LEFT JOIN bulk_shipment_job_details bsjd 
            ON (j.id = bsjd.job_id AND bsjd.status = 'success') WHERE bsjd.id IS NULL 
            AND j.queue = 'bulk_shipment_creation'"
        );

        if ($jobIds) {
            $jobIds = array_column($jobIds, 'id');
            $this->execute("UPDATE jobs SET `status` = 'failed' WHERE id IN (" . implode(',', $jobIds) . ")");
        }
    }
}
