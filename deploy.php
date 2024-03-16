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
set('writable_mode', 'chown');

// Hosts
host('18.188.54.27')
    ->setRemoteUser('ubuntu')
    ->set('branch', 'deploy-to-aws-ec2')
    ->set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader')
    ->set('deploy_path', '~/var/www/current');

// Hooks

after('deploy:failed', 'deploy:unlock');
