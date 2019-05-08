<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */


$subpanel_layout = array(
   'top_buttons' => array(
      array( 'widget_class' => 'SubPanelTopCreateButton' ),
      array( 'widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'People' ),
   ),
   'where' => '',
   'list_fields' => array(
      'name' => array(
         'vname' => 'LBL_NAME',
         'widget_class' => 'SubPanelDetailViewLink',
         'width' => '15%',
      ),
      'start_date' => array(
         'name' => 'start_date',
         'vname' => 'LBL_START_DATE',
      ),
      'end_date' => array(
         'name' => 'end_date',
         'vname' => 'LBL_END_DATE',
      ),
      'status' => array(
         'name' => 'status',
         'vname' => 'LBL_STATUS',
      ),
      'employee_name' => array(
         'vname' => 'LBL_EMPLOYEE',
         'width' => '15%',
      ),
      'assigned_user_name' => array(
         'vname' => 'LBL_ASSIGNED_TO_NAME',
         'width' => '15%',
      ),
      'date_modified' => array(
         'vname' => 'LBL_DATE_MODIFIED',
         'width' => '15%',
      ),
      'edit_button' => array(
         'vname' => 'LBL_EDIT_BUTTON',
         'widget_class' => 'SubPanelEditButton',
         'module' => 'Contacts',
      ),
   ),
);
