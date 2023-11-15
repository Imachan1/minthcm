<?php

namespace MintHCM\Install\Helpers;

use MintHCM\MintCLI\Installer\Installer;

class ConfigHelper extends Installer
{
    const INSTANCE_DIR = '/../../../legacy';
    const CLI_DIR = '/../../../legacy/MintCLI/src';

    protected $rootDirectory;
    protected $serverService;
    protected $htaccessService;

    public function prepareSystemConfiguration()
    {
        $cliConfigPath = __DIR__ . self::CLI_DIR . '/Assets/config_si.php';
        $instanceConfigPath = __DIR__ . self::INSTANCE_DIR . '/config_si.php';
        copy($cliConfigPath, $instanceConfigPath);

        $config = file_get_contents($instanceConfigPath);
        $config = str_replace('_SETUP_SYSTEM_NAME_', 'MintHCM', $config);
        file_put_contents($instanceConfigPath, $config);
    }

    public function addDatabaseInfoToConfig($data)
    {
        $configData = [
            '_DB_HOST_' => $data['databaseHost'],
            '_DB_PORT_' => $data['databasePort'],
            '_DB_USER_' => $data['databaseUsername'],
            '_DB_PASSWORD_' => $data['databasePassword'],
            '_DB_NAME_' => $data['databaseName'],
            '_DB_COLLATION_' => $data['databaseCollation'],
        ];

        $this->changeConfigFileInfo($configData);
    }

    public function addSystemInfoToConfig($data)
    {
        $configData = [
            '_INSTALL_DD_' => $data['demoData'] ? 'yes' : 'no',
            '_MINT_USER_' => $data['systemAdminName'],
            '_MINT_PASS_' => $data['systemAdminPassword'],
            '_SITE_URL_' => $data['siteUrl'],
        ];

        $this->changeConfigFileInfo($configData);
    }

    public function addElasticInfoToConfig($data)
    {
        $configData = [
            '_ELASTIC_HOST_' => $data['elasticHost'] . ":" . $data['elasticPort'],
            '_ELASTIC_USER_' => $data['elasticUser'],
            '_ELASTIC_PASS_' => $data['elasticPass'],
        ];

        $this->changeConfigFileInfo($configData);
    }

    public function changeConfigFileInfo($configData)
    {
        $instanceConfigPath = __DIR__ . self::INSTANCE_DIR . '/config_si.php';
        $config = file_get_contents($instanceConfigPath);
        $config = str_replace(array_keys($configData), array_values($configData), $config);
        file_put_contents($instanceConfigPath, $config);
    }
}
