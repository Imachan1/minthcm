<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'SpentTime';
$subpanel_layout = array(
   'top_buttons' => array(
      array(
         'widget_class' => 'SubPanelTopCreateButton'
      ),
   ),
   'where' => '',
   'list_fields' => array(
      'name' => array(
         'vname' => 'LBL_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '10%',
      ),
      'spent_time' => array(
         'vname' => 'LBL_SPENT_TIME',
         'width' => '10%',
      ),
      'date_start' => array(
         'vname' => 'LBL_DATE_START',
         'width' => '10%',
      ),
      'date_end' => array(
         'vname' => 'LBL_DATE_END',
         'width' => '10%',
      ),
      'description' => array(
         'vname' => 'LBL_DESCRIPTION',
         'width' => '10%',
      ),
      'edit_button' => array(
         'widget_class' => 'SubPanelEditButton',
         'module' => $module_name,
         'width' => '10%',
      ),
   ),
);
