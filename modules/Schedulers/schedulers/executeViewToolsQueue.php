<?php

error_reporting(E_ERROR);
require_once 'data/SugarBean.php';
//global $job_strings; t
array_push($job_strings, 'executeViewToolsQueue');

function executeViewToolsQueue() {
   require_once 'include/ViewTools/executeViewToolsQueue.php';
   $scheduler = new executeViewToolsQueue();
   $scheduler->run();
   return true;
}
