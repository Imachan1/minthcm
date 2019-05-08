<?php

$module_name = 'CompetencyRatings';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         'name',
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
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
         'rating' =>
         array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_RATING',
            'width' => '10%',
            'default' => true,
            'name' => 'rating',
         ),
         array(
            'name' => 'competency_name',
            'label' => 'LBL_COMPETENCY_NAME',
         ),
         'parent_name' => array(
            'type' => 'parent',
            'label' => 'LBL_PARENT_NAME',
            'width' => '10%',
            'default' => true,
            'name' => 'parent_name',
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
      ),
   ),
   'templateMeta' =>
   array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' =>
      array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
;
?>
