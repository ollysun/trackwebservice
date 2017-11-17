<?php
// AWS Credentials
define( 'AWSAccessKeyId', 'AKIAJFG642ALS2CEVSAA' );
define( 'AWSSecretKey', 'AgsO3Mg+gzmKkcSRidCU4+0CWOzJe0nubcD8Ho3mupUk' );

// Email address to send test email from
define( 'FROM_ADDRESS', 'trackplus@courierplus-ng.com' );

// Set the correct endpoint. http://docs.aws.amazon.com/general/latest/gr/rande.html#ses_region
define( 'AWSSESEndpoint', 'https://email.us-east-1.amazonaws.com/' );


return new \Phalcon\Config([
    /*'database' => [
        'adapter' => 'Mysql',
        'host' => 'tnt-new.cqnljhscd9gz.eu-central-1.rds.amazonaws.com',
        'username' => 'root',
        'password' => 'thelcmof8is2',
        'dbname' => 'tnt',
        'charset' => 'utf8',
    ],*/
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'trackplus',
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
        'workersDir'        => __DIR__ . '/../../app/workers/',
        'jobsDir'        => __DIR__ . '/../../app/jobs/',
        'baseUri' => '/tnt/',
        'cacheLifeTime' => 259200, //3 days
    ],

    'params' => [
        'mailer' => [
            'mandrill_username' => 'yemexx1@gmail.com',
            'mandrill_password' => 'fakh_1NtNOd6Vq3J5CvHCQ',
            'ses_key' => 'AKIAIZJGZVNDYZPUTV5Q',
            'ses_secret' => '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',
            'default_from' => ['trackplus@openbulksms.com' => 'Courier Plus'],
            'smtp_host' => 'email-smtp.us-east-1.amazonaws.com',
            'smtp_port' => 587
        ]
    ],

    'fe_base_url' => 'http://trackplus.courierplus-ng.com/',

    'aws' => [
        'aws_key' => getenv('AWS_KEY'),
        'aws_secret' => getenv('AWS_SECRET'),
        's3' => [
            'bucket' => 'tnt-delivery-receipts',
            'namespace' => 'local',
            'region' => 'us-west-2'
        ],
    ],

    'isCli' => false,

    'beanstalkd' => [
        'host' => getenv('BEANSTALKD_HOST'),
        'port' => getenv('BEANSTALKD_PORT')
    ]
]);