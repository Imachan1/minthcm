<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

$pluginmetadata = array(
   'id' => 'kpdfexport',
   'type' => 'integration',
   'category' => 'export',
   'displayname' => 'LBL_PDF_EXPORT',
   'integration' => array(
      'include' => 'kpdfexport.php',
      'class' => 'kpdfexport'
   ),
   'includes' => array(
      'view' => 'kpdfexport.js',
      'edit' => 'kpdfexportpanel.js',
      'editPanel' => 'SpiceCRM.KReporter.Designer.integrationplugins.kpdfexportpanel',
      'viewItem' => 'SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.menuitem'
   )
);
