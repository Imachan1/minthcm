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
$module_name = 'Candidatures';
$viewdefs[$module_name]['EditView'] = array(
   'templateMeta' => array(
      'includes' => array(
         array(
            'file' => 'modules/Candidatures/js/view.edit.js',
         ),
      ),
      'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      ),
   ),
   'panels' => array(
      'LBL_RECORDVIEW_PANEL3' => array(
         array(
            'status',
            'to_decision',
         ),
         array(
            'work_start',
            'training_date',
         ),
         array(
            'reason_for_rejection',
         ),
         array(
            'candidate_name',
            array(
               'name' => 'recruitment_name',
               'displayParams' => array(
                  'call_back_function' => 'recruitmentNameCallBack',
               ),
            ),
         ),
         array(
            'start_date',
            'recruitment_end_name',
         ),
         array(
            'status_information',
         ),
         array(
            'entry_interview',
         ),
         array(
            'source',
            'task_grade',
         ),
         array(
            'scoring',
            '',
         ),
      ),
      'LBL_RECORDVIEW_PANEL5' => array(
         array(
            'employment_form',
         ),
         array(
            'dg_amount',
            'currency_id',
         ),
         array(
            'net_amount',
            'gross_amount',
         ),
         array(
            'notice',
         ),
      ),
      'LBL_RECORDVIEW_PANEL4' => array(
         array(
            'final_employment_form',
            'salary_net',
         ),
         array(
            'notice_final_expectations',
         ),
      ),
      'LBL_SHOW_MORE_INFORMATION' => array(
         array(
            'assigned_user_name',
         ),
         array(
            'description',
         ),
      ),
   ),
);
?>
