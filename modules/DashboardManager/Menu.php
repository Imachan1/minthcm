<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('DashboardManager', 'edit', true) ) {
   $module_menu[] = array( 'index.php?module=DashboardManager&action=EditView&return_module=DashboardManager&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'DashboardManager' );
}
if ( ACLController::checkAccess('DashboardManager', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=DashboardManager&action=index&return_module=DashboardManager&return_action=DetailView', $mod_strings['LNK_LIST'], 'List', 'DashboardManager' );
}
if ( ACLController::checkAccess('DashboardHistory', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=DashboardHistory&action=index&return_module=DashboardManager&return_action=DetailView', $mod_strings['LBL_DASHBOARDHISTORY_MENU'], 'List', 'DashboardHistory' );
}
if ( ACLController::checkAccess('DashboardBackups', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=DashboardBackups&action=index&return_module=DashboardManager&return_action=DetailView', $mod_strings['LNK_VIEW_DASHBOARD_BACKUPS'], 'List', 'DashboardBackups' );
}