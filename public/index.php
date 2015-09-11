<?php

error_reporting(E_ALL);

define('TNT_DB_HOST', 'TNT_DB_HOST');
define('TNT_DB_USERNAME', 'TNT_DB_USERNAME');
define('TNT_DB_PASSWORD', 'TNT_DB_PASSWORD');
define('TNT_DBNAME', 'TNT_DBNAME');

date_default_timezone_set('Africa/Lagos');

try {
    include __DIR__ . '/../app/library/phpfastcache.php';
    phpFastCache::setup('path', __DIR__ . '/../app/cache');

    include __DIR__ . "/../app/config/global.php";

    /**
     * import composer autoloader
     */
    include __DIR__ . "/../vendor/autoload.php";

    /**
     * Read the configuration
     */
    if (getenv(TNT_DB_HOST) == false){
        $config = include __DIR__ . "/../app/config/config.dev.php";
    } else{
        $config = include __DIR__ . "/../app/config/config.php";
    }

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    var_dump($e);
    echo $e->getMessage();
}
