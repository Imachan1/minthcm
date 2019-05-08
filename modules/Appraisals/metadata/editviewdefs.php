<?php

$module_name = 'Appraisals';
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
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               'status',
            ),
            array(
               'date',
               'type',
            ),
            array(
               'assigned_user_name',
               'employee_name',
            ),
            array(
               'position_name',
               'candidature_name',
            ),
            array(
               array(
                  'name' => 'appraisal_items_inline',
                  'label' => 'LBL_APPRAISAL_ITEMS_INLINE',
                  'hideLabel' => true,
               ),
            ),
            array(
               'description',
            ),
         ),
      ),
   ),
);
;
?>
