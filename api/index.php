<?php
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
chdir(__DIR__ . '/../legacy/');
require_once __DIR__ . '/../legacy/include/entryPoint.php';
chdir(__DIR__ . '/../api/');

require __DIR__ . '/vendor/autoload.php';

use MintHCM\Api\ApiManager;
use MintHCM\Api\Config\AppConfig;
use MintHCM\Api\Utils\CustomLoader;
use Slim\Factory\AppFactory;

$config = CustomLoader::getObject(AppConfig::class);
$app = AppFactory::create();
$app->setBasePath($config::getBasePath());

$manager = ApiManager::getInstance();
$manager->execute();

$app->run();
