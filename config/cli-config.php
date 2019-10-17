<?php

use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\ORM\EntityManager;

require 'vendor/autoload.php';

$settings = require 'settings.php';

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $settings['doctrine.meta.entity_path'],
    (bool) $settings['doctrine.meta.auto_generate_proxies'],
    $settings['doctrine.meta.proxy_dir']
);

$platform = new \Doctrine\DBAL\Platforms\MySQL57Platform();
$settings['doctrine.connection']['platform'] = $platform;
$em = EntityManager::create($settings['doctrine.connection'], $config);

// Migrations Configuration
$connection = $em->getConnection();
$configuration = new Configuration($connection);

$configuration->setName('migration');
$configuration->setMigrationsNamespace('App\Migrations');
$configuration->setMigrationsTableName('doctrine_migration_versions');
$configuration->setMigrationsColumnName('version');
$configuration->setMigrationsDirectory(__DIR__ . '/../src/Migrations');
$configuration->setCheckDatabasePlatform(true);
$configuration->setAllOrNothing(true);

return new HelperSet([
    'em' => new EntityManagerHelper($em),
    'db' => new ConnectionHelper($connection),
    new ConfigurationHelper($connection, $configuration)
]);
