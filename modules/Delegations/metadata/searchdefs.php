<?php

$module_name = 'Delegations';
$searchdefs [$module_name] = array(
   'layout' => array(
      'basic_search' => array(
         'name',
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
         ),
      ),
      'advanced_search' => array(
         'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array(
               'name' => 'get_user_array',
               'params' => array(
                  false,
               ),
            ),
            'default' => true,
            'width' => '10%',
         ),
         'start_date' => array(
            'type' => 'datetimecombo',
            'label' => 'LBL_START_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'start_date',
         ),
         'end_date' => array(
            'type' => 'datetimecombo',
            'label' => 'LBL_END_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'end_date',
         ),
      ),
   ),
   'templateMeta' => array(
      'maxColumns' => '3',
      'widths' => array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
