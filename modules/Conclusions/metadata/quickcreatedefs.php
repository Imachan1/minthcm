<?php

$module_name = 'Conclusions';
$viewdefs[$module_name]['QuickCreate'] = array(
   'templateMeta' => array(
      'maxColumns' => '2',
      'widths' => array(
         array( 'label' => '10', 'field' => '30' ),
         array( 'label' => '10', 'field' => '30' )
      ),
   ),
   'panels' => array(
      'default' => array(
         array(
            'name',
            array(
               'name' => 'meeting_name',
               'label' => 'LBL_MEETING_NAME',
            ),
         ),
         array(
            'assigned_user_name',
            ''
         ),
      ),
   ),
);
