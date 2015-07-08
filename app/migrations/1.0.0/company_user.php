<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class CompanyUserMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'company_user',
            array(
            'columns' => array(
                new Column(
                    'company_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'first' => true
                    )
                ),
                new Column(
                    'user_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'company_id'
                    )
                ),
                new Column(
                    'status',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'user_id'
                    )
                )
            ),
            'indexes' => array(
                new Index('company_user_fk0', array('company_id')),
                new Index('company_user_fk1', array('user_id')),
                new Index('company_user_fk2', array('status'))
            ),
            'references' => array(
                new Reference('company_user_fk0', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'company',
                    'columns' => array('company_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('company_user_fk1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'user',
                    'columns' => array('user_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('company_user_fk2', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'status',
                    'columns' => array('status'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'latin1_swedish_ci'
            )
        )
        );
    }
}
