<?php

$dictionary['DashboardHistory'] = array(
   'table' => 'dashboardhistory',
   'audited' => false,
   'inline_edit' => false,
   'duplicate_merge' => false,
   'fields' => array(
      'user_count' => array(
         'type' => 'int',
         'name' => 'user_count',
         'vname' => 'LBL_USER_COUNT',
         'massupdate' => false,
         'importable' => true,
         'default' => '0',
         'no_default' => false,
      ),
      "dashboardmanager" => array(
         'name' => 'dashboardmanager',
         'type' => 'link',
         'relationship' => 'dashboardhistory_dashboardmanager',
         'source' => 'non-db',
         'module' => 'DashboardManager',
         'bean_name' => false,
         'vname' => 'LBL_DASHBOARDMANAGER',
      ),
      "dashboardmanager_name" => array(
         'id_name' => 'dashboardmanager_id',
         'name' => 'dashboardmanager_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_DASHBOARDMANAGER_NAME',
         'link' => 'dashboardmanager',
         'table' => 'dashboardmanager',
         'module' => 'DashboardManager',
         'rname' => 'name',
      ),
      "dashboardmanager_id" => array(
         'name' => 'dashboardmanager_id',
         'type' => 'id',
         'vname' => 'LBL_DASHBOARDMANAGER_ID',
         'link' => 'dashboardmanager',
         'table' => 'dashboardmanager',
         'module' => 'DashboardManager',
         'rname' => 'id',
         'reportable' => false,
         'massupdate' => false,
         'duplicate_merge' => 'disabled',
         'hideacl' => true,
      ),
      "dashboardbackups" => array(
         'name' => 'dashboardbackups',
         'type' => 'link',
         'relationship' => 'dashboardbackups_dashboardhistory',
         'source' => 'non-db',
         'module' => 'DashboardBackups',
         'bean_name' => 'DashboardBackups',
         'vname' => 'LBL_DASHBOARDBACKUPS',
      ),
   ),
   'relationships' => array(
      'dashboardbackups_dashboardhistory' =>
      array(
         'lhs_module' => 'DashboardHistory',
         'lhs_table' => 'dashboardhistory',
         'lhs_key' => 'id',
         'rhs_module' => 'DashboardBackups',
         'rhs_table' => 'dashboardbackups',
         'rhs_key' => 'dashboardhistory_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => false,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('DashboardHistory', 'DashboardHistory', array( 'basic', 'assignable' ));
