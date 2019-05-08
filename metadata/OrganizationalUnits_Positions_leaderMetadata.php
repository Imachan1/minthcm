<?php

$dictionary["organizationalunits_positions_leader"] = array(
   'true_relationship_type' => 'one-to-one',
   'relationships' =>
   array(
      'organizationalunits_positions_leader' =>
      array(
         'lhs_module' => 'OrganizationalUnits',
         'lhs_table' => 'organizationalunits',
         'lhs_key' => 'id',
         'rhs_module' => 'Positions',
         'rhs_table' => 'positions',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'organizationalunits_positions_leader',
         'join_key_lhs' => 'organizationalunits_leader_id',
         'join_key_rhs' => 'position_leader_id',
      ),
   ),
   'table' => 'organizationalunits_positions_leader',
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
         'name' => 'organizationalunits_leader_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'position_leader_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' =>
   array(
      array(
         'name' => 'organizationalunits_positions_leader_spk',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'organizationalunits_lhs_alt',
         'type' => 'index',
         'fields' =>
         array(
            'organizationalunits_leader_id',
         ),
      ),
      array(
         'name' => 'positions_rhs_alt',
         'type' => 'index',
         'fields' =>
         array(
            'position_leader_id',
         ),
      ),
   ),
);
