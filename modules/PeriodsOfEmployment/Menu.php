<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('PeriodsOfEmployment', 'list', true) ) {
   $module_menu[] = array(
      'index.php?module=PeriodsOfEmployment&action=index&return_module=PeriodsOfEmployment&return_action=DetailView',
      $mod_strings['LNK_LIST'],
      'View',
      'PeriodsOfEmployment'
   );
}
