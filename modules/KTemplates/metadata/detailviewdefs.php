<?php

$module_name = 'KTemplates';
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
         'includes' => array(
            array( 'file' => 'modules/KTemplates/js/view.detail.js' )
         ),
         'useTabs' => false,
      ),
      'panels' =>
      array(
         'default' =>
         array(
            0 =>
            array(
               0 => 'name',
               1 =>
               array(
                  'name' => 'relatedmodule',
                  'studio' => 'visible',
                  'label' => 'LBL_RELATEDMODULE',
               ),
            ),
            1 =>
            array(
               array(
                  'name' => 'date_modified',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                  'label' => 'LBL_DATE_MODIFIED',
               ),
            ),
            2 =>
            array(
               0 =>
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
