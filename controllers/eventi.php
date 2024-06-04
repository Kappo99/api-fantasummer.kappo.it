<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Crea un'applicazione per gli utenti
$app->group('/eventi', function ($group) {

    // GET /eventi
    $group->get('', function (Request $request, Response $response) {
        $eventi = Evento::getEventi();

        for ($i = 0; $i < count($eventi); $i++) {
            $eventi[$i] = $eventi[$i]->toArray();
        }

        if ($eventi)
            $httpResponse = new HttpResponse(
                Status::Ok,
                "GET all eventi",
                array('eventi' => $eventi)
            );
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found eventi");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

})/* ->add(new AuthenticationMiddleware()) */ ; //* Aggiungi il Middleware di autenticazione a tutto il gruppo
