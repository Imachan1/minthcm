<?php

$module_name = 'WorkSchedules';
$subpanel_layout = array(
   'top_buttons' => array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
      ),
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'workschedules',
      ),
   ),
   'where' => '',
   'list_fields' => array(
      'name' => array(
         'vname' => 'LBL_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '45%',
         'default' => true,
      ),
      'schedule_date' => array(
         'type' => 'date',
         'vname' => 'LBL_SCHEDULE_DATE',
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
      'status' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'vname' => 'LBL_STATUS',
         'width' => '10%',
      ),
      'supervisor_acceptance' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'vname' => 'LBL_SUPERVISOR_ACCEPTANCE',
         'width' => '10%',
      ),
      'assigned_user_name' => array(
         'width' => '9%',
         'vname' => 'LBL_ASSIGNED_TO_NAME',
         'module' => 'Employees',
         'id' => 'ASSIGNED_USER_ID',
         'default' => true,
      ),
      'edit_button' => array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'workschedules',
         'width' => '4%',
         'default' => true,
      ),
      'remove_button' => array(
         'vname' => 'LBL_REMOVE',
         'widget_class' => 'SubPanelRemoveButton',
         'module' => 'workschedules',
         'width' => '5%',
         'default' => true,
      ),
   ),
);
