<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configs/config.php';

require_once __DIR__ . '/include/utilities.php';

require_once __DIR__ . '/include/model.php';

// Inizializza il tuo framework o gestisci le richieste manualmente
$app = AppFactory::create();

// Aggiungi il middleware di gestione degli errori
$app->add(new ErrorMiddleware());

//* Add CORS Middleware for preloader request
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Includi le rotte per gli utenti
require_once __DIR__ . '/include/controllers.php';

$app->run();

