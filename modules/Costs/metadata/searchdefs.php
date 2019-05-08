<?php

$module_name = 'Costs';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'current_user_only' =>
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
            'default' => true,
            'width' => '10%',
         ),
      ),
      'advanced_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'assigned_user_id' =>
         array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' =>
            array(
               'name' => 'get_user_array',
               'params' =>
               array(
                  0 => false,
               ),
            ),
            'default' => true,
            'width' => '10%',
         ),
         'type' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'sortable' => false,
            'width' => '10%',
            'name' => 'type',
         ),
         'cost_amount' =>
         array(
            'type' => 'currency',
            'label' => 'LBL_COST_AMOUNT',
            'currency_format' => true,
            'width' => '10%',
            'default' => true,
            'name' => 'cost_amount',
         ),
         'cost_date' =>
         array(
            'type' => 'date',
            'label' => 'LBL_COST_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'cost_date',
         ),
         'accommodation_no' =>
         array(
            'type' => 'enum',
            'default' => true,
            'label' => 'LBL_ACCOMMODATION_NO',
            'width' => '10%',
            'name' => 'accommodation_no',
         ),
         'type_of_meal' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_TYPE_OF_MEAL',
            'width' => '10%',
            'name' => 'type_of_meal',
         ),
         'cost_city' =>
         array(
            'type' => 'varchar',
            'label' => 'LBL_COST_CITY',
            'width' => '10%',
            'default' => true,
            'name' => 'cost_city',
         ),
         'transportation_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_TRANSPORTATION_NAME',
            'id' => 'TRANSPORTATION_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'transportation_name',
         ),
         'delegation_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_DELEGATION_NAME',
            'id' => 'DELEGATION_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'delegation_name',
         ),
      ),
   ),
   'templateMeta' =>
   array(
      'maxColumns' => '3',
      'widths' =>
      array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
