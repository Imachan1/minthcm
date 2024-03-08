<?php

namespace MintHCM\Firebase;

class Config
{
    protected $config;

    public function __construct()
    {
        if (file_exists("custom/modules/Connectors/connectors/sources/ext/eapm/firebase/config.php")) {
            include 'custom/modules/Connectors/connectors/sources/ext/eapm/firebase/config.php';
            $config = $config['properties'] ?? [];
        } else {
            $GLOBALS['log']->error("WARNING: Firebase - default_config used");
            if (file_exists("include/Integrations/Firebase/Config/default_config.php")) {
                include 'include/Integrations/Firebase/Config/default_config.php';
            }
        }
        if (!isset($config)) {
            $GLOBALS['log']->fatal("FATAL: Firebase - config not found for minthcm mobile");
            exit();
        }
        if (empty($config['server_key'])) {
            $GLOBALS['log']->fatal("FATAL: Firebase - missing server_key for minthcm mobile");
        }
        $this->config = $config;
    }

    public function get($param, $default = '')
    {
        return $this->config[$param] ?? $default;
    }
}
