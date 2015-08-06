<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'tnt',
        'charset'     => 'utf8',
    ),
    'application' => array(
        'componentsDir' => __DIR__ . '/../../app/components/',
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir' => __DIR__ . '/../../app/models/',
        'viewsDir' => __DIR__ . '/../../app/views/',
        'pluginsDir' => __DIR__ . '/../../app/plugins/',
        'libraryDir' => __DIR__ . '/../../app/library/',
        'cacheDir' => __DIR__ . '/../../app/cache/',
        'tasksDir'        => __DIR__ . '/../../app/tasks/',
        'baseUri' => '/tnt/',
        'cacheLifeTime' => 259200, //3 days
    )
));
