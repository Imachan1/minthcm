<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('Reservations', 'edit', true) ) {
   $module_menu[] = array( 'index.php?module=Reservations&action=EditView&return_module=Reservations&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'Reservations' );
}
if ( ACLController::checkAccess('Reservations', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=Reservations&action=index&return_module=Reservations&return_action=DetailView', $mod_strings['LNK_LIST'], 'View', 'Reservations' );
}
if ( ACLController::checkAccess('ReservationsCalendar', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=ReservationsCalendar&action=index', $app_strings['LNK_RESERVATION_CALENDAR'], 'Schedule_Meeting', 'ReservationsCalendar' );
}
if ( ACLController::checkAccess('Reservations', 'import', true) ) {
   $module_menu[] = array( 'index.php?module=Import&action=Step1&import_module=Reservations&return_module=Reservations&return_action=index', $app_strings['LBL_IMPORT'], 'Import', 'Reservations' );
}
