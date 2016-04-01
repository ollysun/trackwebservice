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

env('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');

task('deploy:run_migrations', function () {
    run('cd {{release_path}} PHINX_DBHOST={{PHINX_DBHOST}} PHINX_DBUSER={{PHINX_DBUSER}} PHINX_DBPASS={{PHINX_DBPASS}} PHINX_DBNAME={{PHINX_DBNAME}} php vendor/bin/phinx migrate -e {{APPLICATION_ENV}}
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


set('repository', 'git@bitbucket.org:cottacush/tnt-service.git');

//slack tasks
//task('slack:before_deploy', function () {
//    postToSlack('Starting deploy on ' . env('server.name') . '...');
//});
//
//task('slack:after_deploy', function () {
//    postToSlack('Deploy to ' . env('server.name') . ' done');
//});
//
//function postToSlack($message)
//{
//    runLocally('curl -s -S -X POST --data-urlencode payload="{\"channel\": \"#courier_plus_dev\", \"username\": \"T+ testing Service Release Bot\", \"text\": \"' . $message . '\"}" https://hooks.slack.com/services/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');
//}
//
//before('deploy', 'slack:before_deploy');
//after('deploy', 'slack:after_deploy');
