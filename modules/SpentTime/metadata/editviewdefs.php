<?php

$module_name = 'SpentTime';
$viewdefs[$module_name] = array(
   'EditView' => array(
      'templateMeta' => array(
         'form' => array(
            'hidden' => array(
               '<input type="hidden" id="projecttask_issue_tracker" name="projecttask_issue_tracker" value="{$fields.projecttask_issue_tracker.value}" />',
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
         'includes' => array(
            array(
               'file' => 'include/javascript/moment.min.js'
            ),
            array(
               'file' => 'modules/SpentTime/js/view.edit.js',
            ),
         ),
      ),
      'panels' => array(
         'default' => array(
            array(
               'employee_name',
               'assigned_user_name',
            ),
            array(
               'work_date',
               array(
                  'name' => 'workschedule_name',
                  'displayParams' => array(
                     'field_to_name_array' => array(
                        'id' => 'workschedule_id',
                        'name' => 'workschedule_name',
                        'date_start' => 'work_date'
                     ),
                     'call_back_function' => 'set_return_overload',
                  ),
               )
            ),
            array(
               'spent_time',
               '',
            ),
            array(
               array(
                  'name' => 'date_start',
                  'displayParams' => array(
                     'minutesStep' => 5,
                  ),
               ),
               array(
                  'name' => 'date_end',
                  'displayParams' => array(
                     'minutesStep' => 5,
                  ),
               ),
            ),
            array(
               'description',
            ),
         ),
      ),
   ),
);
