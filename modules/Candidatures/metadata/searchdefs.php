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
$module_name = 'Candidatures';
$searchdefs[$module_name] = array(
   'templateMeta' => array(
      'maxColumns' => '3',
      'maxColumnsBasic' => '4',
      'widths' => array(
         'label' => '10',
         'field' => '30'
      ),
   ),
   'layout' => array(
      'basic_search' => array(
         'name',
         array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool'
         ),
         array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
         ),
      ),
      'advanced_search' => array(
         'name',
         array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array(
               'name' => 'get_user_array',
               'params' => array( false )
            )
         ),
         'recruitment_end_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_CANDIDATURES_RECRUITMENTS_END_FROM_RECRUITMENTS_TITLE',
            'id' => 'RECRUITMENT_END_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'recruitment_end_name',
         ),
         'recruitment_name' =>
         array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_CANDIDATURES_RECRUITMENTS_FROM_RECRUITMENTS_TITLE',
            'id' => 'RECRUITMENT_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'recruitment_name',
         ),
         array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
         ),
         'scoring' =>
         array(
            'type' => 'enum',
            'default' => true,
            'label' => 'SCORING',
            'width' => '10%',
            'name' => 'scoring',
         ),
         'source' =>
         array(
            'type' => 'enum',
            'default' => true,
            'label' => 'LBL_SOURCE',
            'width' => '10%',
            'name' => 'source',
         ),
         'status' =>
         array(
            'type' => 'enum',
            'default' => true,
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'name' => 'status',
         ),
         'task_grade' =>
         array(
            'type' => 'enum',
            'default' => true,
            'label' => 'LBL_TASK_GRADE',
            'width' => '10%',
            'name' => 'task_grade',
         ),
      ),
   ),
);
