<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('Resources', 'edit', true) ) {
   $module_menu[] = array('index.php?module=Resources&action=EditView&return_module=Resources&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'Resources');
}
if ( ACLController::checkAccess('Resources', 'list', true) ) {
   $module_menu[] = array('index.php?module=Resources&action=index&return_module=Resources&return_action=DetailView', $mod_strings['LNK_LIST'], 'View', 'Resources');
}
if ( ACLController::checkAccess('ReservationsCalendar', 'list', true) ) {
   $module_menu[] = array('index.php?module=ReservationsCalendar&action=index', $app_strings['LNK_RESERVATION_CALENDAR'], 'Schedule_Meeting', 'ReservationsCalendar');
}
if ( ACLController::checkAccess('Resources', 'import', true) ) {
   $module_menu[] = array('index.php?module=Import&action=Step1&import_module=Resources&return_module=Resources&return_action=index', $mod_strings['LNK_IMPORT_RESOURCES'], 'Import', 'Resources');
}
