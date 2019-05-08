<?php

$module_name = 'WorkSchedules';
$viewdefs [$module_name] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
               'FIND_DUPLICATES',
               array(
                  'customCode' => '{include file="modules/WorkSchedules/tpls/CloseButton.tpl"}'
                  . '{include file="modules/WorkSchedules/tpls/AcceptWorkPlanButton.tpl"}'
                  . '{include file="modules/WorkSchedules/tpls/UndoAcceptanceButton.tpl"}',
               ),
            ),
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
               'file' => 'modules/WorkSchedules/js/detail.js',
            ),
            array(
               'file' => 'modules/WorkSchedules/tpls/TimeTrackingPane.js',
            ),
         ),
      ),
      'panels' => array(
         'default' => array(
            array(
               'name',
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
                  'name' => 'schedule_date',
                  'label' => 'LBL_SCHEDULE_DATE',
               ),
               array(
                  'name' => 'spent_time',
                  'label' => 'LBL_SPENT_TIME',
               ),
            ),
            array(
               array(
                  'name' => 'date_start',
                  'label' => 'LBL_DATE_START',
               ),
               array(
                  'name' => 'date_end',
                  'label' => 'LBL_DATE_END',
               ),
            ),
            array(
               array(
                  'name' => 'spent_time_settlement',
                  'label' => 'LBL_SPENT_TIME_SETTLEMENT',
               ),
               array(
                  'label' => 'LBL_DURATION',
                  'customCode' => '{$fields.duration_hours.value} <span class="dateFormat">{$MOD.LBL_HOURS_HOURS}</span>&nbsp;{$fields.duration_minutes.value} <span class="dateFormat">{$MOD.LBL_HOURS_MINUTES}</span>',
               ),
            ),
            array(
               'delegation_name',
               '',
            ),
            array(
               array(
                  'name' => 'supervisor_acceptance',
                  'studio' => 'visible',
                  'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
               ),
               '',
            ),
            array(
               'delegation_duration',
            ),
         ),
         '' => array(
            array(
               array(
                  'name' => 'time_tracking_pane',
                  'customCode' => '{include file="modules/WorkSchedules/tpls/TimeTrackingPane.tpl"}',
               ),
            ),
         ),
         'LBL_PANEL_ASSIGNMENT' => array(
            array(
               array(
                  'name' => 'date_entered',
                  'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}'
               ),
               array(
                  'name' => 'date_modified',
                  'label' => 'LBL_DATE_MODIFIED',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}'
               )
            )
         ),
      ),
   ),
);
