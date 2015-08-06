<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Manager as EventsManager;

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
$di->set('response', function() {
    $response = new PackedResponse();
    return $response;
});

/**
 * Added cache functionality for data
 */
$di->set('cache', function () use ($config){
    #todo: use memcached after
    return \phpFastCache();
}, true);

/**
 * Added Auth functionality as a service
 */
$di->set('auth', function(){
    $auth = new Auth();
    return $auth;
}, true);

/**
 * Added Dispatcher service with events handled
 */
$di->set('dispatcher', function() {
    $events_manager = new EventsManager();

//    $events_manager->attach('dispatch:beforeException', function($event, $dispatcher){
//        /**
//         * @var MvcDispatcher $dispatcher
//         */
//        $di = FactoryDefault::getDefault();
//        echo $di['response']->sendError(ResponseMessage::INTERNAL_ERROR)->getContent();
//        exit();
//    });

    $events_manager->attach("dispatch:beforeExecuteRoute", function($event, $dispatcher){
//        /**
//         * @var MvcDispatcher $dispatcher
//         */
        $di = FactoryDefault::getDefault();
        $auth = $di['auth'];
        if (!$auth->skipAuth(array(
            array('controller' => 'ref'),
            array('controller' => 'admin', 'action' => 'login'),
            array('controller' => 'user', 'action' => 'login')
        )))
        {
            $i = $di['request']->getHeader('i');
            $a = $di['request']->getHeader('a');

            $auth->loadTokenData($i);

            $token_check = $auth->checkToken($a);
            if ($token_check == Auth::STATUS_OK){
//                $auth->resetToken();
            } else if ($token_check == Auth::STATUS_ACCESS_DENIED){
                echo $di['response']->sendAccessDenied()->getContent();
                exit();
            } else if ($token_check == Auth::STATUS_LOGIN_REQUIRED){
                echo $di['response']->sendLoginRequired()->getContent();
                exit();
            } else {
                echo $di['response']->sendError()->getContent();
                exit();
            }
        }
    });

    $dispatcher = new MvcDispatcher();

    $dispatcher->setEventsManager($events_manager);
    return $dispatcher;
}, true);