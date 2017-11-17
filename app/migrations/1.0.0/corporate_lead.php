<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class CorporateLeadMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'corporate_lead',
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
                    'user_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'comment',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'user_id'
                    )
                ),
                new Column(
                    'created_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'comment'
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
                new Index('corporate_lead_fk0', array('user_id')),
                new Index('corporate_lead_fk1', array('status'))
            ),
            'references' => array(
                new Reference('corporate_lead_fk0', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'user',
                    'columns' => array('user_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('corporate_lead_fk1', array(
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
