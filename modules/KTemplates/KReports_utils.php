<?php

class KReports_utils {

   function get_variable($kreport_id) {
      require_once('modules/KReports/KReport.php');
      $thisReport = new KReport();
      if ( $kreport_id != null ) {
         $thisReport->retrieve($kreport_id);
      } else {
         return false;
         $GLOBALS['log']->debug = "kreport id is empty";
      }

      global $current_user;

      $results = $thisReport->getSelectionResults(array( 'toCSV' => true ), isset($_REQUEST ['snapshotid']) ? $_REQUEST ['snapshotid'] : '0');

      $arrayList = json_decode_kinamu(html_entity_decode($thisReport->listfields, ENT_QUOTES, 'UTF-8'));
      $fieldArray = [];
      $fieldIdArray = array();
      foreach ( $arrayList as $thisList ) {
         if ( $thisList ['display'] == 'yes' ) {
            $displaypath = explode("::", $thisList['path']);
            $patch_array = array();
            $name = '';
            for ( $i = 1; $i < count($displaypath); $i++ ) {
               if ( "link" == substr($displaypath[$i], 0, strpos($displaypath[$i], ":")) ) {
                  $patch_array[] = substr($displaypath[$i], strRpos($displaypath[$i], ":") + 1);
               }
            }
            foreach ( $patch_array as $patch ) {
               $name .= $patch . '__';
            }
            $name .= substr($thisList['path'], strpos($thisList['path'], "field:") + 6);
            $fieldArray [] = array(
               'label' => $thisList ['name'],
               'width' => (isset($thisList ['width']) && $thisList ['width'] != '' && $thisList ['width'] != '0') ? $thisList ['width'] : '100',
               'display' => $thisList ['display'],
               'name' => $name
            );
            $fieldIdArray [] = $thisList ['fieldid'];
         }
      }
      return $fieldArray;
   }

}
