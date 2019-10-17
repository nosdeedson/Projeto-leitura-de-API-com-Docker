<?php

declare(strict_types=1);

use App\Actions\BolsaFamilia\BolsaFamiliaMes;
use App\Actions\Municipio;
use App\Actions\Root;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

/**
 * Para mais informações de como criar rotas consulte a documentação do Slim:
 *
 * http://www.slimframework.com/docs/v4/objects/routing.html
 *
 */
return function (App $app) {
    // acessado via GET http://localhost:8888
    $app->get('/', Root::class);

    $app->group('/municipio', function(Group $group) {

        // acessado via GET http://localhost:8888/municipio
        $group->get('', Municipio::class);

        $group->group('/{codigoIbge:[0-9]+}', function(Group $group) {
            // pode ser acessado via GET http://localhost:8888/municipio/{codigoIbge}/bolsa-familia
            $group->get('/bolsa-familia', BolsaFamiliaMes::class);

            // pode ser acessado via GET http://localhost:8888/municipio/{codigoIbge}/licitacao
            // $group->get( ...
        });
    });
};
