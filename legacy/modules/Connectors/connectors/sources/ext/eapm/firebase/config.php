<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$config = array(
    'name' => 'Firebase',
    'properties' => array(
        'server_key' => 'add server key here',
        'url' => 'https://fcm.googleapis.com/fcm/send',
        'priority' => 'high',
        'android_channel_id' => 'minthcm0',
        'badge' => 1,
        'sound' => 'default',
    ),
);
