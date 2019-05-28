<?php

$job_strings[] = 'updateNewsDisplayDate';

function updateNewsDisplayDate() {
    require_once 'include/NewsDisplayDateUpdater/NewsDisplayDateUpdater.php';
    $ENDDU = new NewsDisplayDateUpdater();
    $ENDDU->start();
    return true;
}
