<?php

use Phinx\Migration\AbstractMigration;

class ShipmentRequestParcelValueDefaultValue extends AbstractMigration
{

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function up()
    {
        $upSql = file_get_contents(dirname(__FILE__) . '/../data/corporate/shipment_requests/shipment_request_parcel_value_alter.sql');
        $this->execute($upSql);
    }
}
