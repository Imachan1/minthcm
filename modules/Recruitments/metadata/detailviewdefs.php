<?php

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
$module_name = 'Recruitments';
$viewdefs[$module_name]['DetailView'] = array(
   'templateMeta' => array( 'form' => array( 'buttons' => array( 'EDIT', 'DUPLICATE', 'DELETE', 'FIND_DUPLICATES' ) ),
      'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      ),
      'useTabs' => true,
      'tabDefs' =>
      array(
         'DEFAULT' =>
         array(
            'newTab' => true,
            'panelDefault' => 'expanded',
         ),
         'LBL_SHOW_MORE_INFORMATION' =>
         array(
            'newTab' => true,
            'panelDefault' => 'expanded',
         ),
      ),
   ),
   'panels' => array(
      'default' => array(
         array(
            'name'
         ),
         array(
            'start_date',
            'end_date'
         ),
         array(
            'project_status',
            'position_name'
         ),
         array(
            array(
               'name' => 'salary_to',
               'label' => '{$MOD.LBL_SALARY_TO} ({$CURRENCY})',
            ),
            array(
               'name' => 'salary_from',
               'label' => '{$MOD.LBL_SALARY_FROM} ({$CURRENCY})',
            ),
         ),
         array(
            'description'
         ),
         array(
            'vacancy',
            'start_work_date'
         ),
         array(
            'recruitment_channels',
            'recruitment_type'
         ),
         array(
            'employees_number',
            ''
         )
      ),
      'LBL_SHOW_MORE_INFORMATION' => array(
         array(
            'assigned_user_name', ''
         ),
         array(
            array(
               'name' => 'date_entered',
               'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
               'label' => 'LBL_DATE_ENTERED'
            ),
            array(
               'name' => 'date_modified',
               'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
               'label' => 'LBL_DATE_MODIFIED'
            )
         )
      )
   )
);
