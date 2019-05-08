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
$viewdefs[$module_name]['EditView'] = array(
   'templateMeta' => array( 'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      )
   ),
   'panels' => array(
      'default' => array(
         // array(
         //    'name'
         // ),
         array(
            'start_date',
            'end_date'
         ),
         array(
            'project_status',
            'position_name'
         ),
         array(
            'currency_id',
         ),
         array(
            'salary_to',
            'salary_from'
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
      ),
      'LBL_SHOW_MORE_INFORMATION' => array(
         array(
            'assigned_user_name', ''
         )
      )
   )
);
