<?php
$dictionary["documents_candidatures"] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
    array(
        'documents_candidatures' =>
        array(
            'lhs_module' => 'Documents',
            'lhs_table' => 'documents',
            'lhs_key' => 'id',
            'rhs_module' => 'Candidatures',
            'rhs_table' => 'candidatures',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'documents_candidatures',
            'join_key_lhs' => 'document_id',
            'join_key_rhs' => 'candidature_id',
        ),
    ),
    'table' => 'documents_candidatures',
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
            'name' => 'document_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        array(
            'name' => 'candidature_id',
            'type' => 'varchar',
            'len' => 36,
        ),
    ),
    'indices' =>
    array(
        array(
            'name' => 'documents_candidaturesspk',
            'type' => 'primary',
            'fields' =>
            array(
                'id',
            ),
        ),
        array(
            'name' => 'documents_candidatures_candidature_id',
            'type' => 'alternate_key',
            'fields' =>
            array(
                'candidature_id',
                'document_id',
            ),
        ),
    ),
);

