<?php

$module_name = 'KTemplates';
$viewdefs [$module_name] = array(
   'EditView' =>
   array(
      'templateMeta' =>
      array(
         'maxColumns' => '2',
         'widths' =>
         array(
            array(
               'label' => '10',
               'field' => '10',
            ),
            array(
               'label' => '10',
               'field' => '60',
            ),
         ),
         'includes' =>
         array(
            array(
               'file' => 'include/javascript/tiny_mce/tiny_mce.js'
            ),
            array(
               'file' => 'modules/KTemplates/js/view.edit.js'
            ),
            array(
               'file' => 'modules/KTemplates/js/treeAddons.js'
            ),
         ),
         'useTabs' => false,
         'form' =>
         array(
            'hidden' =>
            array(
               '<input type="hidden" id="relatedmodule" name="relatedmodule" value="{$fields.relatedmodule.value}">',
            ),
         ),
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               '',
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
                  'label' => 'LBL_FIELDS',
               ),
            ),
         ),
      ),
   ),
);
?>
