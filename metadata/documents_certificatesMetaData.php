<?php
$dictionary["documents_certificates"] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
    array(
        'documents_certificates' =>
        array(
            'lhs_module' => 'Documents',
            'lhs_table' => 'documents',
            'lhs_key' => 'id',
            'rhs_module' => 'Certificates',
            'rhs_table' => 'certificates',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'documents_certificates',
            'join_key_lhs' => 'document_id',
            'join_key_rhs' => 'certificate_id',
        ),
    ),
    'table' => 'documents_certificates',
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
            'name' => 'certificate_id',
            'type' => 'varchar',
            'len' => 36,
        ),
    ),
    'indices' =>
    array(
        array(
            'name' => 'documents_certificatesspk',
            'type' => 'primary',
            'fields' =>
            array(
                'id',
            ),
        ),
        array(
            'name' => 'documents_certificates_certificate_id',
            'type' => 'alternate_key',
            'fields' =>
            array(
                'certificate_id',
                'document_id',
            ),
        ),
    ),
);

