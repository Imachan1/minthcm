<?php

$dictionary["candidates_employees"] = array(
   'true_relationship_type' => 'one-to-one',
   'relationships' =>
   array(
      'candidates_employees' =>
      array(
         'lhs_module' => 'Candidates',
         'lhs_table' => 'candidates',
         'lhs_key' => 'id',
         'rhs_module' => 'Users',
         'rhs_table' => 'users',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'candidates_employees',
         'join_key_lhs' => 'candidate_id',
         'join_key_rhs' => 'employee_id',
      ),
   ),
   'table' => 'candidates_employees',
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
         'name' => 'candidate_id',
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
         'name' => 'candidates_employees_spk',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'candidates_lhs_alt',
         'type' => 'index',
         'fields' =>
         array(
            'candidate_id',
         ),
      ),
      array(
         'name' => 'employees_alt',
         'type' => 'index',
         'fields' =>
         array(
            'employee_id',
         ),
      ),
   ),
);

