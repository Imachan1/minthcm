<?php

$job_strings[] = 'updateEvNewsDisplayDate';

function updateEvNewsDisplayDate() {
    require_once 'include/NewsDisplayDateUpdater/NewsDisplayDateUpdater.php';
    $ENDDU = new NewsDisplayDateUpdater();
    $ENDDU->start();
    return true;
}
