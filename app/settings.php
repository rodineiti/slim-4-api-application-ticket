<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'db_dev' => [
                'driver' => 'mysql',
                'host' => '',
                'database' => '',
                'username' => '',
                'password' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
            ],
            'db_prod' => [
                'driver' => 'mysql',
                'host' => '',
                'database' => '',
                'username' => '',
                'password' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
            ],
            'secretKey' => 'Su4C7dLUB9cXMgY5',
            'secretToken' => 'Su4C7dLUB9cXMgY5',
            'send_mail' => [
                'add_chamado' => false,
                'edit_chamado' => false,
                'del_chamado' => false,
                'reply_chamado' => false,
            ]
        ],
    ]);
};
