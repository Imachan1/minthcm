<?php
$dictionary["documents_termsofemployment"] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
    array(
        'documents_termsofemployment' =>
        array(
            'lhs_module' => 'Documents',
            'lhs_table' => 'documents',
            'lhs_key' => 'id',
            'rhs_module' => 'TermsOfEmployment',
            'rhs_table' => 'termsofemployment',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'documents_termsofemployment',
            'join_key_lhs' => 'document_id',
            'join_key_rhs' => 'termsofemployment_id',
        ),
    ),
    'table' => 'documents_termsofemployment',
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
            'name' => 'termsofemployment_id',
            'type' => 'varchar',
            'len' => 36,
        ),
    ),
    'indices' =>
    array(
        array(
            'name' => 'documents_termsofemploymentspk',
            'type' => 'primary',
            'fields' =>
            array(
                'id',
            ),
        ),
        array(
            'name' => 'documents_termsofemployment_termsofemployment_id',
            'type' => 'alternate_key',
            'fields' =>
            array(
                'termsofemployment_id',
                'document_id',
            ),
        ),
    ),
);

