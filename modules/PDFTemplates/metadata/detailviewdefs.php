<?php

$module_name = 'PDFTemplates';
$viewdefs [$module_name] = array(
           'DetailView' =>
           array(
              'templateMeta' =>
              array(
                 'form' =>
                 array(
                    'buttons' =>
                    array(
                       0 => 'EDIT',
                       1 => 'DUPLICATE',
                       2 => 'DELETE',
                    ),
                 ),
                 'maxColumns' => '2',
                 'widths' =>
                 array(
                    0 =>
                    array(
                       'label' => '10',
                       'field' => '30',
                    ),
                    1 =>
                    array(
                       'label' => '10',
                       'field' => '30',
                    ),
                 ),
                 'includes' => array(array('file' => 'modules/PDFTemplates/js/detailview.js')),
                 'useTabs' => false,
              ),
              'panels' =>
              array(
                 'default' =>
                 array(
                    array(
                       0 => 'name',
                       1 => array(
                          'name' => 'type',
                       ),
                    ),
                    array(
                       0 =>
                       array(
                          'name' => 'is_default',
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
                 'LBL_PANEL_ASSIGNMENT' =>
                 array(
                    array(
                       array(
                          'name' => 'date_entered',
                          'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                       ),
                       array(
                          'name' => 'date_modified',
                          'label' => 'LBL_DATE_MODIFIED',
                          'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                       ),
                    ),
                 ),
                 array(
                    array(
                       array(
                          'name' => 'preview',
                          'label' => '<span id="preview_label_span">{$MOD.LBL_PREVIEW}</span>',
                       ),
                    ),
                 ),
              ),
           ),
);
?>
