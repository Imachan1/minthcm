<?php

$dictionary["users_schedulereports"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' => array(
      'users_schedulereports' => array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'ScheduleReports',
         'rhs_table' => 'schedulereports',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'users_schedulereports',
         'join_key_lhs' => 'user_id',
         'join_key_rhs' => 'schedulereport_id',
      ),
   ),
   'table' => 'users_schedulereports',
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
         'name' => 'user_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'schedulereport_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' => array(
      array(
         'name' => 'users_schedulereports_spk',
         'type' => 'primary',
         'fields' => array(
            'id',
         ),
      ),
      array(
         'name' => 'user_id_alt',
         'type' => 'index',
         'fields' => array(
            'user_id',
         ),
      ),
      array(
         'name' => 'schedulereport_id_alt',
         'type' => 'index',
         'fields' => array(
            'schedulereport_id',
         ),
      ),
   ),
);
