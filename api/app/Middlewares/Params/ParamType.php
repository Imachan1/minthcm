<?php

namespace MintHCM\Api\Middlewares\Params;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

abstract class ParamType
{
    public function __invoke(Request $request, $value, $required = false)
    {
        $this->runValidation($request, $value, $required);
        return $this->getValue($value, $required);
    }

    protected function runValidation($request, $value, $required = false)
    {
        if (($required && !isset($value)) || (isset($value) && !$this->validate($value))) {
            throw new HttpBadRequestException($request);
        }
    }

    protected function getValue($value, $required)
    {
        if (!isset($value) && !$required) {
            return null;
        }

        return $this->parseValue($value);
    }

    abstract protected function validate($value): bool;
    abstract protected function parseValue($value);
}
