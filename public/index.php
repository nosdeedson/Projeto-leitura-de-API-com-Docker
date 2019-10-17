<?php

declare(strict_types=1);

use App\Handlers\ShutdownHandler;
use DI\ContainerBuilder;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Handlers\ErrorHandler;
use Slim\ResponseEmitter;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (!getenv('DEV_MODE')) { // Should be set to true in production
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings & dependencies
$settings = require __DIR__ . '/../config/settings.php';
$dependencies = require __DIR__ . '/../config/dependencies.php';

$containerBuilder->addDefinitions([
    'settings' => $settings
]);
$containerBuilder->addDefinitions($dependencies);
$container = $containerBuilder->build();

// Instantiate the app
$app = \DI\Bridge\Slim\Bridge::create($container);

$callableResolver = $app->getCallableResolver();

// Register routes
$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new ErrorHandler($callableResolver, $responseFactory);
$errorHandler->forceContentType('application/json');

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
