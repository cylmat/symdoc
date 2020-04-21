<?php

namespace Deployer;

require 'recipe/common.php';

/**
 * Deployer
 * https://deployer.org
 * 
 * Config
 * https://deployer.org/docs/configuration.html
 */
/* 
 * Will create on host:
 *   releases/ contains releases dirs,
 *   share/    contains shared files and dirs,
 *   current/  symlink to current release.
 */

// $REPOSITORY https://user:pass@git-repo.com
$REPOSITORY = getenv('REPOSITORY');
// $REMOTE_HOST user@ssh.host.com
$REMOTE_HOST = getenv('REMOTE_HOST');

/**
 * CLI usage
 *
 * dep config:current
 * dep config:dump
 * dep config:hosts
 * dep deploy (--tag="v0.1") (--revision="5daefb59edbaa75")
 * dep deploy prod --hosts prod:pre:local --roles build,test_role
 * dep my_custom_task
 * dep rollback
 * dep run '<shell_command>'
 * dep ssh (connect to host)
 * 
 * dep help deploy
 */

/*********
 * Params
 */

// MAIN PARAMS //

// Project name
set('application', 'symfony');
set('default_stage', 'prod');

// Stage
set('target_directory', '~/');
set('deploy_path', '{{target_directory}}{{application}}'); 

// Repository //
set('branch', 'main');
set('repository', $REPOSITORY);

// OTHERS //
set('allow_anonymous_stats', false);
set('composer_action', 'update'); //default install
set('keep_releases', 10);

// Shared files/dirs between deploys 
set('shared_dirs', ['vendor', 'var']);

/********
 * Hosts sample
 * https://deployer.org/docs/hosts.html
 */

host('prod')
    ->hostname($REMOTE_HOST)
    ->set('deploy_path', '~/{{application}}')
    ->set('env', [
        'REPOSITORY' => $REPOSITORY,
        'APP_ENV' => 'prod'
    ])
    ->port(22)
    ->forwardAgent(true)
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no');

/********
 * Tasks
 * https://deployer.org/docs/tasks.html
 */

// Simlink host .env //
task('link:env', function () {
    $src_env = '{{deploy_path}}/.env';
    $target_env = "{{release_path}}/.env";
    
    if (test("test -f $src_env"))
        run("ln -s $src_env $target_env");
});

// Manually upload local files //
task('local:upload', function () {
    upload(__DIR__ . "/", '{{release_path}}');
});

// Run tasks //

$upload = ['deploy:update_code', 'local:upload'][1];

desc('Deploy your project');
task('deploy', [
    'deploy:unlock',

    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    $upload,
    'deploy:shared',
    //'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'link:env',
    'deploy:unlock',
    'cleanup',
    'success'
]);

/*********
 * Orders
 */

// [Optional] If deploy fails automatically unlock.
//after('deploy:failed', 'deploy:unlock'); // run after task, can be "before"
fail('*', 'deploy:unlock');

/**********************
 *  PREFIX PREVIOUS   *
 * Avoid host caching *
 *********************/
before('cleanup', 'prefix_previous');
before('rollback', 'unprefix_previous_rollback');

task('prefix_previous', function () {
    if (!isset(get('releases_list')[1])) return;
    $previous = get('releases_list')[1];
    $path = "{{deploy_path}}/releases";

    if (test("test -d $path/$previous")) {
        if (test("test -d $path/_$previous")) {
            run("rm -rf $path/_$previous");
        }
        run("mv $path/$previous $path/_$previous");
    }
});

task('unprefix_previous_rollback', function () {
    $path = "{{deploy_path}}/releases";

    // unprefix all
    run('cd '.$path.' && for x in $(ls -1 | grep "_"); do mv $x $(echo $x | sed -e "s/_//"); done;');
    if (!isset(get('releases_list')[1])) return;
    $previous = get('releases_list')[1];

    if (test("test -d $path/_$previous")) {
        if (test("test -d $path/$previous")) {
            run("rm -rf $path/$previous");
        }
        run("mv $path/_$previous $path/$previous");
    }
});