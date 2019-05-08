<?php

// created: 2018-10-13 15:14:30 
$dictionary["responsibilities_roles"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'responsibilities_roles' =>
      array(
         'lhs_module' => 'Responsibilities',
         'lhs_table' => 'responsibilities',
         'lhs_key' => 'id',
         'rhs_module' => 'EmployeeRoles',
         'rhs_table' => 'employeeroles',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'responsibilities_roles',
         'join_key_lhs' => 'responsibility_id',
         'join_key_rhs' => 'role_id',
      ),
   ),
   'table' => 'responsibilities_roles',
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
         'name' => 'responsibility_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'role_id',
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
            'responsibility_id',
            'role_id',
         ),
      ),
   ),
);
