<?php

global $mod_strings, $app_strings, $sugar_config;
global $current_user;

$module_menu = array(
   array(
      "index.php?module=SpentTime&action=EditView",
      $mod_strings['LBL_NEW_FORM_TITLE'],
      'Create',
      'SpentTime'
   ),
   array(
      "index.php?module=SpentTime&action=index",
      $mod_strings['LNK_LIST'],
      'List',
      'SpentTime'
   ),
);
