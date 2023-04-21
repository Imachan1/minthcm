<?php

namespace MintHCM\Api\Middlewares\Params\ParamTypes;

use MintHCM\Api\Middlewares\Params\ParamType;

class BoolType extends ParamType
{
    protected function validate($value): bool
    {
        return
        is_bool($value)
            || (is_string($value) && in_array(strtolower($value), ['1', '0', 'true', 'false']))
            || (is_numeric($value) && in_array($value, [1, 0]))
        ;
    }

    protected function parseValue($value)
    {
        if (!is_string($value)) {
            return (bool) $value;
        }

        return in_array(strtolower($value), ['1', 'true']) ? true : false;
    }
}
