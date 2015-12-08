<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => getenv(TNT_DB_HOST),
        'username'    => getenv(TNT_DB_USERNAME),
        'password'    => getenv(TNT_DB_PASSWORD),
        'dbname'      => getenv(TNT_DBNAME),
        'charset'     => 'utf8',
    ),
    'application' => array(
        'componentsDir' => __DIR__ . '/../../app/components/',
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir' => __DIR__ . '/../../app/models/',
        'viewsDir' => __DIR__ . '/../../app/views/',
        'pluginsDir' => __DIR__ . '/../../app/plugins/',
        'libraryDir' => __DIR__ . '/../../app/library/',
        'validationsDir' => __DIR__ . '/../../app/validations/',
        'traitsDir' => __DIR__ . '/../../app/traits/',
        'cacheDir' => __DIR__ . '/../../app/cache/',
        'tasksDir'        => __DIR__ . '/../../app/tasks/',
        'baseUri' => '/tnt/',
        'cacheLifeTime' => 259200, //3 days
    ),

    'params' => array(

        //TODO change to production config
        'mailer' => array(
            'mandrill_username' => 'yemi@cottacush.com',
            'mandrill_password' => 'c483t67ANIZJNsVpRMTH4Q',
            'default_from' => ['sys@traceandtrack.com' => 'Courier Plus'],
            'smtp_host' => 'smtp.mandrillapp.com',
            'smtp_port' => 587
        ),
    ),


    'fe_base_url' => 'http://training-courierplusng.cottacush.com'
));