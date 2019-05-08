<?php

$viewdefs['SpentTime'] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DELETE',
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
         'includes' => array(
            array(
               'file' => 'include/javascript/moment.min.js'
            ),
         )
      ),
      'panels' => array(
         'default' => array(
            array(
               'name',
            ),
            array(
               'employee_name',
               'assigned_user_name',
            ),
            array(
               'work_date',
               'spent_time',
            ),
            array(
               'date_start',
               'date_end',
            ),
            array(
               'workschedule_name',
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
      ),
   ),
);
