<?php

if ( !defined('sugarEntry') ) {
   define('sugarEntry', true);
}

require_once('include/SugarQueue/SugarJobQueue.php');
SugarAutoLoader::requireWithCustom('include/ScheduleGenerateOnboardingOffboarding/ScheduleGenerateOnboardingOffboarding.php');

$module_name = filter_input(INPUT_GET, 'module_name', FILTER_SANITIZE_STRING);
$template_id = filter_input(INPUT_GET, 'template_id', FILTER_SANITIZE_STRING);
$employee_id = filter_input(INPUT_GET, 'employee_id', FILTER_SANITIZE_STRING);
$date_start = filter_input(INPUT_GET, 'date_start', FILTER_SANITIZE_STRING);

try {
   $SGOO = new ScheduleGenerateOnboardingOffboarding($module_name, $template_id, $employee_id, $date_start);
   echo $SGOO->schedule();
} catch ( Exception $ex ) {
   $GLOBALS['log']->fatal($ex->getTraceAsString());
   echo false;
}

