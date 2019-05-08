<?php

// created: 2018-10-13 15:14:30 
$dictionary["benefits_positions"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'benefits_positions' =>
      array(
         'lhs_module' => 'Benefits',
         'lhs_table' => 'benefits',
         'lhs_key' => 'id',
         'rhs_module' => 'Positions',
         'rhs_table' => 'positions',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'benefits_positions',
         'join_key_lhs' => 'benefit_id',
         'join_key_rhs' => 'position_id',
      ),
   ),
   'table' => 'benefits_positions',
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
            'benefit_id',
            'position_id',
         ),
      ),
   ),
);
