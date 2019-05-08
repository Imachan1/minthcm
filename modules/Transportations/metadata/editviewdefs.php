<?php

$module_name = 'Transportations';
$viewdefs [$module_name] = array(
   'EditView' => array(
      'templateMeta' => array(
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
      ),
      'panels' => array(
         'default' => array(
            array(
               array(
                  'name' => 'from_city',
                  'label' => 'LBL_FROM_CITY',
               ),
               array(
                  'name' => 'to_city',
                  'label' => 'LBL_TO_CITY',
               ),
            ),
            array(
               array(
                  'name' => 'type',
                  'studio' => 'visible',
                  'label' => 'LBL_TYPE',
               ),
               array(
                  'name' => 'other_transportation',
                  'label' => 'LBL_OTHER_TRANSPORTATION',
               ),
            ),
            array(
               array(
                  'name' => 'trans_date',
                  'label' => 'LBL_TRANS_DATE',
               ),
               array(
                  'name' => 'delegation_name',
               ),
            ),
            array(
               array(
                  'name' => 'description',
                  'label' => 'LBL_DESCRIPTION',
               ),
            ),
         ),
      ),
   ),
);
