<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:Naskalin/veresk-landshaft.git');
set('clear_paths', ['deploy.php']);
set('keep_releases', 2);
set('writable_mode', 'chmod');
set('shared_files', ['.env.local']);
set('ssh_multiplexing', false);
set('shared_dirs', [
    'var/log',
    'var/sessions',
]);
set('bin/php', '/usr/local/php/cgi/8.1/bin/php');
set('bin/composer', function () {
    return '/usr/local/php/cgi/8.1/bin/php ~/.local/bin/composer';
});
set('git_tty', true);
add('writable_dirs', []);

// Hosts

host('max13nue.beget.tech')
    ->setRemoteUser('max13nue')
    ->setIdentityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '~/veresk-landshaft-new');

task('beget:symlink', static function () {
    run('{{bin/symlink}} {{deploy_path}}/current/public {{deploy_path}}/public_html');
});

task('npm:prod', static function () {
    exec('npm run build');
    upload('./public/build', '{{release_path}}/public');
});

// Hooks

after('deploy:failed', 'deploy:unlock');
after('deploy:symlink', 'beget:symlink');
after('deploy:update_code', 'npm:prod');
