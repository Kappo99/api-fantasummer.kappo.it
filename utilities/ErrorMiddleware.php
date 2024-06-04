<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class ErrorMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        try {
            // Esegui la richiesta e gestisci eventuali eccezioni
            $method = $request->getMethod();
            $uri = $request->getUri()->getPath();
            $params = $request->getParsedBody() ? json_encode($request->getParsedBody(), JSON_PRETTY_PRINT) : null;
            $state = Log_State::Iniziato;

            $log = new Log(null, $method, $uri, $params, $state);
            $log_id = Log::addLog($log);

            $response = $handler->handle($request)->withHeader('Content-Type', 'application/json');

            // $error = $response->getStatusCode() != 200 ? $response->getBody()->getContents() : null;
            // TODO: Aggiornare il LOG con Error_Log se StatusCode !== 200
            $result = $this->updateLogState($log_id, Log_State::Terminato);
        } catch (Exception $e) {
            // Cattura l'eccezione e crea una risposta di errore personalizzata
            $statusCode = $e->getCode() ?: 500;
            $httpResponse = new HttpResponse($statusCode, $e->getMessage());
            file_put_contents('exceptionLog.txt', date('Y-m-d H:i:s') . "\n" . $e->getMessage() . "\n\n", FILE_APPEND);

            // Log dell'eccezione
            try {
                $result = $this->updateLogState($log_id, Log_State::Errore, $e->getMessage());
            } catch (Exception $e) {
                file_put_contents('exceptionLog.txt', date('Y-m-d H:i:s') . "\n" . $e->getMessage() . "\n\n", FILE_APPEND);
            }

            // Creazione di una nuova risposta JSON utilizzando Slim\Psr7\Response
            $response = new SlimResponse();
            $response->getBody()->write($httpResponse->send());
            $response = $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
        }

        return $response;
    }

    private function updateLogState(int $id, string $state, ?string $error = null)
    {
        $log = Log::getLogById($id); //* Recupero il record aggiornato nel caso AuthenticationMiddleware abbia aggiornato Id_Giocatore_Log
        global $ID_ACCOUNT;
        if (isset($ID_ACCOUNT))
            $log->setIdGiocatore($ID_ACCOUNT);
        $log->setState($state);
        if ($error !== null)
            $log->setError($error);
        return Log::updateLog($log);
    }
}
