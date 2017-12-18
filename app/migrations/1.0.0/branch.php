<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class BranchMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'branch',
            array(
            'columns' => array(
                new Column(
                    'id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 11,
                        'first' => true
                    )
                ),
                new Column(
                    'name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 128,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'branch_type',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'name'
                    )
                ),
                new Column(
                    'address',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'branch_type'
                    )
                ),
                new Column(
                    'created_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'address'
                    )
                ),
                new Column(
                    'modified_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'created_date'
                    )
                ),
                new Column(
                    'status',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'modified_date'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('branch_fk0', array('branch_type')),
                new Index('branch_fk1', array('status'))
            ),
            'references' => array(
                new Reference('branch_fk0', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'branch_type',
                    'columns' => array('branch_type'),
                    'referencedColumns' => array('id')
                )),
                new Reference('branch_fk1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'status',
                    'columns' => array('status'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '3',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'latin1_swedish_ci'
            )
        )
        );
    }
}
