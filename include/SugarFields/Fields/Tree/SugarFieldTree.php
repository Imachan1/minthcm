<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

global $sugar_config;

$version = $sugar_config['sugar_version'];
if ( version_compare($version, '7.0.0.0') > 0 ) {
   require_once('vendor/ytree/Tree.php');
   require_once('vendor/ytree/Node.php');
} else {
   require_once('include/ytree/Tree.php');
   require_once('include/ytree/Node.php');
}

require_once('modules/ev_Templates/TreeData.php');

class SugarFieldTree extends SugarFieldBase {

   function setup($parentFieldArray, $vardef, $displayParams, $tabindex, $twopass = true) {
      parent::setup($parentFieldArray, $vardef, $displayParams, $tabindex, $twopass);
   }

   function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
      $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
      return $this->fetch($this->findTemplate('EditView'));
   }

}
