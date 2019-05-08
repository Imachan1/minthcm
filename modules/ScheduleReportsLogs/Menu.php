<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('KReports', 'edit', true) )
   $module_menu[] = Array( "index.php?module=KReports&action=EditView&return_module=ScheduleReportsLogs&return_action=index", $mod_strings['LBL_NEW_REPORT'], "Create", "KReports" );
if ( ACLController::checkAccess('KReports', 'list', true) )
   $module_menu[] = Array( "index.php?module=KReports&action=index", $mod_strings['LBL_REPORT_LIST'], "List", "KReports" );
if ( ACLController::checkAccess('ScheduleReports', 'edit', true) )
   $module_menu[] = Array( "index.php?module=ScheduleReports&action=EditView&return_module=ScheduleReportsLogs&return_action=index", $mod_strings['LBL_NEW_SCHEDULE_REPORT'], "Create", 'ScheduleReports' );
if ( ACLController::checkAccess('ScheduleReports', 'list', true) )
   $module_menu[] = Array( "index.php?module=ScheduleReports&action=index", $mod_strings['LBL_SCHEDULE_REPORTS'], "List", 'ScheduleReports' );
if ( ACLController::checkAccess('ScheduleReportsLogs', 'list', true) )
   $module_menu[] = Array( "index.php?module=ScheduleReportsLogs&action=index", $mod_strings['LNK_LIST'], "List", 'ScheduleReportsLogs' );
?>