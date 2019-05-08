<?php

/**
 * This file is used to rebuild js View Tools files.
 * Each change in include/ViewTools/Expressions shuld be ended by execute This file.
 * 
 * Warning!
 * Do not edit/format/delete this file, otherwise ViewTools will not 
 * work properly.
 */
if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
try {
   if ( $GLOBALS['sugar_config']['developerMode'] != true ) {
      $_REQUEST['js_admin_repair'] = 'concat';
      $_REQUEST['root_directory'] = getcwd();
      include_once 'modules/Administration/callJSRepair.php';
   }
} catch ( Exception $e ) {
   echo $e->getMessage();
}