<?php

namespace MintHCM\Api\Middlewares\Params;

use MintHCM\Api\Middlewares\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

abstract class ParamType
{
    public function __invoke($request, $value, $required = false)
    {
        $this->runValidation($request, $value, $required);
        return $this->getValue($value, $required);   
    }

    protected function runValidation($request, $value, $required = false)
    {
        if(($required && empty($value)) || (!empty($value) && !$this->validate($value))) {
            throw new HttpBadRequestException($request);
        }
    }

    protected function getValue($value, $required)
    {
        if(empty($value) && !$required) return null;
        return $this->parseValue($value);
    }

    abstract protected function validate($value) :bool;
    abstract protected function parseValue($value);
}
