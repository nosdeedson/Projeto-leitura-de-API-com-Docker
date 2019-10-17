<?php

declare(strict_types=1);

use Monolog\Logger;

use function DI\get;

return [
    // display errors
    'displayErrorDetails' => true,
    // logger
    'logger.name' => getenv('APP_NAME'),
    'logger.path' => __DIR__ . '/../logs/app.log',
    'logger.level' => Logger::DEBUG,
    // orm
    'doctrine.meta.entity_path' => [
        __DIR__ . '/../src/Entities'
    ],
    'doctrine.meta.auto_generate_proxies' => getenv('DEV_MODE'),
    'doctrine.meta.proxy_dir' =>  __DIR__ . '/../var/cache/DoctrineORM/proxies',
    'doctrine.meta.cache' => !getenv('DEV_MODE'),
    // Connection
    'doctrine.connection' => [
        'driver' => 'pdo_mysql',
        'host' => getenv('DB_HOST'),
        'port' => getenv('DB_PORT'),
        'dbname' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
        'charset' => 'utf8mb4',
    ],
];