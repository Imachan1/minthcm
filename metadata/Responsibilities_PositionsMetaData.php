<?php

// created: 2018-10-13 15:14:30 
$dictionary["responsibilities_positions"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'responsibilities_positions' =>
      array(
         'lhs_module' => 'Responsibilities',
         'lhs_table' => 'responsibilities',
         'lhs_key' => 'id',
         'rhs_module' => 'Positions',
         'rhs_table' => 'positions',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'responsibilities_positions',
         'join_key_lhs' => 'responsibility_id',
         'join_key_rhs' => 'position_id',
      ),
   ),
   'table' => 'responsibilities_positions',
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
         'name' => 'position_id',
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
            'position_id',
         ),
      ),
   ),
);
