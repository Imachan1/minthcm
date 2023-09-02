<?php

namespace MintHCM\Api\Middlewares\Params\ParamTypes;

use MintHCM\Api\Middlewares\Params\ParamType;

class IntType extends ParamType
{
    protected function validate($value): bool
    {
        return is_int($value) || preg_match('/^-?\d+$/', $value);
    }

    protected function parseValue($value)
    {
        return (int)$value;
    }
}
