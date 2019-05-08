<?php

$module_name = 'Transportations';
$viewdefs [$module_name] = array(
   'QuickCreate' => array(
      'templateMeta' => array(
         'form' => array(
            'hidden' => array(
               '<input type="hidden" name="delegation_name" value="{$fields.delegation_name.value}">',
               '<input type="hidden" name="delegation_id" value="{$fields.delegation_id.value}">',
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
