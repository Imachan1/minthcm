<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'DashboardManager';
$searchFields[$module_name] = array(
   'name' => array( 'query_type' => 'default' ),
   'current_user_only' => array( 'query_type' => 'default', 'db_field' => array( 'assigned_user_id' ), 'my_items' => true, 'vname' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool' ),
   'assigned_user_id' => array( 'query_type' => 'default' ),
   'favorites_only' => array(
      'query_type' => 'format',
      'operator' => 'subquery',
      'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites 
			                    WHERE sugarfavorites.deleted=0 
			                        and sugarfavorites.module = \'' . $module_name . '\'
			                        and sugarfavorites.assigned_user_id = \'{0}\'',
      'db_field' => array( 'id' ) ),
   //Range Search Support 
   'range_date_entered' => array( 'query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true ),
   'start_range_date_entered' => array( 'query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true ),
   'end_range_date_entered' => array( 'query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true ),
   'range_date_modified' => array( 'query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true ),
   'start_range_date_modified' => array( 'query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true ),
   'end_range_date_modified' => array( 'query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true ),
        //Range Search Support 		
);
