<?php

$module_name = 'OrganizationalUnits';
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
               array(
                  'name' => 'parent_name',
                  'label' => 'LBL_MEMBER_OF'
               ),
               'assigned_user_name',
            ),
            array(
               array(
                  'name' => 'current_manager_name',
                  'label' => 'LBL_CURRENT_MANAGER_NAME',
               ),
               array(
                  'name' => 'position_leader_name',
                  'label' => 'LBL_POSITION_LEADER_NAME'
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
