<?php
$dictionary["documents_contracts"] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
    array(
        'documents_contracts' =>
        array(
            'lhs_module' => 'Documents',
            'lhs_table' => 'documents',
            'lhs_key' => 'id',
            'rhs_module' => 'Contracts',
            'rhs_table' => 'contracts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'documents_contracts',
            'join_key_lhs' => 'document_id',
            'join_key_rhs' => 'contract_id',
        ),
    ),
    'table' => 'documents_contracts',
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
            'name' => 'contract_id',
            'type' => 'varchar',
            'len' => 36,
        ),
    ),
    'indices' =>
    array(
        array(
            'name' => 'documents_contractsspk',
            'type' => 'primary',
            'fields' =>
            array(
                'id',
            ),
        ),
        array(
            'name' => 'documents_contracts_contract_id',
            'type' => 'alternate_key',
            'fields' =>
            array(
                'contract_id',
                'document_id',
            ),
        ),
    ),
);

