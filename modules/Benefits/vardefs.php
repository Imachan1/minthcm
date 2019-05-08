<?php

$dictionary['Benefits'] = array(
   'table' => 'benefits',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'employees' => array(
         'name' => 'employees',
         'type' => 'link',
         'relationship' => 'benefits_employees',
         'source' => 'non-db',
         'module' => 'Employees',
         'bean_name' => 'Employee',
         'vname' => 'LBL_EMPLOYEES',
      ),
      'positions' => array( 
         'name' => 'positions',
         'type' => 'link',
         'relationship' => 'benefits_positions',
         'source' => 'non-db',
         'module' => 'Positions',
         'bean_name' => 'Positions',
         'vname' => 'LBL_POSITIONS',
      ),
      'roles' => array( 
         'name' => 'roles',
         'type' => 'link',
         'relationship' => 'benefits_roles', 
         'source' => 'non-db',
         'module' => 'EmployeeRoles',
         'bean_name' => 'EmployeeRoles',
         'vname' => 'LBL_ROLES',
      ),
   ),
   'relationships' => array(
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Benefits', 'Benefits', array( 'basic', 'assignable', 'security_groups' ));

