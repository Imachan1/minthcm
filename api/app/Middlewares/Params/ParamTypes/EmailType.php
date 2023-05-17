<?php

namespace MintHCM\Api\Middlewares\Params\ParamTypes;

use MintHCM\Api\Middlewares\Params\ParamType;

class EmailType extends ParamType
{
    protected function validate($value): bool
    {
        $regexmail = "/^\w+(['\.\-\+]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+\$/";
        return is_string($value) && preg_match($regexmail, $value);
    }

    protected function parseValue($value)
    {
        return $value;
    }
}
