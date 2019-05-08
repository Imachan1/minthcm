<?php

$module_name = 'PDFTemplates';
$viewdefs [$module_name] = array(
           'EditView' =>
           array(
              'templateMeta' =>
              array(
                 'maxColumns' => '3',
                 'widths' =>
                 array(
                    0 =>
                    array(
                       'label' => '10',
                       'field' => '10',
                    ),
                    1 =>
                    array(
                       'label' => '10',
                       'field' => '60',
                    ),
                 ),
                 'includes' =>
                 array(
                    array('file' => 'modules/PDFTemplates/js/tiny_mce/tiny_mce.js'),
                    array('file' => 'modules/PDFTemplates/js/scripts.js'),
                 ),
                 'useTabs' => false,
              ),
              'panels' =>
              array(
                 'default' =>
                 array(
                    array(
                       'name',
                       array(
                          'name' => 'type',
                       ),
                    ),
                    array(
                       0 =>
                       array(
                          'name' => 'is_default',
                          'studio' => 'visible',
                          'label' => 'LBL_IS_DEFAULT',
                       ),
                       1 => 
                       array(
                          'name' => 'orientation',
                       ),
                    ),
                    array(
                       0 =>
                       array(
                          'name' => 'relatedmodule',
                          'studio' => 'visible',
                          'label' => 'LBL_RELATEDMODULE',
                       ),
                       1 => ''
                    ),
                 ),
                 '' =>
                 array(
                    array(
                       array(
                          'hideLabel' => true,
                           
                          'name' => 'fields',
                          'studio' => 'visible',
                          'type' => 'treeAndTiny',
                         // 'label' => 'LBL_FIELDS',
                       ),
                        
                    ),
                 ),
              ),
           ),
);
?>
