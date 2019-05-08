<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}


global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('WorkSchedules', 'edit', true) ) {
   $module_menu[] = array(
      'index.php?module=WorkSchedules&action=EditView&return_module=WorkSchedules&return_action=DetailView',
      $mod_strings['LNK_NEW_RECORD'],
      'Create',
      'WorkSchedules'
   );
}
if ( ACLController::checkAccess('WorkSchedules', 'list', true) ) {
   $module_menu[] = array(
      'index.php?module=WorkSchedules&action=index&return_module=WorkSchedules&return_action=DetailView',
      $mod_strings['LNK_LIST'],
      'List',
      'WorkSchedules'
   );
}
