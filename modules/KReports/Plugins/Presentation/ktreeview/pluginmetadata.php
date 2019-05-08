<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

$pluginmetadata = array(
   'id' => 'ktreeview',
   'displayname' => 'LBL_TREEVIEW',
   'type' => 'presentation',
   'phpinclude' => 'ktreeviewinclude.php',
   'pluginpanel' => 'SpiceCRM.KReporter.Designer.presentationplugins.ktreeviewpanel',
   'viewpanel' => 'SpiceCRM.KReporter.Viewer.plugins.KTreeViewPanel',
   'includes' => array(
      'edit' => 'ktreeviewpanel.js',
      'view' => 'ktreeview.js'
   )
);
