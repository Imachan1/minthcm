<?php

$module_name = 'Offboardings';
$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
      ),
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'Offboardings',
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
      'date_start' =>
      array(
         'type' => 'datetimecombo',
         'vname' => 'LBL_DATE_START',
         'width' => '10%',
         'default' => true,
      ),
      'status' =>
      array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'vname' => 'LBL_STATUS',
         'width' => '10%',
      ),
      'employee_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'vname' => 'LBL_EMPLOYEE_NAME',
         'id' => 'EMPLOYEE_ID',
         'width' => '10%',
         'default' => true,
         'widget_class' => 'SubPanelDetailViewLink',
         'target_module' => 'Users',
         'target_record_key' => 'employee_id',
      ),
      'assigned_user_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'vname' => 'LBL_ASSIGNED_TO_NAME',
         'id' => 'ASSIGNED_USER_ID',
         'width' => '10%',
         'default' => true,
         'widget_class' => 'SubPanelDetailViewLink',
         'target_module' => 'Users',
         'target_record_key' => 'assigned_user_id',
      ),
      'edit_button' =>
      array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'Offboardings',
         'width' => '4%',
         'default' => true,
      ),
   ),
);
