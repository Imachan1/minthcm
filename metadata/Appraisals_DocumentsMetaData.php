<?php

// created: 2018-10-11 11:45:12
$dictionary["appraisals_documents"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'appraisals_documents' =>
      array(
         'lhs_module' => 'Appraisals',
         'lhs_table' => 'appraisals',
         'lhs_key' => 'id',
         'rhs_module' => 'Documents',
         'rhs_table' => 'documents',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'appraisals_documents',
         'join_key_lhs' => 'appraisal_id',
         'join_key_rhs' => 'document_id',
      ),
   ),
   'table' => 'appraisals_documents',
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
         'name' => 'appraisal_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'document_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' =>
   array(
      array(
         'name' => 'appraisals_documentsspk',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'appraisals_documents_alt',
         'type' => 'alternate_key',
         'fields' =>
         array(
            'appraisal_id',
            'document_id',
         ),
      ),
   ),
);
