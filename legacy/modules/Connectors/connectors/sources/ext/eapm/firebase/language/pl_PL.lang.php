<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<b>Musisz wygenerować klucz serwera <a href="https://console.firebase.google.com" target="_blank">tutaj</a></b>',
    'server_key' => 'Klucz serwera',
    'url' => 'Adres serwera (https://fcm.googleapis.com/fcm/send)',
    'priority' => 'Priorytet (high)',
    'android_channel_id' => 'Id kanału (minthcm0)',
    'badge' => 'Wartość odznaki na ikonie aplikacji (badge)',
    'sound' => 'Dźwięk (default)',
);
