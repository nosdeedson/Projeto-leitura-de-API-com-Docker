<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class Transparencia
{
    const BASE_URI = 'http://www.transparencia.gov.br';

    private $client;

    public function  __construct(Client $client)
    {
        $this->client = $client;
    }

    public function searchBolsaFamilia(string $yearMonth, string $ibgeCityCode, int $page)
    {
        $response = $this->request('GET', '/bolsa-familia-por-municipio', [
            'mesAno' => $yearMonth,
            'codigoIbge' => $ibgeCityCode,
            'pagina' => $page
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        };

        throw new Exception("Erro ao consultar dados do bolsa família: " . $response->getBody());
    }

    // adicionei aqui a função de consulta de licitações
    public function searchLicitacao( string $dataInicial, String $dataFinal, string $siafiCityCode, int $page)
    {
        $response = $this->request('GET', '/licitacoes', [
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal,
            'codigoOrgao' => $siafiCityCode,
            'pagina' => $page
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        };

        throw new Exception("Erro ao consultar dados de licitações do Poder Executivo Federal: " . $response->getBody());
    }


    private function request(string $method, string $path, array $data)
    {
        $options = [];

        switch ($method) {
            case 'GET':
                $options['query'] = $data;
                break;
            default:
                throw new Exception("Invalid method '{$method}'");
        }

        try {
            return $this->client->request($method, "/api-de-dados{$path}", $options);
        } catch (ConnectException $e) {
            throw new Exception('Não foi possível estabelecer conexão com a API');
        }
    }
}
