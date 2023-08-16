<?php

global $mint_config;

$mint_config = array(
    "search" => array(
        "default_engine" => 'ElasticSearch',
        "default_page_size" => 25,
        "engines" => array(
            "ElasticSearch" => array(
                array(
                    'host' => 'localhost',
                    'user' => '',
                    'pass' => '',
                    'port' => '9200',
                ),
            ),
        ),
    ),
    "database" => array(
        'driver' => 'pdo_mysql',
        'host' => "localhost",
        'port' => '3306',
        'dbname' => '',
        'user' => '',
        'password' => '',
    ),
);

$files = scandir(__DIR__);
if (is_array($files)) {
    $files = array_diff($files, array('.', '..', 'config.php'));
    foreach ($files as $file) {
        include __DIR__ . '/' . $file;
    }
}
unset($files);
