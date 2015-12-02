<?php

use Phinx\Migration\AbstractMigration;

class RemoveOldData extends AbstractMigration
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

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function up()
    {
        if (!$this->hasTable('teller_parcel')) {
            $sql = 'CREATE TABLE `teller_parcel` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `parcel_id` bigint(20) NOT NULL,
                  `teller_id` bigint(20) NOT NULL,
                  `created_date` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `k_teller_parcel_teller_id` (`teller_id`),
                  KEY `k_teller_parcel_parcel_id` (`parcel_id`),
                  CONSTRAINT `fk_teller_parcel_teller_teller_id` FOREIGN KEY (`teller_id`) REFERENCES `teller` (`id`),
                  CONSTRAINT `fk_teller_parcel_teller_parcel_id` FOREIGN KEY (`parcel_id`) REFERENCES `parcel` (`id`));';
            $this->execute($sql);
        }

        if (!$this->hasTable('shipment_request_comments')) {
            $sql = 'CREATE TABLE IF NOT EXISTS shipment_request_comments (
                  `id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                  `shipment_request_id` INT(11) NOT NULL,
                  `comment` TEXT NOT NULL,
                  `type` VARCHAR(25) NOT NULL,
                  `created_at` DATETIME NOT NULL,
                  `updated_at` DATETIME NOT NULL,
                  `status` TINYINT(1) DEFAULT 1,
                  KEY k_shipment_request_id (shipment_request_id),
                  KEY k_type (`type`),
                  KEY k_status (`status`),
                  CONSTRAINT fk_src_sr_shipment_request_id FOREIGN KEY (shipment_request_id) REFERENCES shipment_requests(id));';
            $this->execute($sql);
        }

        if (!$this->hasTable('shipment_request_comments')) {
            $sql = 'CREATE TABLE IF NOT EXISTS pickup_request_comments (
                  `id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                  `pickup_request_id` INT(11) NOT NULL,
                  `comment` TEXT NOT NULL,
                  `type` VARCHAR(25) NOT NULL,
                  `created_at` DATETIME NOT NULL,
                  `updated_at` DATETIME NOT NULL,
                  `status` TINYINT(1) DEFAULT 1,
                  KEY k_pickup_request_id (pickup_request_id),
                  KEY k_type (`type`),
                  KEY k_status (`status`),
                  CONSTRAINT fk_src_sr_pickup_request_id FOREIGN KEY (pickup_request_id) REFERENCES pickup_requests(id));';
            $this->execute($sql);
        }

        if ($this->hasTable('parcel_teller')) {
            $this->dropTable('parcel_teller');
        }

        $start_date = '2015-01-01 00:00:00';
        $end_date = '2015-11-22 23:59:59';

        $this->adapter->beginTransaction();

        $this->execute("DELETE FROM held_parcel WHERE (parcel_id IN(SELECT id FROM parcel WHERE created_date BETWEEN '{$start_date}' AND '{$end_date}'))");
        $this->execute("DELETE FROM shipment_request_comments WHERE (created_at BETWEEN '{$start_date}' AND '{$end_date}')");
        $this->execute("DELETE FROM shipment_requests WHERE (created_at BETWEEN '{$start_date}' AND '{$end_date}')");
        $this->execute("DELETE FROM pickup_request_comments WHERE (created_at BETWEEN '{$start_date}' AND '{$end_date}')");
        $this->execute("DELETE FROM pickup_requests WHERE (created_at BETWEEN '{$start_date}' AND '{$end_date}')");

        $this->execute("DELETE FROM teller_parcel WHERE (created_date BETWEEN '{$start_date}' AND '{$end_date}')");
        $this->execute("DELETE FROM teller WHERE (created_date BETWEEN '{$start_date}' AND '{$end_date}')");
        $this->execute("DELETE FROM delivery_receipts WHERE (created_at BETWEEN '{$start_date}' AND '{$end_date}')");

        $this->execute("DELETE FROM linked_parcel WHERE (parent_id IN(SELECT id FROM parcel WHERE created_date BETWEEN '{$start_date}' AND '{$end_date}') OR child_id IN(SELECT id FROM parcel WHERE created_date BETWEEN '{$start_date}' AND '{$end_date}'))");
        $this->execute("DELETE FROM parcel_history WHERE (parcel_id IN(SELECT id FROM parcel WHERE created_date BETWEEN '{$start_date}' AND '{$end_date}'))");
        $this->execute("DELETE FROM parcel_comments WHERE (created_at BETWEEN '{$start_date}' AND '{$end_date}')");

        $this->execute("DELETE FROM parcel WHERE (created_date BETWEEN '{$start_date}' AND '{$end_date}')");

        $this->execute("DELETE FROM manifest WHERE (created_date BETWEEN '{$start_date}' AND '{$end_date}')");
        $this->execute("DELETE FROM bank_account WHERE (created_date BETWEEN '{$start_date}' AND '{$end_date}')");

        $this->adapter->commitTransaction();
    }
}
