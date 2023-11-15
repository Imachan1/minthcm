<?php

define('sugarEntry', true);

use MintHCM\Install\Helpers\VersionValidator;
use MintHCM\Install\InstallController;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;
use Slim\Psr7\Headers;
use Slim\Psr7\Stream;
use Slim\Psr7\Response;

require 'vendor/autoload.php';

require 'install/InstallController.php';
require 'utils/MintResponse.php';
require 'install/Helpers/VersionValidator.php';
require_once 'install/Helpers/ConfigHelper.php';

echo "\n==========\n";
echo "\nTesting...\n";

$installController = new InstallController();

$uri = new Uri('http', 'example.com');

$request = new Request(
    'GET',          // Method
    $uri,      // Uri
    new Headers(),  // Headers
    [],             // Cookies
    [],             // Server Params
    new Stream(fopen('php://temp', 'r+')) // Body
);

// UNCOMMENT ANY TESTS U WANT TO PERFORM -> FILL IN THE DATA FOR THE POST REQUESTS

// testLicense($installController, $request);
// testVerifyEnvironment($installController, $request);
// testDBConnections($installController, $request);
// testDBValidate($installController, $request);
// testDBConfig($installController, $request);
// testElasticValidate($installController, $request);
// testElasticConfig($installController, $request);
// testSaveSettings($installController, $request);
// testInstallSystem($installController, $request);
// testSteps($installController, $request);
// testStepsLoop($installController, $request);





// LICENSE TEST
function testLicense($installController, $request)
{
    $licenseResponse = $installController->getLicense($request, new Response());
    echo "\nlicense retrieved?: " . !empty($licenseResponse->getBody());
}

// ENV TEST
function testVerifyEnvironment($installController, $request)
{
    $environmentResponse = $installController->verifyEnvironment($request, new Response());
    $environmentResponse = (new VersionValidator)->runValidations();
    echo "\nverifyEnvironment Response: ";
    var_dump($environmentResponse);
}

// DB AVAILABLE CONNECTION TEST
function testDBConnections($installController, $request)
{
    $dbConnectionResponse = $installController->checkAvailableDBConnection($request, new Response());
    echo "\ncheckAvailableDBConnection Response: " . $dbConnectionResponse->getBody();
}

// DB VALIDATE CONNECTION TEST
function testDBValidate($installController, $request)
{
    $requestData = [
        'databaseHost' => 'localhost',
        'databasePort' => '3306',
        'databaseUsername' => 'root',
        'databasePassword' => 'password',
        'databaseName' => 'dbase',
    ];
    $requestDB = $request->withParsedBody($requestData);
    $response = $installController->validateDBConnection($requestDB, new Response());
    echo "\nvalidateDBConnection Response: " . $response->getBody() . "\n";
}

// DB SAVE CONFIG
function testDBConfig($installController, $request)
{
    $requestData = [
        'databaseHost' => 'localhost',
        'databasePort' => '3306',
        'databaseUsername' => 'root',
        'databasePassword' => 'password',
        'databaseName' => 'dbase',
        'databaseCollation' => 'utf8_general_ci',
    ];
    $requestDB = $request->withParsedBody($requestData);
    $response = $installController->saveDBConnection($requestDB, new Response());
    echo "\nsaveDBConnection Response: " . $response->getBody() . "\n";
}


// VALIDATE ES CONFIG
function testElasticValidate($installController, $request)
{
    $requestData = [
        'elasticUser' => '',
        'elasticPass' => '',
        'elasticHost' => 'elasticurl.com',
        'elasticPort' => '9209',
    ];
    $requestES = $request->withParsedBody($requestData);
    $response = $installController->validateESConnection($requestES, new Response());
    echo "\nvalidateESConnection Response: " . $response->getBody() . "\n";
}

// ELASTIC SAVE CONFIG
function testElasticConfig($installController, $request)
{
    $requestData = [
        'elasticUser' => '',
        'elasticPass' => '',
        'elasticHost' => 'elasticurl.com',
        'elasticPort' => '9209',
    ];
    $requestES = $request->withParsedBody($requestData);
    $response = $installController->saveESConnection($requestES, new Response());
    echo "\nsaveESConnection Response: " . $response->getBody() . "\n";
}

// SAVE SETTINGS
function testSaveSettings($installController, $request)
{
    $requestData = [
        'demoData' => 'yes',
        'systemAdminName' => 'ADMIN',
        'systemAdminPassword' => 'PASSWORD',
        'siteUrl' => 'https://site.net/MINT',
        'rootDirectory' => '/var/www',
    ];
    $request = $request->withParsedBody($requestData);
    $response = $installController->saveSettings($request, new Response());
    echo "\nsaveSettings Response: " . $response->getBody() . "\n";
}

// INSTALL THE SYSTEM (WATCH OUT, THIS ACTUALLY INSTALLS THE SYSTEM, MAKE SURE U HAVE UR PREVIOUS STEPS DONE)
function testInstallSystem($installController, $request)
{
    $request = $request->withParsedBody($request);
    $response = $installController->install($request, new Response());
    echo "\ninstall Response: " . $response->getBody() . "\n";
}

// INSTALLATION STEPS
function testSteps($installController, $request)
{
    $installController->clearStatusJson();

    $installController->setMintInstallStatus(1, "LBL_STEP_1");

    $response = $installController->getInstallationProgress($request, new Response());
    echo "\ncurrent progress: " . $response->getBody() . "\n";

    $installController->setMintInstallStatus(2, "LBL_STEP_2");

    $response = $installController->getInstallationProgress($request, new Response());
    echo "\ncurrent progress: " . $response->getBody() . "\n";

    $installController->setMintInstallStatus(3, "LBL_STEP_3");

    $response = $installController->getInstallationProgress($request, new Response());
    echo "\ncurrent progress: " . $response->getBody() . "\n";
}

// INSTALLATION STEPS LOOP
function testStepsLoop($installController, $request)
{
    for ($i = 0; $i < 30; $i++) {
        $response = $installController->getInstallationProgress($request, new Response());
        echo "\n\ntime: $i,  current progress: " . $response->getBody() . "\n\n";

        sleep(1);
    }
}
