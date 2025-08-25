<?php
define('sugarEntry', 'tests');

chdir(dirname(__FILE__) . "/../../../../MintHCM");
class DBManager
{}
require_once 'include/database/MysqlManager.php';

class MysqlManagerTest extends MysqlManager
{
    public function setVersions($version, $version_name)
    {
        $this->version = $version;
        $this->version_name = $version_name;
    }
    public function version()
    {
        return $this->version;
    }
    public function versionName()
    {
        return $this->version_name;
    }
}

$mysql_manager = new MysqlManagerTest;
$mysql_manager->setVersions('', '');

$tests = [
    ['ERR_DB_VERSION_FAILURE', ['', '']],
    ['ERR_DB_MYSQL_VERSION', ['5.5.59', 'mysql']],
    ['ERR_DB_MYSQL_VERSION', ['8.0.25', 'mysql']],
    ['ERR_DB_MYSQL_VERSION', ['10.2', 'maria']],
    ['ERR_DB_MYSQL_VERSION', ['5.4', 'maria']],
    [true, ['5.6', 'mysql']],
    [true, ['5.7.24-27', 'mysql']],
    [true, ['5.6', 'maria']],
    [true, ['10.1', 'maria']],
];

foreach ($tests as $test) {
    $expected_result = $test[0];
    $test_params = $test[1];
    $mysql_manager->setVersions($test_params[0], $test_params[1]);
    $result = $mysql_manager->canInstall();
    echo "TEST: [" . implode(',', $test_params) . "]->" . $expected_result . " - ";
    if (true === $expected_result ? true === $result : is_array($result) && $result[0] === $expected_result) {
        echo " PASS ";
    } else {
        echo " FAIL ";
    }
    echo " \n";
}
