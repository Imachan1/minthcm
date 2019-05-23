<?php
$dictionary["documents_candidates"] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
    array(
        'documents_candidates' =>
        array(
            'lhs_module' => 'Documents',
            'lhs_table' => 'documents',
            'lhs_key' => 'id',
            'rhs_module' => 'Candidates',
            'rhs_table' => 'candidates',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'documents_candidates',
            'join_key_lhs' => 'document_id',
            'join_key_rhs' => 'candidate_id',
        ),
    ),
    'table' => 'documents_candidates',
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
            'name' => 'candidate_id',
            'type' => 'varchar',
            'len' => 36,
        ),
    ),
    'indices' =>
    array(
        array(
            'name' => 'documents_candidatesspk',
            'type' => 'primary',
            'fields' =>
            array(
                'id',
            ),
        ),
        array(
            'name' => 'documents_candidates_candidate_id',
            'type' => 'alternate_key',
            'fields' =>
            array(
                'candidate_id',
                'document_id',
            ),
        ),
    ),
);

