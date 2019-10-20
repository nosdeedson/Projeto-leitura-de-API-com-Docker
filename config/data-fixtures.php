<?php

declare(strict_types=1);

//require 'vendor/autoload.php';
require_once(dirname(__FILE__)."/../vendor/autoload.php");
//$settings = require 'config/settings.php';
$settings = require_once(dirname(__FILE__)."/../config/settings.php");

use App\DbFixtures\BolsaFamiliaLoader;
use App\DbFixtures\LicitacaoLoader;
use App\DbFixtures\MunicipioLoader;
use App\Services\Transparencia;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;


$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $settings['doctrine.meta.entity_path'],
    (bool) $settings['doctrine.meta.auto_generate_proxies'],
    $settings['doctrine.meta.proxy_dir']
);

$em = EntityManager::create($settings['doctrine.connection'], $config);

$purger = new ORMPurger();
$executor = new ORMExecutor($em, $purger);

$guzzleClient = new Client([
    'base_uri' => Transparencia::BASE_URI,
    'timeout'  => 10.0,
    'headers' => [
        'Accept' => 'application/json'
    ]
]);

$transparencia = new Transparencia($guzzleClient);

$loader = new Loader();

// Munícipios
$loader->addFixture(new MunicipioLoader());


// Bolsa Família nos anos 2016 a 2018 em Itajubá e Pouso Alegre
//descomentar linha abaixo
$loader->addFixture(new BolsaFamiliaLoader($transparencia, [2016, 2017, 2018], [3132404, 3152501]));

// licitações na Unifei nos anos 2016 a 2018 
$loader->addFixture(new LicitacaoLoader($transparencia, "26261", ['2016', '2017', '2018'], "01", "31", "3132404"));

$executor->execute($loader->getFixtures());
