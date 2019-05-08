<?php

$module_name = 'Recruitments';
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
   ),
);
?>