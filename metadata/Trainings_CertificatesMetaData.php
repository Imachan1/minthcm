<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
$dictionary["certificates_trainings"] = array(
   'true_relationship_type' => 'many-to-many',
   'from_studio' => true,
   'relationships' => array(
      'certificates_trainings' => array(
         'lhs_module' => 'Trainings',
         'lhs_table' => 'trainings',
         'lhs_key' => 'id',
         'rhs_module' => 'Certificates',
         'rhs_table' => 'certificates',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'certificates_trainings',
         'join_key_lhs' => 'trainings_id',
         'join_key_rhs' => 'certificates_id',
      ),
   ),
   'table' => 'certificates_trainings',
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
         'name' => 'trainings_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'certificates_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' => array(
      array(
         'name' => 'trainings_id_certificates_id_spk',
         'type' => 'primary',
         'fields' => array(
            'id',
         ),
      ),
      array(
         'name' => 'trainings_id_alt',
         'type' => 'index',
         'fields' => array(
            'trainings_id',
         ),
      ),
      array(
         'name' => 'certificates_id_alt',
         'type' => 'index',
         'fields' => array(
            'certificates_id',
         ),
      ),
   ),
);
