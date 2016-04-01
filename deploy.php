<?php
/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Author: Babatunde Otaru <tunde@cottacush.com>
 * Date: 08/03/2016
 * Time: 12:50 PM
 */

require 'recipe/common.php';

serverList('deploy/servers.yml');

set('writable_dirs', ['app/logs', 'app/cache']);

set('shared_dirs', ['app/logs']);

set('repository', 'git@bitbucket.org:cottacush/tnt-service.git');

env('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');

task('deploy:run_migrations', function () {
    run('cd {{release_path}} && PHINX_DBHOST={{PHINX_DBHOST}} PHINX_DBUSER={{PHINX_DBUSER}} PHINX_DBPASS={{PHINX_DBPASS}} PHINX_DBNAME={{PHINX_DBNAME}} php vendor/bin/phinx migrate -e {{APPLICATION_ENV}}
');
})->desc('Run migrations');

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:run_migrations',
    'deploy:symlink',
    'deploy:writable',
    'cleanup'
])->desc('Deploy Project');


/**
 * Cleanup old releases.
 */
task('cleanup', function () {
    $releases = env('releases_list');

    $keep = get('keep_releases');

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("sudo rm -rf {{deploy_path}}/releases/$release");
    }

    run("cd {{deploy_path}} && if [ -e release ]; then rm release; fi");
    run("cd {{deploy_path}} && if [ -h release ]; then rm release; fi");

})->desc('Cleaning up old releases');


task('workers:stop', function () {
    run('cd {{deploy_path}}/current && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} php app/cli.php worker stop ParcelCreationWorker >  {{deploy_path}}/current/app/logs/parcel_creation_worker.log');
    run('cd {{deploy_path}}/current && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} php app/cli.php worker stop WaybillPrintingWorker > {{deploy_path}}/current/app/logs/waybill_printing_worker.log');
    run('cd {{deploy_path}}/current && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} php app/cli.php worker stop InvoicePrintingWorker > {{deploy_path}}/current/app/logs/invoice_printing_worker.log');
});


task('workers:start', function () {
    run('BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} nohup php {{deploy_path}}/current/app/cli.php worker start ParcelCreationWorker > {{deploy_path}}/current/app/logs/parcel_creation_worker.log &');
    run('APPLICATION_ENV={{APPLICATION_ENV}} AWS_KEY={{AWS_KEY}} AWS_SECRET={{AWS_SECRET}} BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} nohup php {{deploy_path}}/current/app/cli.php worker start WaybillPrintingWorker > {{deploy_path}}/current/app/logs/waybill_printing_worker.log &');
    run('BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} nohup php {{deploy_path}}/current/app/cli.php worker start InvoicePrintingWorker > {{deploy_path}}/current/app/logs/invoice_printing_worker.log &');
});


task('workers:restart', [
    'workers:stop',
    'workers:start'
]);


//slack tasks
task('slack:before_deploy', function () {
    postToSlack('Starting deploy on ' . env('server.name') . '...');
});

task('slack:after_deploy', function () {
    postToSlack('Deploy to ' . env('server.name') . ' done');
});

task('slack:before_workers_stop', function () {
    postToSlack('Stopping Workers on ' . env('server.name') . '...');
});

task('slack:after_workers_stop', function () {
    postToSlack('Workers stopped successfully');
});

task('slack:before_workers_start', function () {
    postToSlack('Starting Workers on ' . env('server.name') . '...');
});

task('slack:after_workers_start', function () {
    postToSlack('Workers started successfully');
});


function postToSlack($message)
{
    runLocally('curl -s -S -X POST --data-urlencode payload="{\"channel\": \"#courier_plus\", \"username\": \"Tnt-Service Release Bot\", \"text\": \"' . $message . '\"}" https://hooks.slack.com/services/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');
}

after('workers:start', 'slack:after_workers_start');
before('workers:start', 'slack:before_workers_start');

after('workers:stop', 'slack:after_workers_stop');
before('workers:stop', 'slack:before_workers_stop');

after('deploy', 'workers:restart');
before('deploy', 'slack:before_deploy');
after('deploy', 'slack:after_deploy');

