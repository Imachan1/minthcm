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
$viewdefs[$module_name]['QuickCreate'] = array(
   'templateMeta' => array( 'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      ),
   ),
   'panels' => array(
      'default' => array(
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
   ),
);
?>
