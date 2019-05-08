<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('KReports', 'edit', true) )
   $module_menu[] = Array( "index.php?module=KReports&action=EditView&return_module=ScheduleReports&return_action=index", $mod_strings['LNK_NEW_REPORT'], "Create", "KReports" );
if ( ACLController::checkAccess('KReports', 'list', true) )
   $module_menu[] = Array( "index.php?module=KReports&action=index", $mod_strings['LNK_REPORT_LIST'], "List", "KReports" );
if ( ACLController::checkAccess('ScheduleReports', 'edit', true) )
   $module_menu[] = Array( "index.php?module=ScheduleReports&action=EditView&return_module=ScheduleReports&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'], "Create", 'ScheduleReports' );
if ( ACLController::checkAccess('ScheduleReports', 'list', true) )
   $module_menu[] = Array( "index.php?module=ScheduleReports&action=index", $mod_strings['LNK_LIST'], "List", 'ScheduleReports' );
if ( ACLController::checkAccess('EmailTemplates', 'edit', true) )
   $module_menu[] = Array( "index.php?module=EmailTemplates&action=EditView&return_module=ScheduleReports&return_action=index", $mod_strings['LBL_NEW_EMAIL_TEMPLATE'], "Create", 'EmailTemplates' );
if ( ACLController::checkAccess('EmailTemplates', 'list', true) )
   $module_menu[] = Array( "index.php?module=EmailTemplates&action=index", $mod_strings['LBL_EMAIL_TEMPLATES'], "List", 'EmailTemplates' );