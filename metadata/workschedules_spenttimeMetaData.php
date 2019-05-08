<?php

$dictionary["workschedules_spenttime"] = array(
   'true_relationship_type' => 'one-to-many',
   'relationships' => array(
      'workschedules_spenttime' =>
      array(
         'lhs_module' => 'WorkSchedules',
         'lhs_table' => 'workschedules',
         'lhs_key' => 'id',
         'rhs_module' => 'SpentTime',
         'rhs_table' => 'spenttime',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'workschedules_spenttime',
         'join_key_lhs' => 'workschedule_id',
         'join_key_rhs' => 'spenttime_id',
      ),
   ),
   'table' => 'workschedules_spenttime',
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
         'name' => 'workschedule_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'spenttime_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' =>
   array(
      array(
         'name' => 'workschedules_spenttime_pk',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'workschedules_spenttime_ida1',
         'type' => 'index',
         'fields' =>
         array(
            'workschedule_id',
         ),
      ),
      array(
         'name' => 'workschedules_spenttime_alt',
         'type' => 'alternate_key',
         'fields' =>
         array(
            'spenttime_id',
         ),
      ),
   ),
);
