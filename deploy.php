<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

// Project name
set('application', 'Laravel-auth');

// Project repo
set('repository', 'https://github.com/Ayenko4all/lara-basic-auth-api.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);
set('allow_anonymous_stats', false);
//set('writable_mode', 'acl');
//set('writable_chmod_mode', '0755');

// Hosts
host('18.188.104.26')
    ->setRemoteUser('ubuntu')
    ->set('branch', 'deploy-to-aws-ec2')
    ->set('deploy_path', '/var/www');

// Hooks
task('build', function () {
    run('cd {{release_path}} && build');
});

after('deploy:failed', 'deploy:unlock');
