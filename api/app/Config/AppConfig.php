<?php

namespace MintHCM\Api\Config;

class AppConfig
{

    public static function getBasePath()
    {
        //TODO Check if instance path is in config -> after rebuild config manager
        $appBasePath = "/api";
        $currentDirectoryName = dirname($_SERVER['PHP_SELF']);
        if (str_contains($currentDirectoryName, '/api') && $appBasePath != $currentDirectoryName) {
            $appBasePath = $currentDirectoryName;
        }
        return $appBasePath;
    }
}
