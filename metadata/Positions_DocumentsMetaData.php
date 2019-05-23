<?php
$dictionary["positions_documents"] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' => array(
        'positions_documents' => array(
            'lhs_module' => 'Positions',
            'lhs_table' => 'positions',
            'lhs_key' => 'id',
            'rhs_module' => 'Documents',
            'rhs_table' => 'documents',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'positions_documents',
            'join_key_lhs' => 'positions_lhs_id',
            'join_key_rhs' => 'documents_rhs_id',
        ),
    ),
    'table' => 'positions_documents',
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
            'name' => 'positions_lhs_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        array(
            'name' => 'documents_rhs_id',
            'type' => 'varchar',
            'len' => 36,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'positions_documents_spk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'positions_lhs_alt',
            'type' => 'index',
            'fields' => array(
                'positions_lhs_id',
            ),
        ),
        array(
            'name' => 'documents_rhs_alt',
            'type' => 'index',
            'fields' => array(
                'documents_rhs_id',
            ),
        ),
    ),
);

