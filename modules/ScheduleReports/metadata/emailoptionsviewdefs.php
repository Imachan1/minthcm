<?php

$module_name = 'ScheduleReports';
$viewdefs [$module_name]['emailoptionsView'] = array(
   'templateMeta' =>
   array(
      'form' =>
      array(
         'headerTpl' => 'include/EditView/header.tpl',
         'footerTpl' => 'include/EditView/footer.tpl',
         'buttons' =>
         array(
            array(
               'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" onclick="disableOnUnloadEditView(this.form);this.form.action.value=\'saveEmailOptions\'; this.form.module.value=\'ScheduleReports\';" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
            ),
            array(
               'customCode' => '<input title="' . $GLOBALS['app_strings']['LBL_CANCEL_BUTTON_LABEL'] . ' [Alt+X]" accessKey="X" onclick="this.form.action.value=\'index\'; this.form.module.value=\'ScheduleReports\';" type="submit" name="button" value="' . $GLOBALS['app_strings']['LBL_CANCEL_BUTTON_LABEL'] . '">',
            ),
         ),
      ),
      'maxColumns' => '1',
      'widths' =>
      array(
         array(
            'label' => '10',
            'field' => '90',
         ),
      ),
      'includes' => array(
         array(
            'file' => 'modules/ScheduleReports/js/email_options.js'
         ),
      ),
   ),
   'panels' => array(
      'default' => array(
         array(
            array(
               'label' => "LBL_DEFAULT_TEMPLATE",
               'customCode' => ' <slot>
									        		<select id="email_template_id" name="email_template_id" {$IE_DISABLED}>{$TMPL_DRPDWN_GENERATE}</select>
													<input type="button" class="button" onclick="javascript:window.email_options.open_email_template_form(\'email_template_id\')" value="{$MOD.LBL_CREATE_TEMPLATE}" {$IE_DISABLED}>
													<input type="button" value="{$MOD.LBL_EDIT_TEMPLATE}" class="button" onclick="javascript:window.email_options.edit_email_template_form(\'email_template_id\')" name="edit_email_template_id" id="edit_email_template_id" style="{$EDIT_TEMPLATE}">
												</slot>',
            ),
         ),
      ),
   ),
);


