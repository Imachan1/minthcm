<?php

$module_name = 'KTemplates';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => true,
   ),
   'MODIFIED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => 'modified_user_link',
      'label' => 'LBL_MODIFIED_NAME',
      'width' => '10%',
      'default' => false,
   ),
   'CREATED_BY_NAME' =>
   array(
      'type' => 'relate',
      'link' => 'created_by_link',
      'label' => 'LBL_CREATED',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '10%',
      'default' => false,
   ),
);
?>
