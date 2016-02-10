<?php

use Phinx\Migration\AbstractMigration;

class RemoveCollectedStatus extends AbstractMigration
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
    public function up()
    {
        if($this->hasTable('status')){
            $sql = "delete from status where status.name = 'parcel_collected'";
            $this->execute($sql);
        }
    }

    public function down()
    {
        $statusTable = $this->table('status');
        if($statusTable->exists()){
            $sql = "insert into status VALUES (4,'parcel_collected')";
            $this->execute($sql);
        }
    }
}
