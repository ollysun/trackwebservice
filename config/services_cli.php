<?php
/**
 * User: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 9/15/15
 * Time: 4:11 PM
 */

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Logger;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use PhalconUtils\Mailer\MailerHandler;
use PhalconUtils\S3\S3Client;
use Pheanstalk\Pheanstalk;


/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    $connection = new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        "charset" => $config->database->charset,
        'persistent' => true
    ));
    return $connection;
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
    return new Pheanstalk($config->beanstalkd->host, $config->beanstalkd->port, null, true);
});

/**
 * Register s3 client as a lazy loaded service

$di->set('s3Client', function () use ($config) {
    return new S3Client(
        $config->aws->aws_key,
        $config->aws->aws_secret,
        $config->aws->s3->region,
        null,
        $config->aws->s3->namespace);
});
 */

/*$di->set('s3Client', function () use ($config) {
    return new S3Client(
        $config->aws->aws_key,
        $config->aws->aws_secret,
        'us-east-1',
        'tnt-storage',//$config->aws->s3->bucket,
        'staging'//$config->aws->s3->namespace
    );
});*/

$di->set('s3Client', function () use ($config) {
    return new S3Client(
        'AKIAIHZQOXQPVD73RVGA',
        'o44ZWXPCWzvBTABbby6p+mX+4pSlJKZavMet3Kas',
        'us-east-1',
        'tnt-storage',//$config->aws->s3->bucket,
        'staging'//$config->aws->s3->namespace
    );
});

//