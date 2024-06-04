<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Crea un'applicazione per gli utenti
$app->group('/summertori', function ($group) {

    // GET /summertori
    $group->get('', function (Request $request, Response $response) {

        $httpResponse = new HttpResponse(Status::NotImplemented, "GET all summertori");
        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // GET /summertori/{id}
    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $articleId = $args['id'];
        $giocatore = Giocatore::getGiocatoreById($articleId);

        if ($giocatore)
            $httpResponse = new HttpResponse(Status::Ok, "GET giocatore with Id: $articleId", $giocatore->toArray());
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found giocatore with Id: $articleId");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

})->add(new AuthenticationMiddleware()) ; //* Aggiungi il Middleware di autenticazione a tutto il gruppo
