<?php

namespace MintHCM\Install;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use MintHCM\MintCLI\Services\DatabaseService;
use Elasticsearch\ClientBuilder;
use MintHCM\Install\Helpers\VersionValidator;
use MintHCM\Install\Helpers\ConfigHelper;

chdir('../legacy/');
require_once 'include/utils.php';
require_once 'include/database/DBManagerFactory.php';
require_once 'MintCLI/src/Services/DatabaseService.php';
require_once 'MintCLI/src/Services/ServerService.php';
require_once 'MintCLI/src/Services/HtaccessService.php';
require_once 'MintCLI/src/Installer/Installer.php';
require_once 'include/SugarLogger/LoggerManager.php';
require_once 'sugar_version.php';
require_once 'suitecrm_version.php';
require_once 'minthcm_version.php';
require_once 'install/install_utils.php';
require_once 'install/install_defaults.php';
require_once 'include/TimeDate.php';
require_once 'include/Localization/Localization.php';
require_once 'include/SugarTheme/SugarTheme.php';
require_once 'include/utils/LogicHook.php';
require_once 'data/SugarBean.php';
chdir('../api/');

require_once 'Helpers/ConfigHelper.php';

class InstallController
{
    const INSTALLATION_STEPS = 22;
    protected $rootDirectory;
    protected $dbData;
    protected $elasticData;

    /**
     * Returns the contents of the License.txt file
     * located in the /Assets/LICENSE.txt in a format with
     * line breaks incorporated into it
     */
    public function getLicense(Request $request, Response $response): Response
    {
        $filename = __DIR__ . "/Assets/LICENSE.txt";
        $license_file = '';

        if (file_exists($filename) && filesize($filename) > 0) {
            $status = 1;
            $license_file = trim(file_get_contents($filename));
        } else {
            $status = 0;
        }

        return $this->responseWithJson($response, ["status" => $status, "license" => $license_file]);
    }

    /**
     * Verifies the environment for required services, directories
     * and permissions. Checks available/allocated memory too.
     */
    public function verifyEnvironment(Request $request, Response $response): Response
    {
        return $this->responseWithJson($response, (new VersionValidator)->runValidations());
    }

    /**
     * Checks available DB Drivers like MySQL or MariaDB
     * Which will be useful in the future. So far only MySQL is supported.
     */
    public function checkAvailableDBConnection(Request $request, Response $response): Response
    {
        chdir('../legacy');
        $DbDrivers = \DBManagerFactory::getDbDrivers();
        chdir('../api');

        if (!empty($DbDrivers)) {
            $status = 1;
            $DbDrivers = array_map(function ($db) {
                return [
                    'dbType' => $db->dbType,
                    'variant' => $db->variant,
                    'label' => $db->label,
                ];
            }, $DbDrivers);
            $message = "LBL_FOUND_DB_CONNECTIONS";
        } else {
            $status = 0;
            $message = "LBL_CANT_FIND_DB_CONNECTIONS";
        }

        return $this->responseWithJson($response, ["status" => $status, "message" => $message, "dbConnections" => $DbDrivers]);
    }

    /**
     * Validates the DB Connection and checks if the DB name is still available
     * @return Response $this->responseWithJson($response, ), status => 0/1, message => LABEL
     */

    public function validateDBConnection(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $DBService = new DatabaseService();

        $connectionStatus = $DBService->testConnection($body['databaseHost'], $body['databasePort'], $body['databaseUsername'], $body['databasePassword']);
        if (!$connectionStatus['status']) {
            return $this->responseWithJson($response, ["status" => 0, "message" => "ERR_DB_CONNECTION_FAIL"]);
        }

        $existenceStatus = $DBService->testDatabaseExistance($body['databaseHost'], $body['databasePort'], $body['databaseUsername'], $body['databasePassword'], $body['databaseName']);
        if (!$existenceStatus) {
            return $this->responseWithJson($response, ["status" => 0, "message" => "ERR_DB_ALREADY_EXISTS"]);
        }

        return $this->responseWithJson($response, ["status" => 1, "message" => "LBL_DB_CONNECTION_VALIDATED"]);
    }

    /**
     * Saves the DB Connection in config_si.php and in the memory, for future install (mint_override);
     * @return Response $this->responseWithJson($response, status => 0/1, message => LABEL)
     */

    public function saveDBConnection(Request $request, Response $response): Response
    {
        (new ConfigHelper($this->rootDirectory))->prepareSystemConfiguration();

        $body = $request->getParsedBody();
        $this->dbData = $body;
        $installer = new ConfigHelper($this->rootDirectory);
        try {
            $status = $installer->addDatabaseInfoToConfig($body);
            return $this->responseWithJson($response, ["status" => 1, "message" => "LBL_DB_CONNECTION_CREATED"]);
        } catch (\Exception $e) {
            return $this->responseWithJson($response, ["status" => 0, "message" => "ERR_DB_CONNECTION_NOT_CREATED"]);
        }
    }

    /**
     * Validates the ES connection by trying to ping the ElasticSearch instance
     * by using the User, Pass, Host and Port. User and Pass may be provided as empty, but
     * they still should be sent regardless.
     */
    public function validateESConnection(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $client = ClientBuilder::create()
            ->setHosts([$body['elasticUser'] . ':' . $body['elasticPass'] . '@' . $body['elasticHost'] . ":" . $body['elasticPort']])
            ->build();

        try {
            $status = $client->ping();

            if ($status) {
                return $this->responseWithJson($response, ["status" => 1, "message" => "LBL_ES_CONNECTION_VALIDATED"]);
            } else {
                return $this->responseWithJson($response, ["status" => 0, "message" => "ERR_ES_WRONG"]);
            }
        } catch (\Exception $e) {
            return $this->responseWithJson($response, ["status" => 0, "message" => "ERR_ES_CONN", "error" => $e->getMessage()]);
        }
    }

    /**
     * Saves the ES connection in the config file, and saves data for
     * future use in the installation process. This data is later saved into mint_override.php
     */
    public function saveESConnection(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $this->elasticData = $body;

        $installer = new ConfigHelper($this->rootDirectory);
        $installer->addElasticInfoToConfig($body);

        return $this->responseWithJson($response, ["status" => 1, "message" => "LBL_ES_CONNECTION_CREATED"]);
    }

    /**
     * Saves Administrator credentials, root directory, site url
     */
    public function saveSettings(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $this->rootDirectory = $body['rootDirectory'];

        $installer = new ConfigHelper($this->rootDirectory);
        $installer->addSystemInfoToConfig($body);

        return $this->responseWithJson($response, ["status" => 1, "message" => "LBL_SETTINGS_SAVED"]);
    }

    /**
     * Performs the installation using the mechanisms from 
     * MintCLI for mainptainability. This process can only be triggered
     * after you've triggered the previous functions to save the 
     * rootDirectory, dbData and elasticData
     */
    public function install(Request $request, Response $response): Response
    {
        try {
            chdir('../');

            $installer = new ConfigHelper($this->rootDirectory);

            $this->clearStatusJson();

            $this->setMintInstallStatus(1, "LBL_INSTALLATION_SETUP_DOCTRINE");

            $userData = array_merge($this->dbData, $this->elasticData);

            $installer->setupDoctrineConfig($userData);

            $this->setMintInstallStatus(2, "LBL_INSTALLATION_FILE_PERMISSIONS");
            $installer->setupFilesPermissions();

            $this->setMintInstallStatus(3, "LBL_INSTALLATION_STARTING_BACKEND");
            $installer->installBackendApplication();

            // Sudden progress jump due to backend doing a lot of other stuff
            $this->setMintInstallStatus(20, "LBL_INSTALLATION_FRONTEND_APPLICATION");
            $installer->installFrontendApplication();

            $this->setMintInstallStatus(21, "LBL_INSTALLATION_HTACCESS");
            $installer->setupHtaccess();

            $this->setMintInstallStatus(22, "LBL_INSTALLATION_FILE_PERMISSIONS");
            $installer->setupFilesPermissions();

            return $this->responseWithJson($response, ["status" => 1, "message" => "LBL_INSTALLATION_SUCCESS"]);
        } catch (\Exception $e) {
            return $this->responseWithJson($response, ["status" => 0, "message" => "ERR_INSTALLATION_FAILURE", "error" => $e]);
        }
    }

    /**
     * Returns the current installation step
     * @return int status = 0/1, 
     * @return int step, 
     * @return string message
     */
    public function getInstallationProgress(Request $request, Response $response): Response
    {
        $statusFile = __DIR__ . '/Assets/status.json';

        if (file_exists($statusFile)) {
            $statusData = json_decode(file_get_contents($statusFile), true);

            if (is_array($statusData) && !empty($statusData)) {
                $latestStepNumber = max(array_keys($statusData));
                $latestMessage = $statusData[$latestStepNumber];

                return $this->responseWithJson($response, [
                    "status" => 1,
                    "step" => $latestStepNumber,
                    "message" => $latestMessage,
                ]);
            }
        }

        return $this->responseWithJson($response, ["status" => 0, "step" => null, "message" => "ERR_FAILED_TO_GET_STEP"]);
    }

    /**
     * Returns the installation steps amount declared as a 
     * const at the top of this file, to divide the progress bar
     * accordingly
     */
    public function getInstallationStepsAmount(Request $request, Response $response): Response
    {
        return $this->responseWithJson($response, ["status" => 1, "stepsAmount" => self::INSTALLATION_STEPS]);
    }

    /**
     * Adds another step to the status.json file in the format:
     * $step_number (int): $message (string) 
     */
    public function setMintInstallStatus(int $step, string $message): void
    {
        $statusFile = __DIR__ . '/Assets/status.json';
        $statusData = [];

        if (file_exists($statusFile)) {
            $existingData = json_decode(file_get_contents($statusFile), true);
            if (is_array($existingData)) {
                $statusData = $existingData;
            }
        }

        $statusData[$step] = $message;
        $encodedStatusData = json_encode($statusData, JSON_PRETTY_PRINT);
        file_put_contents($statusFile, $encodedStatusData);
    }

    /**
     * At the start of the installation process
     * clears the status.json file in preparation for 
     * the instalation process, in case we had a failed install before
     * and halted it
     */
    public function clearStatusJson(): void
    {
        $statusFile = __DIR__ . '/Assets/status.json';

        if (file_exists($statusFile)) {
            $file = fopen($statusFile, 'w');
            fclose($file);
        }
    }

    /**
     * Helper that will turn the response arguments from
     * an array into a json format instead
     */
    public function responseWithJson($response, $content)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = json_encode($content);
        $response->getBody()->write($data);
        return $response;
    }
}
