<?php

use Phinx\Migration\AbstractMigration;

class AlterJobDataColumn extends AbstractMigration
{
    public function up()
    {
        $this->execute('ALTER TABLE jobs MODIFY COLUMN job_data MEDIUMTEXT');
    }

    public function down()
    {
        $this->execute('ALTER TABLE jobs MODIFY COLUMN job_data TEXT');
    }
}
