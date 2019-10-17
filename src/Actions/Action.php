<?php

declare(strict_types=1);

namespace App\Actions;

use Psr\Http\Message\ResponseInterface;

abstract class Action
{
    protected function toJson(
        ResponseInterface $response,
        array $payloadData = [],
        string $message = 'ok',
        int $statusCode = 200
    ): ResponseInterface {
        $payload = [
            'code' => $statusCode,
            'message' => $message,
            'data' => $payloadData
        ];

        $json = json_encode($payload, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
