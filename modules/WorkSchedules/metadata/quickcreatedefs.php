2<?php
$module_name = 'WorkSchedules';
$viewdefs[$module_name] = array(
   'QuickCreate' => array(
      'templateMeta' => array(
         'form' => array(
            'hidden' => array(
               '<input type="hidden" name="current_user_is_admin" id="current_user_is_admin" value="{$CURRENT_USER_IS_ADMIN}">',
            ),
         ),
         'maxColumns' => '2',
         'widths' => array(
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
         'tabDefs' => array(
            'DEFAULT' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
         ),
         'includes' => array(
            array(
               'file' => 'include/javascript/moment.min.js'
            ),
            array(
               'file' => 'modules/WorkSchedules/js/qc.js',
            ),
            array(
               'file' => 'modules/WorkSchedules/js/edit.js',
            ),
         ),
      ),
      'panels' => array(
         'default' => array(
            array(
               'assigned_user_name',
            ),
            array(
               array(
                  'name' => 'type',
                  'studio' => 'visible',
                  'label' => 'LBL_TYPE',
               ),
               array(
                  'name' => 'status',
                  'studio' => 'visible',
                  'label' => 'LBL_STATUS',
                  'displayParams' => array(
                     'readonly' => true,
                  ),
               ),
            ),
            array(
               array(
                  'name' => 'comments',
                  'label' => 'LBL_COMMENTS',
               ),
               array(
                  'name' => 'occasional_leave_type',
                  'label' => 'LBL_OCCASIONAL_LEAVE_TYPE',
               ),
            ),
            array(
               array(
                  'name' => 'date_start',
                  'label' => 'LBL_DATE_START',
                  'displayParams' =>
                  array(
                     'minutesStep' => 5,
                  ),
               ),
               array(
                  'name' => 'date_end',
                  'label' => 'LBL_DATE_END',
                  'displayParams' =>
                  array(
                     'minutesStep' => 5,
                  ),
               ),
            ),
            array(
               array(
                  'name' => 'duration_hours',
                  'label' => 'LBL_DURATION',
                  'customCode' => '{include file="modules/WorkSchedules/tpls/DurationFieldEditView.tpl"}',
               ),
               'delegation_name',
            ),
            array(
               'delegation_duration',
            ),
         ),
      ),
   ),
);
