<?php
// created: 2018-10-11 11:45:12
$dictionary["exitinterviews_documents"] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
    array(
        'exitinterviews_documents' =>
        array(
            'lhs_module' => 'ExitInterviews',
            'lhs_table' => 'exitinterviews',
            'lhs_key' => 'id',
            'rhs_module' => 'Documents',
            'rhs_table' => 'documents',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'exitinterviews_documents',
            'join_key_lhs' => 'exitinterview_id',
            'join_key_rhs' => 'document_id',
        ),
    ),
    'table' => 'exitinterviews_documents',
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
            'name' => 'exitinterview_id',
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
            'name' => 'exitinterviews_documentsspk',
            'type' => 'primary',
            'fields' =>
            array(
                'id',
            ),
        ),
        array(
            'name' => 'exitinterviews_documents_alt',
            'type' => 'alternate_key',
            'fields' =>
            array(
                'exitinterview_id',
                'document_id',
            ),
        ),
    ),
);
