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
$viewdefs[$module_name]['DetailView'] = array(
   'templateMeta' => array( 'form' => array(
         'buttons' => array(
            'EDIT',
            'DUPLICATE',
            'DELETE',
            'FIND_DUPLICATES',
            array(
               'customCode' => true,
               'sugar_html' =>
               array(
                  'type' => 'button',
                  'value' => '{$MOD.LBL_CREATE_APPRAISAL}',
                  'htmlOptions' =>
                  array(
                     'class' => 'button',
                     'name' => 'generate_button',
                     'id' => 'generate_button',
                     'title' => '{$MOD.LBL_CREATE_APPRAISAL}',
                     'onClick' => 'generateAppraisalDialogBox.init()',
                  ),
               ),
            ),
            array(
               'customCode' => true,
               'sugar_html' =>
               array(
                  'type' => 'button',
                  'value' => '{$MOD.LBL_CONVERT}',
                  'htmlOptions' =>
                  array(
                     'class' => 'button',
                     'name' => 'convert_button',
                     'id' => 'convert_button',
                     'title' => '{$MOD.LBL_CONVERT}',
                     'onClick' => 'convertToEmployee.initialize()',
                  ),
               ),
            ),
         )
      ),
      'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      ),
      'useTabs' => true,
      'tabDefs' =>
      array(
         'LBL_RECORDVIEW_PANEL3' =>
         array(
            'newTab' => true,
            'panelDefault' => 'expanded',
         ),
         'LBL_RECORDVIEW_PANEL5' =>
         array(
            'newTab' => true,
            'panelDefault' => 'expanded',
         ),
         'LBL_RECORDVIEW_PANEL4' =>
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
      'includes' =>
      array(
         array(
            'file' => 'include/GenerateAppraisalAppraisalItems/generate_appraisals.js',
         ),
         array(
            'file' => 'modules/Candidatures/js/view.detail.js',
         ),
      ),
   ),
   'panels' => array(
      'LBL_RECORDVIEW_PANEL3' => array(
         array(
            'name',
         ),
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
            'recruitment_name',
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
            array(
               'name' => 'dg_amount',
               'label' => '{$MOD.LBL_DG_AMOUNT} ({$CURRENCY})',
            ),
         ),
         array(
            array(
               'name' => 'net_amount',
               'label' => '{$MOD.LBL_NET_AMOUNT} ({$CURRENCY})',
            ),
            array(
               'name' => 'gross_amount',
               'label' => '{$MOD.LBL_GROSS_AMOUNT} ({$CURRENCY})',
            ),
         ),
         array(
            'notice',
         ),
      ),
      'LBL_RECORDVIEW_PANEL4' => array(
         array(
            'final_employment_form',
            array(
               'name' => 'salary_net',
               'label' => '{$MOD.LBL_SALARY_NET} ({$CURRENCY})',
            ),
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
         array(
            array(
               'name' => 'date_entered',
               'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
               'label' => 'LBL_DATE_ENTERED',
            ),
            array(
               'name' => 'date_modified',
               'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
               'label' => 'LBL_DATE_MODIFIED',
            ),
         ),
      ),
   )
);
