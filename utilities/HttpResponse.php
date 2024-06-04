<?php

class HttpResponse
{
    private $statusCode;
    private $message;
    private $body;

    function __construct (int $statusCode, string $message, $body=null) {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->body = $body;
    }

    function __toString () {
        return json_encode(["message" => $this->message, "body" => $this->body]);
    }

    function send (): string {
        return $this->__toString();
    }

    function getStatusCode (): int {
        return $this->statusCode;
    }
}

// abstract class Status
// {
//     const Ok = "200 OK";
//     const Created = "201 Created";
//     const Accepted = "202 Accepted";
//     const BadRequest = "400 Bad Request";
//     const Unauthorized = "401 Unauthorized";
//     const Forbidden = "403 Forbidden";
//     const NotFound = "404 Not Found";
//     const MethodNotAllowed = "405 Method Not Allowed";
//     const Conflict = "409 Conflict";
//     const UnprocessableEntity = "422 Unprocessable Entity";
//     const InternalServerError = "500 Internal Server Error";
//     const NotImplemented = "501 Not Implemented";
// }

abstract class Status
{
    const Ok = 200;
    const Created = 201;
    const Accepted = 202;
    const BadRequest = 400;
    const Unauthorized = 401;
    const Forbidden = 403;
    const NotFound = 404;
    const MethodNotAllowed = 405;
    const Conflict = 409;
    const UnprocessableEntity = 422;
    const InternalServerError = 500;
    const NotImplemented = 501;
}
