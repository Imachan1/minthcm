<?php

namespace MintHCM\Api\ExceptionHandlers\Doctrine;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class DoctrineConnectionExceptionHandler
{
    public function __invoke(
        Request $request,
        \Doctrine\DBAL\Exception\ConnectionException $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response
    {
        $response = (new Response)->withStatus(500);
        $response->getBody()->write(json_encode([
            'message' => 'Doctrine: Database connection error',
        ], JSON_PRETTY_PRINT));
        return $response;
    }
}
