<?php

if ( !defined('sugarEntry') ) {
   define('sugarEntry', true);
}

SugarAutoLoader::requireWithCustom('include/ScheduleCreateAppraisal/ScheduleCreateAppraisal.php');
SugarAutoLoader::requireWithCustom('include/SugarQueue/SugarJobQueue.php');

$record_id = filter_input(INPUT_GET, 'record_id', FILTER_SANITIZE_STRING);
$module = filter_input(INPUT_GET, 'module', FILTER_SANITIZE_STRING);
$appraisal_name = filter_input(INPUT_GET, 'appraisal_name', FILTER_SANITIZE_STRING);

try {
   if ( empty($record_id) || empty($module) || empty($appraisal_name) ) {
      throw new Exception('Missing request data. $_GET: ' . var_export($_GET, true));
   } else {
      $SAAI = new ScheduleCreateAppraisal($record_id, $module, $appraisal_name);
      $SAAI->schedule();
      echo 'AppraisalJobAdded';
   }
} catch ( Exception $ex ) {
   $GLOBALS['log']->fatal($ex->getMessage());
   $GLOBALS['log']->fatal($ex->getTraceAsString());
}

