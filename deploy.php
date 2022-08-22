<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:cbrooney/bruni-home.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('raspberrypi')
    ->set('remote_user', 'pi')
    ->set('deploy_path', '/home/pi/Public/bruni-home/bruni_home/2022-08-22');

// Hooks
after('deploy:failed', 'deploy:unlock');
