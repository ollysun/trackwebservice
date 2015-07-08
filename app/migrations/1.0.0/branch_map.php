<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class BranchMapMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'branch_map',
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
                    'child_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'parent_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'child_id'
                    )
                ),
                new Column(
                    'status',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'parent_id'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('branch_map_fk0', array('child_id')),
                new Index('branch_map_fk1', array('parent_id')),
                new Index('branch_map_fk2', array('status'))
            ),
            'references' => array(
                new Reference('branch_map_fk0', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'branch',
                    'columns' => array('child_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('branch_map_fk1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'branch',
                    'columns' => array('parent_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('branch_map_fk2', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'status',
                    'columns' => array('status'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'latin1_swedish_ci'
            )
        )
        );
    }
}
