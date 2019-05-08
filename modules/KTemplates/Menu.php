<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;


if ( ACLController::checkAccess('KTemplates', 'edit', true) )
   $module_menu[] = Array( "index.php?module=KTemplates&action=wizard&return_module=KTemplates&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'], "Create", 'KTemplates' );
if ( ACLController::checkAccess('KTemplates', 'list', true) )
   $module_menu[] = Array( "index.php?module=KTemplates&action=index", $mod_strings['LNK_LIST'], "List", 'KTemplates' );