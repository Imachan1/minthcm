<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if ( ACLController::checkAccess('Appraisals', 'edit', true) ) {
   $module_menu[] = array( 'index.php?module=Appraisals&action=EditView&return_module=Appraisals&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'Appraisals' );
}
if ( ACLController::checkAccess('Appraisals', 'list', true) ) {
   $module_menu[] = array( 'index.php?module=Appraisals&action=index&return_module=Appraisals&return_action=DetailView', $mod_strings['LNK_LIST'], 'View', 'Appraisals' );
}
if ( ACLController::checkAccess('Appraisals', 'import', true) ) {
   $module_menu[] = array( 'index.php?module=Import&action=Step1&import_module=Appraisals&return_module=Appraisals&return_action=index', $app_strings['LBL_IMPORT'], 'Import', 'Appraisals' );
}