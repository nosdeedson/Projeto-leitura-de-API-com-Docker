<?php

namespace App\Actions;

use App\Services\Db\Municipio as AppMunicipioService;
use Psr\Http\Message\ResponseInterface;

class Municipio extends Action
{
    private $mService;

    public function __construct(AppMunicipioService $mService)
    {
        $this->mService = $mService;
    }

    public function __invoke(ResponseInterface $response)
    {
        $data = $this->mService->findAll();

        return $this->toJson($response, $data);
    }
}