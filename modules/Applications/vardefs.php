<?php

$dictionary['Applications'] = array(
   'table' => 'applications',
   'audited' => true,
   'activity_enabled' => false,
   'duplicate_merge' => true,
   'fields' => array(
      'status' => array(
         'required' => true,
         'audited' => true,
         'dependency' => false,
         'no_default' => false,
         'massupdate' => true,
         'importable' => false,
         'calculated' => false,
         'unified_search' => false,
         'reportable' => true,
         'name' => 'status',
         'vname' => 'LBL_STATUS',
         'type' => 'enum',
         'default' => 'new',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'merge_filter' => 'disabled',
         'len' => 100,
         'size' => '20',
         'options' => 'applications_status_list',
         'studio' => 'visible',
      ),
      'type' => array(
         'required' => true,
         'audited' => true,
         'dependency' => false,
         'no_default' => false,
         'massupdate' => true,
         'importable' => false,
         'calculated' => false,
         'unified_search' => false,
         'reportable' => true,
         'name' => 'type',
         'vname' => 'LBL_TYPE',
         'type' => 'enum',
         'default' => '',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'merge_filter' => 'disabled',
         'len' => 100,
         'size' => '20',
         'options' => 'applications_type_list',
         'studio' => 'visible',
      ),
   ),
   'relationships' => array(
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);

if ( !class_exists('VardefManager') ) {
   require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('Applications', 'Applications', array( 'basic', 'assignable', 'security_groups', 'employee_related' ));
