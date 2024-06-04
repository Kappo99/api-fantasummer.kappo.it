<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Crea un'applicazione per gli utenti
$app->group('/classifica', function ($group) {

    // GET /classifica
    $group->get('', function (Request $request, Response $response, $args) {
        $classifica = Classifica::getClassifica();

        for ($i = 0; $i < count($classifica); $i++) {
            $classifica[$i] = $classifica[$i]->toArray();
        }

        if ($classifica)
            $httpResponse = new HttpResponse(
                Status::Ok,
                "GET all classifica",
                array ('classifica' => $classifica)
            );
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found classifica");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

    // GET /classifica/{giornata}
    $group->get('/{giornata}', function (Request $request, Response $response, $args) {
        $giornata = $args['giornata'];
        $result = Classifica::getClassificaByGiornata($giornata);

        $classifica = $result[0];
        $classificaPrev = $result[1];

        for ($i = 0; $i < count($classifica); $i++) {
            $classifica[$i] = $classifica[$i]->toArray();
        }

        for ($i = 0; $i < count($classificaPrev); $i++) {
            $classificaPrev[$i] = $classificaPrev[$i]->toArray();
        }

        if ($classifica)
            $httpResponse = new HttpResponse(
                Status::Ok,
                "GET all classifica",
                array ('classifica' => $classifica, 'classificaPrev' => $classificaPrev)
            );
        else
            $httpResponse = new HttpResponse(Status::NotFound, "Not Found classifica");

        $response->getBody()->write($httpResponse->send());
        $response = $response->withStatus($httpResponse->getStatusCode());
        return $response;
    });

})/* ->add(new AuthenticationMiddleware()) */ ; //* Aggiungi il Middleware di autenticazione a tutto il gruppo
