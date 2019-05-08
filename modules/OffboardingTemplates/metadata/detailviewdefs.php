<?php

$module_name = 'OffboardingTemplates';
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
               array(
                  'customCode' => true,
                  'sugar_html' =>
                  array(
                     'type' => 'button',
                     'value' => '{$MOD.LBL_GENERATE_BUTTON}',
                     'htmlOptions' =>
                     array(
                        'class' => 'button',
                        'name' => 'generate_button',
                        'id' => 'generate_button',
                        'title' => '{$MOD.LBL_GENERATE_BUTTON}',
                        'onClick' => 'generateOnboardingOffboarding.init()',
                     ),
                     'template' => '[CONTENT]',
                  ),
               ),
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
         'useTabs' => true,
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
         ),
         'includes' =>
         array(
            array(
               'file' => 'modules/OnboardingTemplates/js/view.detail.js',
            ),
         ),
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               'position_name',
            ),
            array(
               'description',
            ),
         ),
         'LBL_PANEL_ASSIGNMENT' =>
         array(
            array(
               'assigned_user_name',
               '',
            ),
            array(
               array(
                  'name' => 'date_entered',
                  'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}'
               ),
               array(
                  'name' => 'date_modified',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}'
               )
            )
         ),
      ),
   ),
);
