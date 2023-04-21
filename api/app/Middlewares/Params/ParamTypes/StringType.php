<?php

namespace MintHCM\Api\Middlewares\Params\ParamTypes;

use MintHCM\Api\Middlewares\Params\ParamType;

class StringType extends ParamType
{
    protected function validate($value): bool
    {
        return is_string($value);
    }

    protected function parseValue($value)
    {
        return $value;
    }
}
