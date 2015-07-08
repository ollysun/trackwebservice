<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class AdminMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'admin',
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
                    'role_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'branch_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'role_id'
                    )
                ),
                new Column(
                    'staff_id',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 15,
                        'after' => 'branch_id'
                    )
                ),
                new Column(
                    'email',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 128,
                        'after' => 'staff_id'
                    )
                ),
                new Column(
                    'password',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 60,
                        'after' => 'email'
                    )
                ),
                new Column(
                    'fullname',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'password'
                    )
                ),
                new Column(
                    'phone',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 20,
                        'after' => 'fullname'
                    )
                ),
                new Column(
                    'created_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'phone'
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
                new Index('email', array('email')),
                new Index('staff_id', array('staff_id')),
                new Index('admin_fk0', array('role_id')),
                new Index('admin_fk1', array('status')),
                new Index('branch_id', array('branch_id'))
            ),
            'references' => array(
                new Reference('admin_fk0', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'role',
                    'columns' => array('role_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('admin_fk1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'status',
                    'columns' => array('status'),
                    'referencedColumns' => array('id')
                )),
                new Reference('admin_ibfk_1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'branch',
                    'columns' => array('branch_id'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '6',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'latin1_swedish_ci'
            )
        )
        );
    }
}
