<?php

// created: 2015-04-29 15:00:21
$searchFields['Certificates'] = array(
   'name' =>
   array(
      'query_type' => 'default',
   ),
   'status' =>
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
   'favorites_only' =>
   array(
      'query_type' => 'format',
      'operator' => 'subquery',
      'checked_only' => true,
      'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites 
			                    WHERE sugarfavorites.deleted=0 
			                        and sugarfavorites.module = \'Certificates\' 
			                        and sugarfavorites.assigned_user_id = \'{0}\'',
      'db_field' =>
      array(
         'id',
      ),
   ),
   'range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'start_range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'end_range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'range_end_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'start_range_end_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'end_range_end_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'start_range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'end_range_start_date' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
);
