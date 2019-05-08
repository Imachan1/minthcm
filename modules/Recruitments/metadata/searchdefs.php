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
/*
 * Created on May 29, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$module_name = 'Recruitments';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         0 => 'name',
         1 =>
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
         ),
         2 =>
         array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
         ),
      ),
      'advanced_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'employees_number' =>
         array(
            'label' => 'LBL_EMPLOYEES_NUMBER',
            'type' => 'int',
            'width' => '10%',
            'default' => true,
            'name' => 'employees_number',
         ),
         'position_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
            'id' => 'POSITION_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'position_name',
         ),
         'salary_from' =>
         array(
            'type' => 'currency',
            'default' => true,
            'related_fields' =>
            array(
               0 => 'currency_id',
            ),
            'label' => 'LBL_SALARY_FROM',
            'currency_format' => true,
            'width' => '10%',
            'name' => 'salary_from',
         ),
         'salary_to' =>
         array(
            'type' => 'currency',
            'default' => true,
            'related_fields' =>
            array(
               0 => 'currency_id',
            ),
            'label' => 'LBL_SALARY_TO',
            'currency_format' => true,
            'width' => '10%',
            'name' => 'salary_to',
         ),
         'recruitment_channels' =>
         array(
            'type' => 'multienum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_RECRUITMENT_CHANNELS',
            'width' => '10%',
            'name' => 'recruitment_channels',
         ),
         'vacancy' =>
         array(
            'label' => 'LBL_VACANCY',
            'type' => 'int',
            'width' => '10%',
            'default' => true,
            'name' => 'vacancy',
         ),
         'recruitment_type' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_RECRUITMENT_TYPE',
            'width' => '10%',
            'name' => 'recruitment_type',
         ),
         'project_status' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_PROJECT_STATUS',
            'width' => '10%',
            'name' => 'project_status',
         ),
         'start_work_date' =>
         array(
            'label' => 'LBL_START_WORK_DATE',
            'type' => 'date',
            'width' => '10%',
            'default' => true,
            'name' => 'start_work_date',
         ),
         'start_date' =>
         array(
            'label' => 'LBL_START_DATE',
            'type' => 'date',
            'width' => '10%',
            'default' => true,
            'name' => 'start_date',
         ),
         'end_date' =>
         array(
            'label' => 'LBL_END_DATE',
            'type' => 'date',
            'width' => '10%',
            'default' => true,
            'name' => 'end_date',
         ),
         'assigned_user_id' =>
         array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' =>
            array(
               'name' => 'get_user_array',
               'params' =>
               array(
                  0 => false,
               ),
            ),
            'default' => true,
            'width' => '10%',
         ),
         'favorites_only' =>
         array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
            'default' => true,
            'width' => '10%',
         ),
      ),
   ),
   'templateMeta' =>
   array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' =>
      array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
