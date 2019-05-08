<?php

$module_name = 'KTemplates';
$viewdefs [$module_name] = array(
   'WizardView' =>
   array(
      'templateMeta' =>
      array(
         'form' =>
         array(
            'headerTpl' => 'include/EditView/header.tpl',
            'footerTpl' => 'include/EditView/footer.tpl',
            'buttons' =>
            array(
               0 =>
               array(
                  'customCode' => '<input title="{$MOD.LBL_BUTTON_NEXT}" accessKey="N" onclick="this.form.action.value=\'EditView\'; this.form.module.value=\'KTemplates\'; return check_form(\'WizardView\');" type="submit" name="button" value="{$MOD.LBL_BUTTON_NEXT}">',
               ),
               1 =>
               'CANCEL',
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
         'useTabs' => false,
      ),
      'panels' =>
      array(
         'default' =>
         array(
            0 =>
            array(
               0 =>
               array(
                  'name' => 'relatedmodule',
                  'studio' => 'visible',
                  'label' => 'LBL_RELATEDMODULE',
               ),
            ),
         ),
      ),
   ),
);
?>
