<?php
define('sugarEntry', 'tests');

chdir(dirname(__FILE__) . "/../../../MintHCM");

define('SUITECRM_PHP_MIN_VERSION', '7.0.0');
define('SUITECRM_PHP_REC_VERSION', '7.1.1');
define('MINTHCM_PHP_MAX_VERSION', '7.4.0');

require_once "include/utils.php";

$versions = [
    '5.6.0' => -1,
    '7.6.0' => -1,
    '7.0.0' => 0,
    '7.1.1' => 1,
    '7.2.0' => 1,
];

foreach ($versions as $key => $value) {
    echo "TEST " . $key . " :";
    if (check_php_version($sys_php_version = $key) == $value) {
        echo " PASS";
    } else {
        echo " FAIL";
    }
    echo " \n";
}
