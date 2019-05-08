<?php

// created: 2018-10-13 15:14:30 
$dictionary["benefits_employees"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'benefits_employees' =>
      array(
         'lhs_module' => 'Benefits',
         'lhs_table' => 'benefits',
         'lhs_key' => 'id',
         'rhs_module' => 'Employees',
         'rhs_table' => 'users',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'benefits_employees',
         'join_key_lhs' => 'benefit_id',
         'join_key_rhs' => 'employee_id',
      ),
   ),
   'table' => 'benefits_employees',
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
         'name' => 'benefits_users_id',
         'type' => 'alternate_key',
         'fields' =>
         array(
            'benefit_id',
            'employee_id',
         ),
      ),
   ),
);
