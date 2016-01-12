<?php

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'stagingdb.cottacush.com',
        'username' => 'root',
        'password' => '0N13ArA',
        'dbname' => 'staging_tnt',
        'charset' => 'utf8',
    ],
    'application' => [
        'componentsDir' => __DIR__ . '/../../app/components/',
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir' => __DIR__ . '/../../app/models/',
        'viewsDir' => __DIR__ . '/../../app/views/',
        'pluginsDir' => __DIR__ . '/../../app/plugins/',
        'libraryDir' => __DIR__ . '/../../app/library/',
        'validationsDir' => __DIR__ . '/../../app/validations/',
        'traitsDir' => __DIR__ . '/../../app/traits/',
        'cacheDir' => __DIR__ . '/../../app/cache/',
        'tasksDir' => __DIR__ . '/../../app/tasks/',
        'baseUri' => '/tnt/',
        'cacheLifeTime' => 259200, //3 days
    ],

    'params' => [
        'mailer' => [
            'mandrill_username' => 'yemi@cottacush.com',
            'mandrill_password' => 'c483t67ANIZJNsVpRMTH4Q',
            'default_from' => ['sys@traceandtrack.com' => 'Courier Plus'],
            'smtp_host' => 'smtp.mandrillapp.com',
            'smtp_port' => 587
        ],
    ],

    'fe_base_url' => 'http://local.courierplus.tnt.com',

    'aws' => [
        'aws_key' => getenv('AWS_KEY'),
        'aws_secret' => getenv('AWS_SECRET'),
        's3' => [
            'bucket' => 'tnt-delivery-receipts',
            'namespace' => 'local',
            'region' => 'us-west-2'
        ]
    ]
]);