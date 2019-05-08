<?php

echo "VTOOLS2 REBUILD BEGIN.\n";
try {
   include ('include/MVC/preDispatch.php');
   $startTime = microtime(true);
   require_once('include/entryPoint.php');
   $sapi_type = php_sapi_name();
   if ( substr($sapi_type, 0, 3) != 'cli' ) {
      sugar_die("rebuild_vtools.php is CLI only.");
   }
   global $current_user;
   $current_user = new User();
   $current_user->getSystemUser();
   ob_start();
   require_once('include/MVC/SugarApplication.php');
   $app = new SugarApplication();
   $app->startSession();
   require_once 'modules/Administration/viewToolsQuickRepair.php';
   SugarRelationshipFactory::rebuildCache();
   require_once 'modules/Administration/viewToolsRebuild.php';
   require_once 'modules/Administration/viewToolsJSGroupings.php';
   $rebuild = fopen('include/ViewTools/Expressions/rebuild.lock', 'w');
   fwrite($rebuild, '1');
   fclose($rebuild);
   fsmodifyr("cache");
   echo "REBUILD FINISHED SUCCESSFULLY.\n";
} catch ( Exception $e ) {
   echo "SOME ERROR OCCURRED. SEE DETAILS BELOW:\n" . $e->getMessage();
}

function fsmodify($obj) {
   chmod($obj, is_dir($obj) ? 0755 : 0644);
   chown($obj, get_current_user());
   chgrp($obj, get_current_user());
}

function fsmodifyr($dir) {
   $objs = glob($dir . "/*");
   if ( $objs ) {
      foreach ( $objs as $obj ) {
         fsmodify($obj);
         if ( is_dir($obj) ) {
            fsmodifyr($obj);
         }
      }
   }
   return fsmodify($dir);
}
