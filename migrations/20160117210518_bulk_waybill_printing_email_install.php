<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class BulkWaybillPrintingEmailInstall
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BulkWaybillPrintingEmailInstall extends AbstractMigration
{
    /**
     * Insert email for bulk waybill printing
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $sql = "
INSERT INTO `email_message` (`email_message_code`, `to_email`, `subject`, `message`, `created_date`, `status`) VALUES
('bulk_email_printing', 'dapo@cottacush.com', 'Courier Plus - Bulk Waybill Printable Document', '<!DOCTYPE html>\n<html>\n<head>\n	<title></title>\n</head>\n<style type=\'text/css\'>\n	html {background-color: #ddd}\n	body {margin: 10px; color: #666; font-size: 16px; line-height: 22px; font-family: sans-serif}\n	#all {background-color: #fff; padding: 25px; margin: 0 0 5px}\n	h1, h2, h3, h4, h5, h6 {color: #333; margin: 10px 0 15px; line-height: 1.1}\n	a {color: #9C27B0}\n	p {margin-bottom: 10px}\n	header, footer, section {display:block;}\n	strong, b {font-weight: bold; color: #333;}\n	.form-group {margin-bottom: 10px}\n	label {font-weight: bold; color: #333;  margin-bottom: 2px}\n	.login-label {width: 90px;}\n	.form-control-static {padding: 3px 0}\n	section, .section {margin: 8px 0;}\n	.logo { width: 150px; display:block; margin: 0 auto 15px; }\n	.foot-note {color: #999; font-size:14px;line-height:1;text-align:center;}\n</style>\n<body>\n	<div id=\'all\'>\n<div class=\'section\'>\n			<p>Hello {{name}},<br>Please click the following link to download a printable document for waybills in Bulk shipment task #{{task_number}}: </p>\n<a href=\'{{link}}\'>Bulk shipment task #{{task_number}} Waybill</a>\n			<br>\n			<p>Sincerely,<br>The CourierPlus Team</p>\n		</div>\n	</div>\n	<div class=\'foot-note\'>&copy; 2015 CourierPlus</div>\n</body>\n</html>', NOW(), 1);";

        $this->execute($sql);
    }
}
