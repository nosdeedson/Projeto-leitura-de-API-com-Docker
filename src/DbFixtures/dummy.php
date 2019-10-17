<?php

use App\DbFixtures\LicitacaoLoader;
use App\Services\Transparencia;
use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Client;


use GuzzleHttp\Exception\ConnectException;

require_once(dirname(__FILE__)."/LicitacaoLoader.php");
require_once(dirname(__FILE__)."/../Services/Transparencia.php");
require_once(dirname(__FILE__)."/../../vendor/guzzlehttp/guzzle/src/Client.php");

$c = new Client();
$t = new Transparencia(new  $c);
$l = new LicitacaoLoader($t, '26261', [2016,2017,2018], '01','31','3132404');

print_r($l->getAnos());

$aux = $l->load( objectManager);
print_r($aux);

?>