<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'tnt',
//        'host' => '52.26.122.248',
//        'username' => 'explode',
//        'password' => '$$12TY*34YSD$',
//        'dbname' => 'tnt',
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
    ),

    'params' => array(
        'mailer' => array(
            'mandrill_username' => 'yemi@cottacush.com',
            'mandrill_password' => 'c483t67ANIZJNsVpRMTH4Q',
            'default_from' => ['sys@traceandtrack.com' => 'Courier Plus'],
            'smtp_host' => 'smtp.mandrillapp.com',
            'smtp_port' => 587
        ),
    ),

    'fe_base_url' => 'http://local.courierplus.tnt.com'
));