<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',// getenv(TNT_DB_HOST),
        'username'    => 'root',//getenv(TNT_DB_USERNAME),
        'password'    => '123@qwe',//getenv(TNT_DB_PASSWORD),
        'dbname'      => 'tnt_live',//getenv(TNT_DBNAME),
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
            'mandrill_username' => 'yemexx1@gmail.com',
            'mandrill_password' => 'fakh_1NtNOd6Vq3J5CvHCQ',
            'default_from' => ['sys@traceandtrack.com' => 'Courier Plus'],
            'smtp_host' => 'smtp.mandrillapp.com',
            'smtp_port' => 587
        ),
    ),


    'fe_base_url' => 'http://prod-tnt.cottacush.com',

    'isCli' => true,

    'beanstalkd' => [
        'host' => getenv('BEANSTALKD_HOST'),
        'port' => getenv('BEANSTALKD_PORT')
    ],

    'aws' => [
        'aws_key' => getenv('AWS_KEY'),
        'aws_secret' => getenv('AWS_SECRET'),
        's3' => [
            'namespace' => (getenv('APPLICATION_ENV')) ? getenv('APPLICATION_ENV') : 'local',
            'region' => 'us-west-2'
        ],
    ],
));
