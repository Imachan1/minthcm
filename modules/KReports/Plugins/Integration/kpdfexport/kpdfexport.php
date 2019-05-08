<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

require_once('modules/KReports/Plugins/prototypes/kreportintegrationplugin.php');

class kpdfexport extends kreportintegrationplugin {

   public function __construct() {
      $this->pluginName = 'PDF';
   }

}
