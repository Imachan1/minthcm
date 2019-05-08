<?php

$module_name = 'CareerPaths';
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
      ),
      'advanced_search' =>
      array(
         'name' => array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'position_from_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_POSITION_FROM_NAME',
            'width' => '10%',
            'default' => true,
            'id' => 'position_from_id',
            'name' => 'position_from_name',
         ),
         'position_to_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_POSITION_TO_NAME',
            'width' => '10%',
            'default' => true,
            'id' => 'position_to_id',
            'name' => 'position_to_name',
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
