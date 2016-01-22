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
        'workersDir'        => __DIR__ . '/../../app/workers/',
        'jobsDir'        => __DIR__ . '/../../app/jobs/',
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


    'fe_base_url' => 'http://staging-courierplusng.cottacush.com',

    'aws' => [
        'aws_key' => getenv('AWS_KEY'),
        'aws_secret' => getenv('AWS_SECRET'),
        's3' => [
            'bucket' => 'tnt-delivery-receipts',
            'namespace' => 'staging',
            'region' => 'us-west-2'
        ]
    ],

    'isCli' => false,

    'beanstalkd' => [
        'host' => getenv('BEANSTALKD_HOST'),
        'port' => getenv('BEANSTALKD_PORT')
    ]
));
