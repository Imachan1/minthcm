<?php

$module_name = 'PDFTemplates';
$listViewDefs [$module_name] = array(
    'NAME' =>
    array(
        'width' => '32%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'IS_DEFAULT' =>
    array(
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_IS_DEFAULT',
        'width' => '10%',
    ),
    'RELATEDMODULE' =>
    array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_RELATEDMODULE',
        'sortable' => true,
        'width' => '10%',
    ),
    'TYPE' =>
    array(
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'ORIENTATION' =>
    array(
        'type' => 'enum',
        'label' => 'LBL_ORIENTATION',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_MODIFIED' =>
    array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
    ),
    'MODIFIED_BY_NAME' =>
    array(
        'type' => 'relate',
        'link' => 'modified_user_link',
        'label' => 'LBL_MODIFIED_NAME',
        'width' => '10%',
        'default' => false,
    ),
    'CREATED_BY_NAME' =>
    array(
        'type' => 'relate',
        'link' => 'created_by_link',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' =>
    array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
);
?>
