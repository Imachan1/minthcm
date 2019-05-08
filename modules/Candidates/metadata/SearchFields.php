<?php

// created: 2015-04-29 15:00:21
$searchFields['Candidates'] = array(
   'first_name' =>
   array(
      'query_type' => 'default',
   ),
   'last_name' =>
   array(
      'query_type' => 'default',
   ),
   'search_name' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'first_name',
         'last_name',
      ),
      'force_unifiedsearch' => true,
   ),
   'do_not_call' =>
   array(
      'query_type' => 'default',
      'input_type' => 'checkbox',
      'operator' => '=',
   ),
   'phone' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'phone_mobile',
         'phone_work',
         'phone_other',
         'phone_fax',
         'assistant_phone',
      ),
   ),
   'email' =>
   array(
      'query_type' => 'default',
      'operator' => 'subquery',
      'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.email_address LIKE',
      'db_field' =>
      array(
         0 => 'id',
      ),
      'vname' => 'LBL_ANY_EMAIL',
   ),
   'address_street' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'primary_address_street',
         'alt_address_street',
      ),
   ),
   'address_city' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'primary_address_city',
         'alt_address_city',
      ),
   ),
   'address_state' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'primary_address_state',
         'alt_address_state',
      ),
   ),
   'address_postalcode' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'primary_address_postalcode',
         'alt_address_postalcode',
      ),
   ),
   'address_country' =>
   array(
      'query_type' => 'default',
      'db_field' =>
      array(
         'primary_address_country',
         'alt_address_country',
      ),
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
			                        and sugarfavorites.module = \'Candidates\' 
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
   'range_birthdate' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'start_range_birthdate' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
   'end_range_birthdate' =>
   array(
      'query_type' => 'default',
      'enable_range_search' => true,
      'is_date_field' => true,
   ),
);
