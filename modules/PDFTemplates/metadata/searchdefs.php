<?php

$module_name = 'PDFTemplates';
$searchdefs [$module_name] = array(
           'layout' =>
           array(
              'basic_search' =>
              array(
                 'name' =>
                 array(
                    'name' => 'name',
                    'default' => true,
                    'width' => '10%',
                 ),
                 'is_default' =>
                 array(
                    'type' => 'bool',
                    'default' => true,
                    'label' => 'LBL_IS_DEFAULT',
                    'width' => '10%',
                    'name' => 'is_default',
                 ),
                 'relatedmodule' =>
                 array(
                    'type' => 'enum',
                    'default' => true,
                    'studio' => 'visible',
                    'label' => 'LBL_RELATEDMODULE',
                    'width' => '10%',
                    'name' => 'relatedmodule',
                 ),
              ),
              'advanced_search' =>
              array(
                 'name' =>
                 array(
                    'name' => 'name',
                    'default' => true,
                    'width' => '10%',
                 ),
                 'is_default' =>
                 array(
                    'type' => 'bool',
                    'default' => true,
                    'label' => 'LBL_IS_DEFAULT',
                    'width' => '10%',
                    'name' => 'is_default',
                 ),
                 'relatedmodule' =>
                 array(
                    'type' => 'enum',
                    'default' => true,
                    'studio' => 'visible',
                    'label' => 'LBL_RELATEDMODULE',
                    'width' => '10%',
                    'name' => 'relatedmodule',
                 ),
                 'type' =>
                 array(
                    'type' => 'enum',
                    'default' => true,
                    'studio' => 'visible',
                    'label' => 'LBL_TYPE',
                    'width' => '10%',
                    'name' => 'type',
                 ),
                 'orientation' =>
                 array(
                    'type' => 'enum',
                    'default' => true,
                    'studio' => 'visible',
                    'label' => 'LBL_ORIENTATION',
                    'width' => '10%',
                    'name' => 'orientation',
                 ),
              ),
           ),
           'templateMeta' =>
           array(
              'maxColumns' => '3',
              'widths' =>
              array(
                 'label' => '10',
                 'field' => '30',
              ),
           ),
);
?>
