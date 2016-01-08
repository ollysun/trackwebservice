<?php
/**
 * User: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 9/15/15
 * Time: 4:11 PM
 */

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use PhalconUtils\Mailer\MailerHandler;
use Pheanstalk\Pheanstalk;


/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        "charset" => $config->database->charset
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});


/**
 *Register Mailer Service
 */
$di->set('mailer', function () use ($config) {
    return new MailerHandler(
        $config->params->mailer->mandrill_username,
        $config->params->mailer->mandrill_password,
        $config->params->mailer->smtp_host,
        $config->params->mailer->smtp_port,
        $config->params->mailer->default_from);
});

$di->set('pheanStalkServer', function () use ($config) {
    return new Pheanstalk($config->beanstalkd->host, $config->beanstalkd->port);
});