<?php
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
chdir('../legacy/');
require_once 'include/entryPoint.php';
chdir('../api/');

require __DIR__ . '/vendor/autoload.php';

use MintHCM\Api\ApiManager;
use MintHCM\Api\Config\AppConfig;
use MintHCM\Utils\CustomLoader;
use Slim\Factory\AppFactory;

$config = CustomLoader::getObject(AppConfig::class);
$app = AppFactory::create();
$app->setBasePath($config::getBasePath());

$manager = ApiManager::getInstance();
$manager->execute();

$app->run();
