<?php

// created: 2015-05-04 09:39:39
$searchFields['Recruitments'] = array(
   'name' =>
   array(
      'query_type' => 'default',
   ),
   'current_user_only' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'assigned_user_id',
      ),
      'my_items' => true,
      'vname' => 'LBL_CURRENT_USER_FILTER',
      'type' => 'bool',
   ),
   'assigned_user_id' =>
   array(
      'query_type' => 'default',
   ),
   'favorites_only' =>
   array(
      'query_type' => 'format',
      'operator' => 'subquery',
      'checked_only' => true,
      'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites 
			                    WHERE sugarfavorites.deleted=0 
			                        and sugarfavorites.module = \'Recruitments\'
			                        and sugarfavorites.assigned_user_id = \'{0}\'',
      'db_field' =>
      array(
         'id',
      ),
   ),
   'range_date_entered' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'start_range_date_entered' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'end_range_date_entered' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'range_date_modified' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'start_range_date_modified' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'end_range_date_modified' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'range_employees_number' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'start_range_employees_number' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'end_range_employees_number' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'range_vacancy' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'start_range_vacancy' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'end_range_vacancy' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'range_end_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'start_range_end_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'end_range_end_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'start_range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'end_range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'range_start_work_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'start_range_start_work_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
   'end_range_start_work_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => false,
   ),
);
