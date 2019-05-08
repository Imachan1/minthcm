<?php

$module_name = 'OnboardingOffboardingElements';
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
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
                       'type',
            ),
         array(
            'user_name',
            'own_task',
         ),
         array(
            'days_from_start',
            'task_duration',
         ),
            array(
               'assigned_user_name',
            ),
            array(
               'description',
            ),
         ),
      ),
   ),
);
