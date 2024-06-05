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
    $group->get('/{id:[0-9]+}', function (Request $request, Response $response, $args) {
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

    // GET /giocatori/{id}/eventi
    $group->get('/{id:[0-9]+}/eventi', function (Request $request, Response $response, $args) {
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

    // GET /giocatori/eventi
    $group->get('/eventi', function (Request $request, Response $response) {
        $result = Evento::getEventiByGiocatori();

        $giocatori = array();
        foreach ($result as $r) {
            if (!isset ($giocatori[$r['giocatore']->getId()]))
                $giocatori[$r['giocatore']->getId()] = array ('giocatore' => $r['giocatore']->toArray());
            if (!isset ($giocatori[$r['giocatore']->getId()]['eventi']))
                $giocatori[$r['giocatore']->getId()]['eventi'] = array ();
            $giocatori[$r['giocatore']->getId()]['eventi'][] = $r['evento']->toArray();
        }

        if ($giocatori)
            $httpResponse = new HttpResponse(Status::Ok, "GET eventi of giocatori", $giocatori);
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found eventi of giocatori");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // PUT /giocatori/{idGiocatore}/eventi/{idEvento}
    $group->put('/{idGiocatore:[0-9]+}/eventi/{idEvento:[0-9]+}', function (Request $request, Response $response, $args) {
        $idGiocatore = $args['idGiocatore'];
        $idEvento = $args['idEvento'];
        $updated = Evento::updateIsCompletatoByGiocatore($idEvento, $idGiocatore);

        // if ($updated > 0)
            $httpResponse = new HttpResponse(
                Status::Ok,
                "UPDATE Evento $idEvento",
                $updated
            );
        // else
        //     $httpResponse = new HttpResponse(Status::InternalServerError, "Not UPDATE Evento $idEvento");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    })->add(new AuthenticationMiddleware());

    // GET /giocatori/classifica
    $group->get('/classifica', function (Request $request, Response $response) {
        $classifica = Giocatore::getClassifica();

        for ($i = 0; $i < count($classifica); $i++) {
            $classifica[$i] = $classifica[$i]->toArray();
        }

        if ($classifica)
            $httpResponse = new HttpResponse(Status::Ok, "GET classifica", array('giocatori' => $classifica));
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Cannot calculate classifica");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

})->add(new AuthenticationMiddleware()) ; //* Aggiungi il Middleware di autenticazione a tutto il gruppo
