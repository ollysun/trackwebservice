<?php

use Phinx\Migration\AbstractMigration;

class AlterParcelTableForAdminEdit extends AbstractMigration
{
    public function change()
    {
        $this->table('parcel')
            ->addColumn('insurance','decimal',['null' => true])
            ->addColumn('storage_demurrage','decimal',['null' => true])
            ->addColumn('handling_charge','decimal',['null' => true])
            ->addColumn('duty_charge','decimal',['null' => true])
            ->addColumn('cost_of_crating','decimal',['null' => true])
            ->addColumn('others','decimal',['null' => true])
            ->update();
    }
}
