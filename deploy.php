<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:cbrooney/bruni-home.git');

// overwrite current path for symlink
set('current_path', '{{deploy_path}}/bruni_home');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('raspberrypi')
    ->set('remote_user', 'pi')
    ->set('deploy_path', '/home/pi/Public/bruni-home')
    ->set('branch', 'deployer');

// Hooks
after('deploy:failed', 'deploy:unlock');

// overwrite installing vendors
task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        warning('To speed up composer installation setup "unzip" command with PHP zip extension.');
    }

    run('cd {{release_or_current_path}} && {{bin/php}} composer.phar install --verbose --no-interaction --optimize-autoloader 2>&1');
});
