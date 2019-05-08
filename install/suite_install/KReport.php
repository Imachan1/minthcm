<?php

function install_kreport() {
   require_once('modules/Administration/Administration.php');
   global $sugar_config;

   if ( !isset($sugar_config['addAjaxBannedModules']) ) {
      $sugar_config['addAjaxBannedModules'] = array();
   }
   $sugar_config['addAjaxBannedModules'][] = 'KReports';

   ksort($sugar_config);
   write_array_to_file('sugar_config', $sugar_config, 'config.php');
}
