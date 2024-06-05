<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Crea un'applicazione per gli utenti
$app->group('/giocatori', function ($group) {

    // GET /giocatori
    $group->get('', function (Request $request, Response $response) {

        $httpResponse = new HttpResponse(Status::NotImplemented, "GET all giocatori");
        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // GET /giocatori/{id}
    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $giocatoreId = $args['id'];
        $giocatore = Giocatore::getGiocatoreById($giocatoreId);

        if ($giocatore)
            $httpResponse = new HttpResponse(Status::Ok, "GET giocatore with Id: $giocatoreId", $giocatore->toArray());
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found giocatore with Id: $giocatoreId");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // GET /giocatori/{id}
    $group->get('/{id}/eventi', function (Request $request, Response $response, $args) {
        $giocatoreId = $args['id'];
        $eventi = Evento::getEventiByIdGiocatore($giocatoreId);

        for ($i = 0; $i < count($eventi); $i++) {
            $eventi[$i] = $eventi[$i]->toArray();
        }

        if ($eventi)
            $httpResponse = new HttpResponse(Status::Ok, "GET eventi of giocatore with Id: $giocatoreId", array('eventi' => $eventi));
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found eventi of giocatore with Id: $giocatoreId");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

})->add(new AuthenticationMiddleware()) ; //* Aggiungi il Middleware di autenticazione a tutto il gruppo
