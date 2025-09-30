<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

#[\AllowDynamicProperties]
class PositionsController
{
    public function getCompetencies(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $recordId = $request->getAttribute('record_id');
        
    }

    public function getResponsibilities(Request $request, Response $response, array $args): Response
    {

    }

}