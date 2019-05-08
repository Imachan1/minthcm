<?php

// created: 2018-10-11 11:45:12
$dictionary["trainings_documents"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'trainings_documents' =>
      array(
         'lhs_module' => 'Trainings',
         'lhs_table' => 'trainings',
         'lhs_key' => 'id',
         'rhs_module' => 'Documents',
         'rhs_table' => 'documents',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'trainings_documents',
         'join_key_lhs' => 'training_id',
         'join_key_rhs' => 'document_id',
      ),
   ),
   'table' => 'trainings_documents',
   'fields' =>
   array(
      0 =>
      array(
         'name' => 'id',
         'type' => 'varchar',
         'len' => 36,
      ),
      1 =>
      array(
         'name' => 'date_modified',
         'type' => 'datetime',
      ),
      2 =>
      array(
         'name' => 'deleted',
         'type' => 'bool',
         'len' => '1',
         'default' => '0',
         'required' => true,
      ),
      3 =>
      array(
         'name' => 'training_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      4 =>
      array(
         'name' => 'document_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' =>
   array(
      0 =>
      array(
         'name' => 'trainings_documentsspk',
         'type' => 'primary',
         'fields' =>
         array(
            0 => 'id',
         ),
      ),
      1 =>
      array(
         'name' => 'trainings_documents_alt',
         'type' => 'alternate_key',
         'fields' =>
         array(
            0 => 'training_id',
            1 => 'document_id',
         ),
      ),
   ),
);
