<?php

// created: 2011-05-26 13:20:36
$subpanel_layout['list_fields'] = array(
   'name' => array(
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
   ),
   'from_city' => array(
      'type' => 'varchar',
      'vname' => 'LBL_FROM_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'to_city' => array(
      'type' => 'varchar',
      'vname' => 'LBL_TO_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'type' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_TYPE',
      'width' => '10%',
   ),
   'other_transportation' => array(
      'type' => 'varchar',
      'vname' => 'LBL_OTHER_TRANSPORTATION',
      'width' => '10%',
      'default' => true,
   ),
   'trans_date' => array(
      'type' => 'date',
      'vname' => 'LBL_TRANS_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'assigned_user_name' => array(
      'vname' => 'LBL_ASSIGNED_TO_NAME',
      'width' => '30%'
   ),
   'edit_button' => array(
      'widget_class' => 'SubPanelEditButton',
      'module' => 'Transportations',
      'width' => '4%',
      'default' => true,
   ),
   'remove_button' => array(
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'Transportations',
      'width' => '5%',
      'default' => true,
   ),
);
