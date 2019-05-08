<?php

// created: 2018-10-13 15:14:30 
$dictionary["benefits_roles"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'benefits_roles' =>
      array(
         'lhs_module' => 'Benefits',
         'lhs_table' => 'benefits',
         'lhs_key' => 'id',
         'rhs_module' => 'EmployeeRoles',
         'rhs_table' => 'employeeroles',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'benefits_roles',
         'join_key_lhs' => 'benefit_id',
         'join_key_rhs' => 'role_id',
      ),
   ),
   'table' => 'benefits_roles',
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
         'name' => 'benefit_id',
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
            'benefit_id',
            'role_id',
         ),
      ),
   ),
);
