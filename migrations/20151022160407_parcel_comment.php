<?php

use Phinx\Migration\AbstractMigration;

class ParcelComment extends AbstractMigration
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
        $parcel_comments = $this->table('parcel_comments');
        $parcel_comments->addColumn('waybill_number', 'string', ['limit' => 25, 'null' => false]);
        $parcel_comments->addColumn('comment', 'text');
        $parcel_comments->addColumn('added_by', 'integer', ['limit' => 11, 'null' => false]);
        $parcel_comments->addColumn('type', 'string', ['limit' => 50, 'null' => false]);
        $parcel_comments->addColumn('created_at', 'datetime', ['null' => false]);
        $parcel_comments->addForeignKey('waybill_number','parcel', 'waybill_number');
        $parcel_comments->addForeignKey('added_by','admin', 'id');
        $parcel_comments->create();
    }
}
