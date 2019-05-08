<?php

$module_name = 'Trainings';
$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
      ),
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'Trainings',
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
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'vname' => 'LBL_STATUS',
         'width' => '10%',
      ),
      'date_start' =>
      array(
         'type' => 'datetimecombo',
         'vname' => 'LBL_DATE_START',
         'width' => '10%',
         'default' => true,
      ),
      'date_end' =>
      array(
         'type' => 'datetimecombo',
         'vname' => 'LBL_DATE_END',
         'width' => '10%',
         'default' => true,
      ),
      'training_type' =>
      array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'vname' => 'LBL_TRAINING_TYPE',
         'width' => '10%',
      ),
      'parent_name' =>
      array(
         'vname' => 'LBL_PARENT_NAME',
         'width' => '25%',
         'id' => 'parent_id',
         'widget_class' => 'SubPanelDetailViewLink',
         'target_record_key' => 'parent_id',
         'target_module_key' => 'parent_type',
         'related_fields' =>
         array(
            0 => 'parent_id',
            1 => 'parent_type',
         ),
         'default' => true,
      ),
      'parent_id' => array(
         'usage' => 'query_only',
      ),
      'parent_type' => array(
         'usage' => 'query_only',
      ),
      'edit_button' =>
      array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'Trainings',
         'width' => '4%',
         'default' => true,
      ),
      'remove_button' =>
      array(
         'vname' => 'LBL_REMOVE',
         'widget_class' => 'SubPanelRemoveButton',
         'module' => 'Trainings',
         'width' => '5%',
         'default' => true,
      ),
   ),
);
