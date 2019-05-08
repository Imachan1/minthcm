<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

if ( !isset($_REQUEST['template']) || $_REQUEST['template'] == '' ) {
   echo "No templated id provided!";
   exit;
}
if ( !isset($_REQUEST['module_name']) || $_REQUEST['module_name'] == '' ) {
   echo "No module name provided!";
   exit;
}

require_once('include/utils/db_utils.php');
require_once('include/tcpdf/gen_preview.php');

global $beanList, $beanFiles, $focus, $pdftemplate, $app_strings, $timedate, $app_list_strings, $sugar_config;
require_once 'modules/KReports/Plugins/Integration/kpdfexport/templates_engine.php';
$file = file_get_contents("modules/KReports/Plugins/Integration/kpdfexport/templates/{$_REQUEST['template']}.html");
$tempEngine = new TemplateEngine($file);
$focus = new KReport();
$focus = $focus->retrieve($_REQUEST['module_name']);
if ( $focus ) {
   $field_array = $tempEngine->getFieldArray($focus);
}

$pdf = $tempEngine->getPDFHeader();
$html = $tempEngine->getPreview($field_array['fieldArray']);
$pdf->AddPage('P', 'A4');
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output();

function check_record($id, &$f) {

   $result = $f->retrieve($id);
   if ( $result == null || !$f->ACLAccess('', $f->isOwner($current_user->id)) ) {
      sugar_die($app_strings['ERROR_NO_RECORD']);
   }
}

?>