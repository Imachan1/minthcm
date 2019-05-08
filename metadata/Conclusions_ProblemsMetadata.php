<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
$dictionary["conclusions_problems"] = array(
   'true_relationship_type' => 'many-to-many',
   'from_studio' => true,
   'relationships' => array(
      'conclusions_problems' => array(
         'lhs_module' => 'Conclusions',
         'lhs_table' => 'conclusions',
         'lhs_key' => 'id',
         'rhs_module' => 'Problems',
         'rhs_table' => 'problems',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'conclusions_problems',
         'join_key_lhs' => 'conclusion_id',
         'join_key_rhs' => 'problem_id',
      ),
   ),
   'table' => 'conclusions_problems',
   'fields' => array(
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
         'name' => 'conclusion_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'problem_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' => array(
      array(
         'name' => 'conclusion_id_problem_id_spk',
         'type' => 'primary',
         'fields' => array(
            'id',
         ),
      ),
      array(
         'name' => 'conclusion_id_alt',
         'type' => 'index',
         'fields' => array(
            'conclusion_id',
         ),
      ),
      array(
         'name' => 'problem_id_alt',
         'type' => 'index',
         'fields' => array(
            'problem_id',
         ),
      ),
   ),
);
