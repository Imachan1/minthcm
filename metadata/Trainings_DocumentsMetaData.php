<?php
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
            'name' => 'training_id',
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
            'name' => 'trainings_documentsspk',
            'type' => 'primary',
            'fields' =>
            array(
                'id',
            ),
        ),
        array(
            'name' => 'trainings_documents_alt',
            'type' => 'alternate_key',
            'fields' =>
            array(
                'training_id',
                'document_id',
            ),
        ),
    ),
);
