<?php

$module_name = 'Appraisals';
$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'Appraisals',
      ),
   ),
   'where' => '',
   'list_fields' =>
   array(
      'name' =>
      array(
         'vname' => 'LBL_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '45%',
         'default' => true,
      ),
      'status' =>
      array(
         'vname' => 'LBL_STATUS',
         'width' => '10%',
      ),
      'type' =>
      array(
         'vname' => 'LBL_TYPE',
         'width' => '10%',
      ),
      'date' =>
      array(
         'vname' => 'LBL_DATE',
         'width' => '10%',
      ),
      'employee_name' =>
      array(
         'vname' => 'LBL_EMPLOYEE_NAME',
         'width' => '10%',
      ),
      'candidature_name' =>
      array(
         'vname' => 'LBL_CANDIDATURE_NAME',
         'width' => '10%',
      ),
      'position_name' =>
      array(
         'vname' => 'LBL_POSITION_NAME',
         'width' => '10%',
      ),
      'edit_button' =>
      array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'Appraisals',
         'width' => '4%',
         'default' => true,
      ),
   ),
);
