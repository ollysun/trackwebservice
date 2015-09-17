<?php

use Phalcon\CLI\Console as ConsoleApp;

define('VERSION', '1.0.0');
use Phalcon\DI;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Loader;

//Using the CLI factory default services container
$di = new Cli();

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__)));


date_default_timezone_set('Africa/Lagos');

/**
 * Register the autoloader and tell it to register the tasks directory
 */
$loader = new Loader();

$loader->registerDirs(
    array(
        APPLICATION_PATH . '/tasks',
        APPLICATION_PATH . '/data',
        APPLICATION_PATH . '/models',
        APPLICATION_PATH . '/library',
    )
)->register();


define('TNT_DB_HOST', 'TNT_DB_HOST');
define('TNT_DB_USERNAME', 'TNT_DB_USERNAME');
define('TNT_DB_PASSWORD', 'TNT_DB_PASSWORD');
define('TNT_DBNAME', 'TNT_DBNAME');

/**
 * Process the console arguments
 */
$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}


// Load the configuration file (if any)
if (is_readable(APPLICATION_PATH . '/config/config_cli.php')) {
    $config = include APPLICATION_PATH . '/config/config_cli.php';
    $di->set('config', $config);
}

/**
 * Read services
 */
include APPLICATION_PATH . "/config/services_cli.php";

$console = new ConsoleApp();

$di->set('console', function () use ($console) {
    return $console;
});

$console->setDI($di);

// define global constants for the current task and action
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));


try {
    // handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}