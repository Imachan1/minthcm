<?php

$module_name = 'SpentTime';
$viewdefs[$module_name]['QuickCreate'] = array(
   'templateMeta' => array(
      'maxColumns' => '2',
      'widths' => array(
         array(
            'label' => '10',
            'field' => '30'
         ),
         array(
            'label' => '10',
            'field' => '30'
         )
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
            'spent_time',
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
            array(
               'name' => 'workschedule_name',
               'displayParams' => array(
                  'call_back_function' => 'set_return_red_user_st',
                  'field_to_name_array' => array(
                     'id' => 'workschedule_id',
                     'name' => 'workschedule_name',
                     'date_start' => 'work_date',
                  ),
               ),
            ),
         ),
      ),
      'LBL_PANEL_TASK' => array(
         array(
            'remaining_hours',
            'done_ratio',
         ),
      ),
   ),
);
