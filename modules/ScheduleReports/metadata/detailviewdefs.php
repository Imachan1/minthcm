<?php

$module_name = 'ScheduleReports';
$viewdefs [$module_name] = array(
   'DetailView' =>
   array(
      'templateMeta' =>
      array(
         'form' =>
         array(
            'buttons' =>
            array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
               'FIND_DUPLICATES',
            ),
         ),
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
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               array(
                  'name' => 'template_id',
                  'studio' => 'visible',
                  'label' => 'LBL_TEMPLATE_ID',
               ),
            ),
            array(
               array(
                  'name' => 'kreport_name',
                  'label' => 'LBL_SCHEDULEREPORTS_KREPORTS_FROM_KREPORTS_TITLE',
               ),
               'email_template_id',
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
            array(
               'date_entered',
               'date_modified',
            ),
         ),
      ),
   ),
);
