<?php

$dictionary['DashboardManager'] = array(
   'table' => 'dashboardmanager',
   'audited' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'encoded_pages' =>
      array(
         'required' => false,
         'name' => 'encoded_pages',
         'vname' => 'LBL_ENCODED_PAGES',
         'type' => 'text',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => false,
         'unified_search' => false,
         'calculated' => false,
         'size' => '20',
         'studio' => 'visible',
         'rows' => '4',
         'cols' => '20',
      ),
      'encoded_dashlets' =>
      array(
         'required' => false,
         'name' => 'encoded_dashlets',
         'vname' => 'LBL_ENCODED_DASHLETS',
         'type' => 'text',
         'dbType' => 'mediumtext',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'calculated' => false,
         'size' => '20',
         'studio' => 'visible',
         'rows' => '4',
         'cols' => '20',
      ),
      'is_loaded' => array(
         'type' => 'bool',
         'name' => 'is_loaded',
         'vname' => 'LBL_IS_LOADED',
         'massupdate' => false,
         'audited' => true,
         'importable' => true,
         'default' => '0',
         'no_default' => false,
      ),
      "dashboardbackups" => array(
         'name' => 'dashboardbackups',
         'type' => 'link',
         'relationship' => 'dashboardbackups_dashboardmanager',
         'source' => 'non-db',
         'module' => 'DashboardBackups',
         'bean_name' => 'DashboardBackups',
         'vname' => 'LBL_DASHBOARDBACKUPS',
      ),
      "dashboardhistory" => array(
         'name' => 'dashboardhistory',
         'type' => 'link',
         'relationship' => 'dashboardhistory_dashboardmanager',
         'source' => 'non-db',
         'module' => 'DashboardHistory',
         'bean_name' => 'DashboardHistory',
         'vname' => 'LBL_DASHBOARDHISTORY',
      ),
      'users_locked_dashboards' => array(
         'name' => 'users_locked_dashboards',
         'type' => 'link',
         'relationship' => 'users_locked_dashboards',
         'source' => 'non-db',
         'module' => 'Users',
         'bean_name' => 'User',
         'vname' => 'LBL_USERS_LOCKED_DASHBOARDS',
      ),
      'users_forced_tabs_dashboards' => array(
         'name' => 'users_forced_tabs_dashboards',
         'type' => 'link',
         'relationship' => 'users_forced_tabs_dashboards',
         'source' => 'non-db',
         'module' => 'Users',
         'bean_name' => 'User',
         'vname' => 'LBL_USERS_FORCED_TABS_DASHBOARDS',
      ),
      'users_one_time_default_dashboards' => array(
         'name' => 'users_one_time_default_dashboards',
         'type' => 'link',
         'relationship' => 'users_one_time_default_dashboards',
         'source' => 'non-db',
         'module' => 'Users',
         'bean_name' => 'User',
         'vname' => 'LBL_USERS_ONE_TIME_DEFAULT_DASHBOARDS',
      )
   ),
   'relationships' => array(
      'users_forced_tabs_dashboards' =>
      array(
         'lhs_module' => 'DashboardManager',
         'lhs_table' => 'dashboardmanager',
         'lhs_key' => 'id',
         'rhs_module' => 'Users',
         'rhs_table' => 'users',
         'rhs_key' => 'forced_tabs_dashboard_id',
         'relationship_type' => 'one-to-many',
      ),
      'users_locked_dashboards' =>
      array(
         'lhs_module' => 'DashboardManager',
         'lhs_table' => 'dashboardmanager',
         'lhs_key' => 'id',
         'rhs_module' => 'Users',
         'rhs_table' => 'users',
         'rhs_key' => 'locked_dashboard_id',
         'relationship_type' => 'one-to-many',
      ),
      'users_one_time_default_dashboards' =>
      array(
         'lhs_module' => 'DashboardManager',
         'lhs_table' => 'dashboardmanager',
         'lhs_key' => 'id',
         'rhs_module' => 'Users',
         'rhs_table' => 'users',
         'rhs_key' => 'one_time_default_dashboard_id',
         'relationship_type' => 'one-to-many',
      ),
      'dashboardbackups_dashboardmanager' =>
      array(
         'lhs_module' => 'DashboardManager',
         'lhs_table' => 'dashboardmanager',
         'lhs_key' => 'id',
         'rhs_module' => 'DashboardBackups',
         'rhs_table' => 'dashboardbackups',
         'rhs_key' => 'dashboardmanager_id',
         'relationship_type' => 'one-to-many',
      ),
      'dashboardhistory_dashboardmanager' =>
      array(
         'lhs_module' => 'DashboardManager',
         'lhs_table' => 'dashboardmanager',
         'lhs_key' => 'id',
         'rhs_module' => 'DashboardHistory',
         'rhs_table' => 'dashboardhistory',
         'rhs_key' => 'dashboardmanager_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => false,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('DashboardManager', 'DashboardManager', array( 'basic', 'assignable' ));

$dictionary['DashboardManager']['fields']['name']['audited'] = true;
$dictionary['DashboardManager']['fields']['assigned_user_id']['audited'] = true;
