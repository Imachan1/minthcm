<?php

$module_name = 'News';
$viewdefs [$module_name] = array(
   'DetailView' =>
   array(
      'templateMeta' =>
      array(
         'form' =>
         array(
            'buttons' =>
            array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
               'FIND_DUPLICATES',
               array(
                  'customCode' => '{include file="modules/News/tpls/PublishButton.tpl"}'
                  . '{include file="modules/News/tpls/ArchiveButton.tpl"}',
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
         'includes' =>
         array(
            array(
               'file' => 'modules/News/js/view.detail.js',
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
            'LBL_PANEL_ASSIGNMENT' =>
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
               'news_status',
            ),
            array(
               'news_type',
               '',
            ),
            array(
               array(
                  'name' => 'content_of_announcement',
                  'label' => 'LBL_CONTENT_OF_ANNOUNCEMENT',
                  'customCode' => '{$fields.content_of_announcement.value}',
               ),
            ),
            array(
               'description',
            ),
         ),
         'LBL_PANEL_ASSIGNMENT' =>
         array(
            array(
               'assigned_user_name',
               '',
            ),
            array(
               array(
                  'name' => 'date_entered',
                  'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}'
               ),
               array(
                  'name' => 'date_modified',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}'
               )
            )
         ),
      ),
   ),
);
;
?>
