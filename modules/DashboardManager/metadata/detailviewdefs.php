<?php

$module_name = 'DashboardManager';
$viewdefs [$module_name] = array(
   'DetailView' =>
   array(
      'templateMeta' =>
      array(
         'includes' => array(
            array(
               'file' => 'modules/DashboardManager/js/view.detail.js',
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
               'EDIT',
               'DELETE',
               array(
                  'customCode' => true,
                  'sugar_html' =>
                  array(
                     'type' => 'button',
                     'value' => '{$MOD.LBL_LOAD_DASHBOARDS_BUTTON}',
                     'htmlOptions' =>
                     array(
                        'title' => '{$MOD.LBL_LOAD_DASHBOARDS_BUTTON}',
                        'class' => 'button',
                        'onclick' => 'loadUserDashboardConfirm()',
                        'name' => 'load_dashboards_button',
                        'id' => 'load_dashboards_button',
                     ),
                     'template' => '{if $bean->aclAccess("edit")}[CONTENT]{/if}',
                  ),
               ),
               array(
                  'customCode' => true,
                  'sugar_html' =>
                  array(
                     'type' => 'submit',
                     'value' => '{$MOD.LBL_DEPLOY_BUTTON}',
                     'htmlOptions' =>
                     array(
                        'title' => '{$MOD.LBL_DEPLOY_BUTTON}',
                        'class' => 'button',
                        'onclick' => 'this.form.isSaveFromDetailView.value=true; this.form.customAction.value=\'deployDashboards\'; this.form.action.value=\'Save\';this.form.return_module.value=\'DashboardManager\';this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'',
                        'name' => 'deploy_action',
                        'id' => 'deploy_action',
                     ),
                     'template' => '{if $fields.is_loaded.value && $bean->aclAccess("edit")}[CONTENT]{/if}',
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
               'is_loaded',
            ),
            array(
               'assigned_user_name',
               ''
            ),
            array(
               'description',
            ),
            array(
               array(
                  'name' => 'date_entered',
                  'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                  'label' => 'LBL_DATE_ENTERED',
               ),
               array(
                  'name' => 'date_modified',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                  'label' => 'LBL_DATE_MODIFIED',
               ),
            ),
         ),
      ),
   ),
);
