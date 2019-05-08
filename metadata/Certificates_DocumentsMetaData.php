<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
$dictionary["documents"] = array(
   'true_relationship_type' => 'many-to-many',
   'from_studio' => true,
   'relationships' => array(
      'documents' => array(
         'lhs_module' => 'Certificates',
         'lhs_table' => 'certificates',
         'lhs_key' => 'id',
         'rhs_module' => 'Documents',
         'rhs_table' => 'documents',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'certificates_documents',
         'join_key_lhs' => 'certificates_id',
         'join_key_rhs' => 'documents_id',
      ),
   ),
   'table' => 'certificates_documents',
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
         'name' => 'certificates_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'documents_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' => array(
      array(
         'name' => 'certificates_id_documents_id_spk',
         'type' => 'primary',
         'fields' => array(
            'id',
         ),
      ),
      array(
         'name' => 'certificates_id_alt',
         'type' => 'index',
         'fields' => array(
            'certificates_id',
         ),
      ),
      array(
         'name' => 'documents_id_alt',
         'type' => 'index',
         'fields' => array(
            'documents_id',
         ),
      ),
   ),
);
