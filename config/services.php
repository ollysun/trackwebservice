<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Logger;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Manager as EventsManager;
use Superflux\Mailer\MailerHandler;
use PhalconUtils\S3\S3Client;
use Pheanstalk\Pheanstalk;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();


/**
 * Registered the config to make it accessible global
 */
$di['config'] = $config;

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();
    $view->disable();
    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    $connection = new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        "charset" => $config->database->charset
    ));

    if (getenv('APPLICATION_ENV') == false) {
        $eventsManager = new Phalcon\Events\Manager();
        $logger = new Phalcon\Logger\Adapter\File(dirname(__FILE__) . "logs/sql_debug.log");
        $eventsManager->attach('db', function ($event, $connection) use ($logger) {
            if ($event->getType() == 'beforeQuery') {
                /** @var DbAdapter $connection */
                $logger->log($connection->getSQLStatement(), Logger::DEBUG);
            }
        });
        $connection->setEventsManager($eventsManager);
    }

    return $connection;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Adding custom Response class [PackedResponse]
 */
$di->set('response', function () {
    $response = new PackedResponse();
    return $response;
});

/**
 * Added cache functionality for data
 */
$di->set('cache', function () use ($config) {
    #todo: use memcached after
    return \phpFastCache();
}, true);

/**
 * Added Auth functionality as a service
 */
$di->set('auth', function () {
    $auth = new Auth();
    return $auth;
}, true);

/**
 *Register Mailer Service
 */
$di->set('mailer', function () use ($config) {
    return new MailerHandler(
        $config->params->mailer->ses_key,
        $config->params->mailer->ses_secret,
        $config->params->mailer->smtp_host,
        $config->params->mailer->smtp_port,
        $config->params->mailer->default_from);
});

/**
 * Register s3 client as a lazy loaded service
 */
/*
$di->set('s3Client', function () use ($config) {
    return new S3Client(
        $config->aws->aws_key,
        $config->aws->aws_secret,
        $config->aws->s3->region,
        $config->aws->s3->bucket,
        $config->aws->s3->namespace);
});
*/

$di->set('s3Client', function () use ($config) {
    return new S3Client(
        'AKIAJ57TMSWZMAIY3QOQ',//$config->aws->aws_key,
        'cDOLlR6Fa2tOe/ZUK5/lOEUrp7UccXlhQf9Xbk30',//$config->aws->aws_secret,
        'us-east-1',
        'tnt-storage',//$config->aws->s3->bucket,
        'staging'//$config->aws->s3->namespace
    );
});

$di->set('pheanStalkServer', function () use ($config) {
    return new Pheanstalk($config->beanstalkd->host, $config->beanstalkd->port);
});


/**
 * Added Dispatcher service with events handled
 */
$di->set('dispatcher', function () {

    $events_manager = new EventsManager();

    $events_manager->attach("dispatch:beforeExecuteRoute", function ($event, $dispatcher) {
        $di = FactoryDefault::getDefault();
        $auth = $di['auth'];
        if (!$auth->skipAuth(array(
            array('controller' => 'ref'),
            array('controller' => 'auth', 'action' => 'resetPassword'),
            array('controller' => 'auth', 'action' => 'forgotPassword'),
            array('controller' => 'auth', 'action' => 'validatePasswordResetToken'),
            array('controller' => 'auth', 'action' => 'login'),
            array('controller' => 'user', 'action' => 'login'),
            array('controller' => 'parcel', 'action' => 'history')
        ))
        ) {
            $i = $di['request']->getHeader('i');
            $a = $di['request']->getHeader('a');

            $auth->loadTokenData($i);

            $token_check = $auth->checkToken($a);
            if ($token_check == Auth::STATUS_OK) {
            } else if ($token_check == Auth::STATUS_ACCESS_DENIED) {
                echo $di['response']->sendAccessDenied()->getContent();
                exit();
            } else if ($token_check == Auth::STATUS_LOGIN_REQUIRED) {
                echo $di['response']->sendLoginRequired()->getContent();
                exit();
            } else {
                echo $di['response']->sendError()->getContent();
                exit();
            }
        }

        /**
         * Set Current Transaction in New Relic
         * @author Adegoke Obasa <goke@cottacush.com>
         */
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction($dispatcher->getControllerName() . '/' . $dispatcher->getActionName());
        }
    });

    $dispatcher = new MvcDispatcher();

    $dispatcher->setEventsManager($events_manager);
    return $dispatcher;
}, true);