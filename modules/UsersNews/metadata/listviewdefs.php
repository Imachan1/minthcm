<?php

$module_name = 'UsersNews';
$listViewDefs [$module_name] = array(
   'NAME' =>
   array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'NEWS_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_NEWS_NAME',
      'module' => 'News',
      'id' => 'NEWS_ID',
      'default' => true,
   ),
   'NEWS_READ' =>
   array(
      'type' => 'bool',
      'default' => true,
      'label' => 'LBL_NEWS_READ',
      'width' => '10%',
   ),
   'NOT_DISPLAY' =>
   array(
      'type' => 'date',
      'label' => 'LBL_NOT_DISPLAY',
      'width' => '10%',
      'default' => true,
   ),
   'ASSIGNED_USER_NAME' =>
   array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'DATE_ENTERED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_ENTERED',
      'width' => '10%',
      'default' => false,
   ),
   'DATE_MODIFIED' =>
   array(
      'type' => 'datetime',
      'label' => 'LBL_DATE_MODIFIED',
      'width' => '10%',
      'default' => false,
   ),
);
;
?>
