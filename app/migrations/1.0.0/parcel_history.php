<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class ParcelHistoryMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'parcel_history',
            array(
            'columns' => array(
                new Column(
                    'id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 20,
                        'first' => true
                    )
                ),
                new Column(
                    'parcel_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'branch_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'parcel_id'
                    )
                ),
                new Column(
                    'description',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'branch_id'
                    )
                ),
                new Column(
                    'created_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'description'
                    )
                ),
                new Column(
                    'status',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'created_date'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('parcel_history_fk0', array('parcel_id')),
                new Index('parcel_history_fk1', array('branch_id')),
                new Index('parcel_history_fk2', array('status'))
            ),
            'references' => array(
                new Reference('parcel_history_fk0', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'parcel',
                    'columns' => array('parcel_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_history_fk1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'branch',
                    'columns' => array('branch_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_history_fk2', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'status',
                    'columns' => array('status'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '4',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'latin1_swedish_ci'
            )
        )
        );
    }
}
