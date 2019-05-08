<?php

// created: 2018-10-13 15:14:30 
$dictionary["roles_employees"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'roles_employees' =>
      array(
         'lhs_module' => 'EmployeeRoles',
         'lhs_table' => 'employeeroles',
         'lhs_key' => 'id',
         'rhs_module' => 'Employees',
         'rhs_table' => 'users',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'roles_employees',
         'join_key_lhs' => 'role_id',
         'join_key_rhs' => 'employee_id',
      ),
   ),
   'table' => 'roles_employees',
   'fields' =>
   array(
      array(
         'name' => 'id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'date_modified',
         'type' => 'datetime',
      ),
      array(
         'name' => 'deleted',
         'type' => 'bool',
         'len' => '1',
         'default' => '0',
         'required' => true,
      ),
      array(
         'name' => 'role_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'employee_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' =>
   array(
      array(
         'name' => 'id_index',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'roles_users_id',
         'type' => 'alternate_key',
         'fields' =>
         array(
            'role_id',
            'employee_id',
         ),
      ),
   ),
);
