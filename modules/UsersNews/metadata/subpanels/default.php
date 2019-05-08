<?php

$module_name = 'UsersNews';
$subpanel_layout = array(
   'top_buttons' =>
   array(
      array(
         'widget_class' => 'SubPanelTopCreateButton',
      ),
      array(
         'widget_class' => 'SubPanelTopSelectButton',
         'popup_module' => 'UsersNews',
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
      'news_read' =>
      array(
         'type' => 'bool',
         'default' => true,
         'vname' => 'LBL_NEWS_READ',
         'width' => '10%',
      ),
      'not_display' =>
      array(
         'type' => 'date',
         'vname' => 'LBL_NOT_DISPLAY',
         'width' => '10%',
         'default' => true,
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
         'module' => 'UsersNews',
         'width' => '4%',
         'default' => true,
      ),
      'remove_button' =>
      array(
         'vname' => 'LBL_REMOVE',
         'widget_class' => 'SubPanelRemoveButton',
         'module' => 'UsersNews',
         'width' => '5%',
         'default' => true,
      ),
   ),
);
