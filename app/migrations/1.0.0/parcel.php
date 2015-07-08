<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class ParcelMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'parcel',
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
                    'waybill_number',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'parcel_type',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'waybill_number'
                    )
                ),
                new Column(
                    'sender_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'parcel_type'
                    )
                ),
                new Column(
                    'sender_address_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'sender_id'
                    )
                ),
                new Column(
                    'receiver_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'sender_address_id'
                    )
                ),
                new Column(
                    'receiver_address_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'receiver_id'
                    )
                ),
                new Column(
                    'weight',
                    array(
                        'type' => Column::TYPE_FLOAT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'receiver_address_id'
                    )
                ),
                new Column(
                    'amount_due',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'weight'
                    )
                ),
                new Column(
                    'cash_amount',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'amount_due'
                    )
                ),
                new Column(
                    'pos_amount',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'cash_amount'
                    )
                ),
                new Column(
                    'cash_on_delivery',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'pos_amount'
                    )
                ),
                new Column(
                    'delivery_amount',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'cash_on_delivery'
                    )
                ),
                new Column(
                    'delivery_type',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'delivery_amount'
                    )
                ),
                new Column(
                    'payment_type',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'delivery_type'
                    )
                ),
                new Column(
                    'shipping_type',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'payment_type'
                    )
                ),
                new Column(
                    'created_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'shipping_type'
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
                new Index('waybill_number', array('waybill_number')),
                new Index('parcel_fk0', array('sender_id')),
                new Index('parcel_fk1', array('sender_address_id')),
                new Index('parcel_fk2', array('receiver_id')),
                new Index('parcel_fk3', array('receiver_address_id')),
                new Index('parcel_fk4', array('status')),
                new Index('parcel_fk5', array('delivery_type')),
                new Index('payment_type', array('payment_type')),
                new Index('shipping_type', array('shipping_type'))
            ),
            'references' => array(
                new Reference('parcel_fk0', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'user',
                    'columns' => array('sender_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_fk1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'address',
                    'columns' => array('sender_address_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_fk2', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'user',
                    'columns' => array('receiver_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_fk3', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'address',
                    'columns' => array('receiver_address_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_fk4', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'status',
                    'columns' => array('status'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_fk5', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'delivery_type',
                    'columns' => array('delivery_type'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_fk6', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'payment_type',
                    'columns' => array('payment_type'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_fk7', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'shipping_type',
                    'columns' => array('shipping_type'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_ibfk_1', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'payment_type',
                    'columns' => array('payment_type'),
                    'referencedColumns' => array('id')
                )),
                new Reference('parcel_ibfk_2', array(
                    'referencedSchema' => 'tnt',
                    'referencedTable' => 'shipping_type',
                    'columns' => array('shipping_type'),
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
