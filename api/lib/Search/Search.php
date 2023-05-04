<?php

namespace MintHCM\Lib\Search;

class Search
{

    public static function getManager($engine = null)
    {
        global $mint_config;

        $default_manager_name = $mint_config["search"]["default_engine"] ?? null;
        $default_manager = $default_manager_name && isset($mint_config["search"]["engines"][$default_manager_name]) ? self::getClass($default_manager_name) : null;
        $engine = self::getClass($engine);
        return $engine ? new $engine() : ($default_manager ? new $default_manager() : null);
    }

    protected static function getClass($class_name)
    {
        if (empty($class_name) || !is_string($class_name)) {
            return null;
        }
        if (class_exists("MintHCM\Custom\Lib\Search\\" . $class_name . '\\' . $class_name)) {
            return "MintHCM\Custom\Lib\Search\\" . $class_name . '\\' . $class_name;
        }
        if (class_exists("MintHCM\Lib\Search\\" . $class_name . '\\' . $class_name)) {
            return "MintHCM\Lib\Search\\" . $class_name . '\\' . $class_name;
        }
        return null;
    }
}
