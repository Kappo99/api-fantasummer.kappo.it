<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use Firebase\JWT\JWT; // Assicurati di avere la libreria Firebase JWT installata

class AuthenticationMiddleware
{
    private $allowedAlgs = ['HS256'];

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Esegui la logica di autenticazione qui
        // Ad esempio, verifica la presenza di un token nell'header

        $token = $request->getHeaderLine('Authorization');

        if (empty($token)) {
            // Se il token è vuoto, l'autenticazione fallisce
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        // Verifica che il formato del token sia corretto (Bearer Token)
        if (!preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'Invalid token format']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        // Estrai il token dalla stringa di corrispondenza
        $token = $matches[1];

        try {
            // Decodifica il token JWT
            $decodedToken = JWT::decode($token, JWT_SECRET_KEY, $this->allowedAlgs);
            
            // Puoi aggiungere ulteriori controlli di autenticazione qui
            // Ad esempio, verifica se l'utente associato al token esiste nel tuo sistema

            // Aggiungi il token decodificato alla richiesta Slim
            $request = $request->withAttribute('token', $decodedToken);
            global $ID_ACCOUNT;
            $ID_ACCOUNT = $decodedToken->Id_Giocatore;
        
            // Se l'autenticazione ha successo, procedi con la gestione della richiesta
            $response = $handler->handle($request);
            return $response;
        } catch (Firebase\JWT\ExpiredException $e) {
            // Token scaduto
            $response = new SlimResponse();
            $httpResponse = new HttpResponse(Status::Unauthorized, "Token scaduto");
            $response->getBody()->write($httpResponse->send());
            return $response->withHeader('Content-Type', 'application/json')->withStatus($httpResponse->getStatusCode());
        } catch (Firebase\JWT\BeforeValidException $e) {
            // Token non ancora valido
            $response = new SlimResponse();
            $httpResponse = new HttpResponse(Status::Unauthorized, "Token non ancora valido");
            $response->getBody()->write($httpResponse->send());
            return $response->withHeader('Content-Type', 'application/json')->withStatus($httpResponse->getStatusCode());
        } catch (Firebase\JWT\SignatureInvalidException $e) {
            // Firma del token non valida
            $response = new SlimResponse();
            $httpResponse = new HttpResponse(Status::Unauthorized, "Firma Token non valida");
            $response->getBody()->write($httpResponse->send());
            return $response->withHeader('Content-Type', 'application/json')->withStatus($httpResponse->getStatusCode());
        }
        
    }
}
