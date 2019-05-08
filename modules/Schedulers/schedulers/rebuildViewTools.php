<?php

error_reporting(E_ERROR);
require_once 'data/SugarBean.php';
//global $job_strings; t
array_push($job_strings, 'rebuildViewTools');

function rebuildViewTools() {
   try {
      require_once 'modules/Administration/viewToolsJSGroupings.php';
      $rebuild = fopen('include/ViewTools/Expressions/rebuild.lock', 'w');
      fwrite($rebuild, '1');
      fclose($rebuild);
   } catch ( Exception $e ) {
      echo $e->getMessage();
   }
   return true;
}
