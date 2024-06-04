<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Crea un'applicazione per gli utenti
$app->group('/eventi', function ($group) {

    // GET /eventi
    $group->get('', function (Request $request, Response $response) {

        $httpResponse = new HttpResponse(Status::NotImplemented, "GET all eventi");
        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // GET /eventi/{giornata}
    $group->get('/{giornata}', function (Request $request, Response $response, $args) {
        $giornata = $args['giornata'];
        $result = Evento::getEventiByGiornata($giornata);

        $eventi = $result[0];
        $count = $result[1];
        $numSummerte = $result[2];

        for ($i = 0; $i < count($eventi); $i++) {
            $eventi[$i] = $eventi[$i]->toArray();
        }

        if ($eventi)
            $httpResponse = new HttpResponse(
                Status::Ok,
                "GET all eventi with Giornata: $giornata",
                array ('eventi' => $eventi, 'count' => $count, 'numSummerte' => $numSummerte)
            );
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found eventi with Giornata: $giornata");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // GET /eventi/{id}/count
    $group->get('/{id}/count', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $count = Evento::getEventiCountById($id);

        $httpResponse = new HttpResponse(
            Status::Ok,
            "GET eventi Count with Id: $id",
            array ('count' => $count)
        );

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // GET /eventi/{id}
    $group->put('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $updated = Evento::updateIsSummertaById($id);

        if ($updated > 0)
            $httpResponse = new HttpResponse(
                Status::Ok,
                "UPDATE Evento $id isSummerta status",
                $updated
            );
        else
            $httpResponse = new HttpResponse(Status::InternalServerError, "Not UPDATE Evento $id isSummerta status");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    })->add(new AuthenticationMiddleware());

})/* ->add(new AuthenticationMiddleware()) */ ; //* Aggiungi il Middleware di autenticazione a tutto il gruppo
