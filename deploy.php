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


//slack tasks
task('slack:before_deploy', function () {
    postToSlack('Starting deploy on ' . env('server.name') . '...');
});

task('slack:after_deploy', function () {
    postToSlack('Deploy to ' . env('server.name') . ' done');
});

function postToSlack($message)
{
    runLocally('curl -s -S -X POST --data-urlencode payload="{\"channel\": \"#courier_plus\", \"username\": \"Tnt-Service Release Bot\", \"text\": \"' . $message . '\"}" https://hooks.slack.com/services/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');
}

before('deploy', 'slack:before_deploy');
after('deploy', 'slack:after_deploy');


task('workers:stop', function () {
    run('cd {{release_path}} && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} php app/cli.php worker stop ParcelCreationWorker');
    run('cd {{release_path}} && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} php app/cli.php worker stop WaybillPrintingWorker');
    run('cd {{release_path}} && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} php app/cli.php worker stop InvoicePrintingWorker');
});


task('workers:start', function () {
    run('cd {{release_path}} && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} nohup php app/cli.php worker start ParcelCreationWorker &');
    run('cd {{release_path}} && APPLICATION_ENV={{APPLICATION_ENV}} AWS_KEY={{AWS_KEY}} AWS_SECRET={{AWS_SECRET}} BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} nohup php app/cli.php worker start WaybillPrintingWorker &');
    run('cd {{release_path}} && BEANSTALKD_HOST=localhost BEANSTALKD_PORT=11300 TNT_DB_HOST={{PHINX_DBHOST}} TNT_DB_USERNAME={{PHINX_DBUSER}} TNT_DB_PASSWORD={{PHINX_DBPASS}} TNT_DBNAME={{PHINX_DBNAME}} nohup php app/cli.php worker stop InvoicePrintingWorker &');
});


before('deploy', 'workers:stop');
after('deploy', 'workers:start');


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

