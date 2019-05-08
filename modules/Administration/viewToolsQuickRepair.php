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
   require_once ("modules/Administration/QuickRepairAndRebuild.php");

   $autoexecute = true;
   $show_output = false;

   $repair = new RepairAndClear();
   $repair->repairAndClearAll(array(
      'clearAll'
           ), array(
      translate('LBL_ALL_MODULES')
           ), $autoexecute, $show_output);
} catch ( Exception $e ) {
   echo $e->getMessage();
}