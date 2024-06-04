<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Crea un'applicazione per gli utenti
$app->group('/summerbonus', function ($group) {

    // GET /summerbonus
    $group->get('', function (Request $request, Response $response, $args) {
        $summerbonus = SummerBonus::getSummerBonus();

        for ($i = 0; $i < count($summerbonus); $i++) {
            $summerbonus[$i] = $summerbonus[$i]->toArray();
        }

        if ($summerbonus)
            $httpResponse = new HttpResponse(
                Status::Ok,
                "GET all summerbonus",
                array ('summerbonus' => $summerbonus)
            );
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found summerbonus");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

})/* ->add(new AuthenticationMiddleware()) */ ; //* Aggiungi il Middleware di autenticazione a tutto il gruppo
