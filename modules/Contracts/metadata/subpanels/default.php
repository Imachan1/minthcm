<?php

$module_name = 'Contracts';
$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
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
      'contract_type' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'vname' => 'LBL_CONTRACT_TYPE',
         'width' => '10%',
         'default' => true,
      ),
      'date_of_signing' =>
      array(
         'type' => 'date',
         'vname' => 'LBL_DATE_OF_SIGNING',
         'width' => '10%',
         'default' => true,
      ),
      'daily_working_time' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'vname' => 'LBL_DAILY_WORKING_TIME',
         'width' => '10%',
         'default' => true,
      ),
      'contract_starting_date' =>
      array(
         'type' => 'date',
         'vname' => 'LBL_CONTRACT_STARTING_DATE',
         'width' => '10%',
         'default' => true,
      ),
      'contract_ending_date' =>
      array(
         'type' => 'date',
         'vname' => 'LBL_CONTRACT_ENDING_DATE',
         'width' => '10%',
         'default' => true,
      ),
      'edit_button' =>
      array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'Contracts',
         'width' => '4%',
         'default' => true,
      ),
   ),
);
