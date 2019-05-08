<?php

$module_name = 'Transportations';
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
         'from_city' => array(
            'type' => 'varchar',
            'label' => 'LBL_FROM_CITY',
            'width' => '10%',
            'default' => true,
            'name' => 'from_city',
         ),
         'to_city' => array(
            'type' => 'varchar',
            'label' => 'LBL_TO_CITY',
            'width' => '10%',
            'default' => true,
            'name' => 'to_city',
         ),
         'trans_date' => array(
            'type' => 'date',
            'label' => 'LBL_TRANS_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'trans_date',
         ),
         'type' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'sortable' => false,
            'width' => '10%',
            'name' => 'type',
         ),
         'other_transportation' => array(
            'type' => 'varchar',
            'label' => 'LBL_OTHER_TRANSPORTATION',
            'width' => '10%',
            'default' => true,
            'name' => 'other_transportation',
         ),
         array(
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
