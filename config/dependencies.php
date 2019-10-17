<?php

declare(strict_types=1);

use App\Services\Transparencia;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function DI\factory;

return [
    LoggerInterface::class => function (ContainerInterface $c) {
        $settings = $c->get('settings');
        $logger = new Logger($settings['logger.name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($settings['logger.path'], $settings['logger.level']);
        $logger->pushHandler($handler);

        return $logger;
    },
    Client::class => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => Transparencia::BASE_URI,
            'timeout'  => 10.0,
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
    },
    EntityManager::class => factory(function (ContainerInterface $c) {
        $settings = $c->get('settings');

        $cache = $settings['doctrine.meta.cache'] ? new \Doctrine\Common\Cache\ApcuCache() : null;

        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
            $settings['doctrine.meta.entity_path'],
            $settings['doctrine.meta.auto_generate_proxies'],
            $settings['doctrine.meta.proxy_dir'],
            $cache,
            false
        );

        return EntityManager::create($settings['doctrine.connection'], $config);
    }),
];
