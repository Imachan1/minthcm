<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('DashboardHistory', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=DashboardHistory&action=index&return_module=DashboardHistory&return_action=DetailView', $mod_strings['LNK_LIST'], 'View', 'DashboardHistory' );
}
if ( ACLController::checkAccess('DashboardManager', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=DashboardManager&action=index&return_module=DashboardHistory&return_action=DetailView', $mod_strings['LBL_DASHBOARDMANAGER'], 'List', 'DashboardManager' );
}
if ( ACLController::checkAccess('DashboardBackups', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=DashboardBackups&action=index&return_module=DashboardHistory&return_action=DetailView', $mod_strings['LBL_DASHBOARDBACKUPS'], 'List', 'DashboardBackups' );
}