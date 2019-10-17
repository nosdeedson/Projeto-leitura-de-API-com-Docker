<?php

declare(strict_types=1);

namespace App\Actions\BolsaFamilia;

use App\Actions\Action;
use App\Services\Db\BolsaFamilia;
use DateTime;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BolsaFamiliaMes extends Action
{
    private $bolsaFamiliaService;

    public function __construct(BolsaFamilia $bolsaFamiliaService)
    {
        $this->bolsaFamiliaService = $bolsaFamiliaService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $codigoIbge)
    {
        $params = $this->validate($request->getQueryParams());
        $results = $this->bolsaFamiliaService->findBetweenDates($params['data_inicial'], $params['data_final'], $codigoIbge);

        return $this->toJson($response, $results);
    }

    private function validate(array $params)
    {
        if (empty($params['data_inicial'])) {
            throw new Exception('Data inicial não informada');
        }

        if (empty($params['data_final'])) {
            throw new Exception('Data final não informada');
        }

        $dataInicial = DateTime::createFromFormat('d/m/Y', $params['data_inicial']);

        if (!$dataInicial) {
            throw new Exception("Data inicial '{$dataInicial}' não é válida");
        }

        $dataFinal = DateTime::createFromFormat('d/m/Y', $params['data_final']);

        if (!$dataFinal) {
            throw new Exception("Data final '{$dataFinal}' não é válida");
        }

        return [
            'data_inicial' => $dataInicial,
            'data_final' => $dataFinal,
        ];
    }
}
