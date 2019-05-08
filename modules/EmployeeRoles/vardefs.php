<?php

$dictionary['EmployeeRoles'] = array(
   'table' => 'employeeroles',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'status' => array(
         'name' => 'status',
         'vname' => 'LBL_STATUS',
         'type' => 'enum',
         'required' => false,
         'massupdate' => false,
         'importable' => 'true',
         'duplicate_merge' => 'enabled',
         'duplicate_merge_dom_value' => '1',
         'audited' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'calculated' => false,
         'len' => 100,
         'size' => '20',
         'options' => 'role_status',
         'default' => 'active',
      ),
      'employees' => array(
         'name' => 'employees',
         'type' => 'link',
         'relationship' => 'roles_employees',
         'source' => 'non-db',
         'module' => 'Employees',
         'bean_name' => 'Employee',
         'vname' => 'LBL_EMPLOYEES',
      ),
      'benefits' => array(
         'name' => 'benefits',
         'type' => 'link',
         'relationship' => 'benefits_roles',
         'source' => 'non-db',
         'module' => 'Benefits',
         'bean_name' => 'Benefits',
         'vname' => 'LBL_BENEFITS',
      ),
      'responsibilities' => array(
         'name' => 'responsibilities',
         'type' => 'link',
         'relationship' => 'responsibilities_roles',
         'source' => 'non-db',
         'module' => 'Responsibilities',
         'bean_name' => 'Responsibilities',
         'vname' => 'LBL_RESPONSIBILITIES',
      ),
      'competencyratings' => array(
         'name' => 'competencyratings',
         'type' => 'link',
         'relationship' => 'competencyratings_roles',
         'module' => 'CompetencyRatings',
         'bean_name' => 'CompetencyRatings',
         'source' => 'non-db',
         'vname' => 'LBL_COMPETENCYRATINGS',
      ),
      'appraisals' => array(
         'name' => 'appraisals',
         'type' => 'link',
         'relationship' => 'appraisals_roles',
         'module' => 'Appraisals',
         'bean_name' => 'Appraisals',
         'source' => 'non-db',
         'vname' => 'LBL_APPRAISALS',
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
VardefManager::createVardef('EmployeeRoles', 'EmployeeRoles', array( 'basic', 'assignable', 'security_groups' ));

