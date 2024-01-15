<?php

$parts = explode('/legacy/', $_SERVER['SCRIPT_NAME']);
if (count($parts) === 2) {
    $_SERVER['SCRIPT_NAME'] = $parts[0] . '/' . $parts[1];
}

chdir('../');
require_once __DIR__ . '/Core/app.php';
$app->run();
