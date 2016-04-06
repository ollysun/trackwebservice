<?php

use Phinx\Migration\AbstractMigration;

class AddAttemptedDeliveryColumn extends AbstractMigration
{
    public function change()
    {
        $this->table('parcel')->addColumn('return_status','integer' ,
            ['limit' => 4, 'null' => false , 'default' => 0])->save();
    }
}
