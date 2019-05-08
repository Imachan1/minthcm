<?php

$module_name = 'ScheduleReports';
$viewdefs [$module_name] = array(
   'EditView' =>
   array(
      'templateMeta' =>
      array(
         'maxColumns' => '2',
         'widths' =>
         array(
            array(
               'label' => '10',
               'field' => '30',
            ),
            array(
               'label' => '10',
               'field' => '30',
            ),
         ),
         'useTabs' => false,
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
         ),
         'includes' =>
         array(
            array(
               'file' => 'modules/ScheduleReports/js/view.edit.js',
            ),
         ),
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               array(
                  'name' => 'template_id',
                  'label' => 'LBL_TEMPLATE_ID',
               ),
            ),
            array(
               array(
                  'name' => 'kreport_name',
                  'label' => 'LBL_SCHEDULEREPORTS_KREPORTS_FROM_KREPORTS_TITLE',
                  'displayParams' =>
                  array(
                     'call_back_function' => 'updateTemplatesList',
                  ),
               ),
               array(
                  'name' => 'email_template_id',
                  'label' => 'LBL_EMAIL_TEMPLATE_ID',
               ),
            ),
            array(
               array(
                  'name' => 'frequency_performance',
                  'studio' => 'visible',
                  'label' => 'LBL_FREQUENCY_PERFORMANCE',
               ),
               array(
                  'name' => 'active',
                  'label' => 'LBL_ACTIVE',
               ),
            ),
            array(
               'description',
            ),
         ),
      ),
   ),
);
?>