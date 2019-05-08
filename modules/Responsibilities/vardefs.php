<?php

$dictionary['Responsibilities'] = array(
   'table' => 'responsibilities',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'positions' => array( 
         'name' => 'positions',
         'type' => 'link',
         'relationship' => 'responsibilities_positions',
         'source' => 'non-db',
         'module' => 'Positions',
         'bean_name' => 'Positions',
         'vname' => 'LBL_POSITIONS',
      ),
      'roles' => array( 
         'name' => 'roles',
         'type' => 'link',
         'relationship' => 'responsibilities_roles', 
         'source' => 'non-db',
         'module' => 'EmployeeRoles',
         'bean_name' => 'EmployeeRoles',
         'vname' => 'LBL_ROLES',
      ),
      'appraisalitems' => array(
         'name' => 'appraisalitems',
         'type' => 'link',
         'relationship' => 'appraisalitems_responsibilities',
         'module' => 'AppraisalItems',
         'bean_name' => 'AppraisalItems',
         'source' => 'non-db',
         'vname' => 'LBL_APPRAISALITEMS',
      ),
      'activities' => array( 
         'name' => 'activities',
         'type' => 'link',
         'relationship' => 'responsibilities_activities', 
         'source' => 'non-db',
         'module' => 'ResponsibilityActivities',
         'bean_name' => 'ResponsibilityActivities',
         'vname' => 'LBL_ACTIVITIES',
      ),
   ),
   'relationships' => array(
      'appraisalitems_responsibilities' => array(
         'lhs_module' => 'Responsibilities',
         'lhs_table' => 'responsibilities',
         'lhs_key' => 'id',
         'rhs_module' => 'AppraisalItems',
         'rhs_table' => 'appraisalitems',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Responsibilities'
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Responsibilities', 'Responsibilities', array( 'basic', 'assignable', 'security_groups' ));

