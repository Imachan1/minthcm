<?php

namespace MintHCM\Api\Middlewares\Params\ParamTypes;

use MintHCM\Api\Middlewares\Params\ParamType;

class ArrayType extends ParamType
{
    protected function validate($value): bool
    {
        return is_string($value) || is_array($value);
    }

    protected function parseValue($value)
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        return $value;
    }
}
