<?php

$dictionary["responsibilities_activities"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'responsibilities_activities' =>
      array(
         'lhs_module' => 'Responsibilities',
         'lhs_table' => 'responsibilities',
         'lhs_key' => 'id',
         'rhs_module' => 'ResponsibilityActivities',
         'rhs_table' => 'responsibilityactivities',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'responsibilities_activities',
         'join_key_lhs' => 'responsibility_id',
         'join_key_rhs' => 'activity_id',
      ),
   ),
   'table' => 'responsibilities_activities',
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
         'name' => 'activity_id',
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
         'name' => 'responsibilities_activities_id',
         'type' => 'alternate_key',
         'fields' =>
         array(
            'responsibility_id',
            'activity_id',
         ),
      ),
   ),
);
