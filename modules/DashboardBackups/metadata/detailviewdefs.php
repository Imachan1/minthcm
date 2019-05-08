<?php

$module_name = 'DashboardBackups';
$viewdefs [$module_name] = array(
   'DetailView' =>
   array(
      'templateMeta' =>
      array(
         'includes' =>
         array(
            array(
               'file' => 'modules/DashboardManager/javascript/view.detail.js',
            ),
         ),
         'form' =>
         array(
            'hidden' =>
            array(
               '<input type="hidden" name="customAction">',
               '<input type="hidden" name="isSaveFromDetailView">',
            ),
            'buttons' =>
            array(
               array(
                  'customCode' => true,
                  'sugar_html' =>
                  array(
                     'type' => 'submit',
                     'value' => '{$MOD.LBL_RESTORE_BUTTON}',
                     'htmlOptions' =>
                     array(
                        'title' => '{$MOD.LBL_RESTORE_BUTTON}',
                        'class' => 'button',
                        'onclick' => 'this.form.isSaveFromDetailView.value=true; this.form.customAction.value=\'restoreBackup\'; this.form.action.value=\'Save\';this.form.return_module.value=\'DashboardBackups\';this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'',
                        'name' => 'restore_button',
                        'id' => 'restore_button',
                     ),
                     'template' => '{if $bean->aclAccess("edit")}[CONTENT]{/if}',
                  ),
               ),
            ),
         ),
         'maxColumns' => '2',
         'widths' =>
         array(
            array(
               'label' => '10',
               'field' => '30',
            ),
            array(
               'label' => '10',
               'field' => '30',
            ),
         ),
         'useTabs' => true,
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'name',
               array(
                  'name' => 'assigned_user_name',
                  'label' => 'LBL_ASSIGNED_TO_NAME',
               ),
            ),
            array(
               'dashboardmanager_name',
               'dashboardhistory_name',
            ),
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
      ),
   ),
);
