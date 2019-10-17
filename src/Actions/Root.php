<?php

declare(strict_types=1);

namespace App\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class Root extends Action
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $message = 'Welcome from ' . static::class;
        $this->logger->debug($message);

        return $this->toJson($response, [], $message);
    }
}
