<?php

use Phinx\Migration\AbstractMigration;

class AddStatusColumnToReturnReasons extends AbstractMigration
{
    public function change()
    {

        if ($this->hasTable('return_reasons')) {
            $this->execute('DELETE FROM return_reasons WHERE status_code = 00');
        }
    }
}