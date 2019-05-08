<?php

global $sugar_config;

$version = $sugar_config['sugar_version'];
if ( version_compare($version, '7.0.0.0') > 0 ) {
   require_once('vendor/ytree/Node.php');
} else {
   require_once('include/ytree/Node.php');
}


function get_node_data($params, $get_array = false) {
   return getNodeData($params, $get_array);
}

function getNodeData($params, $get_array = false) {
   $ret = array();
   $depth = $params['TREE']['depth'];
   $new_relation = '';
   $i = 0;
   while ( $i++ < $depth ) {
      $new_relation .= $params['NODES'][$i]['relationship'] . '__';
   }
   $nodes = getModuleElements($params['NODES'][$depth]['related_module'], $new_relation);

   foreach ( $nodes as $node ) {
      $ret['nodes'][] = $node->get_definition();
   }
   $json = new JSON(JSON_LOOSE_TYPE);
   $str = $json->encode($ret);
   return $str;
}

function get_nodes() {
   return getNodes();
}

function getNodes() {
   $module = getModuleRelated();
   if ( !$module ) {
      return array();
   }
   $root = generateNode($module, translate('LBL_MODULE_NAME', $module), true, false);
   $nodes = getModuleElements($module);
   foreach ( $nodes as $node ) {
      $root->add_node($node);
   }
   return array( $root );
}

function get_module_related() {
   return getModuleRelated();
}

function getModuleRelated() {
   global $beanList, $moduleList, $beanFiles;
   if ( in_array($_POST['relatedmodule'], $moduleList) ) {
      return $_POST['relatedmodule'];
   } else if ( isset($_REQUEST['record']) && isset($_REQUEST['module']) ) {
      $bean = $beanList[clean_string($_REQUEST['module'])];
      require_once($beanFiles[$bean]);
      $focus = new $bean;
      $result = $focus->retrieve($_REQUEST['record']);
      if ( in_array($result->relatedmodule, $moduleList) ) {
         return $result->relatedmodule;
      } else {
         return false;
      }
   } else {
      return false;
   }
}

function generateNode($module, $label, $expanded, $dynamic_load, $property = array(), $show_expanded = false) {
   $node = new Node($module, $label, $show_expanded);
   $node->expanded = $expanded;
   $node->dynamic_load = $dynamic_load;
   foreach ( $property as $key => $value ) {
      if ( is_array($value) ) {
         $node->set_property($key, $value[0], $value[1]);
      } else {
         $node->set_property($key, $value);
      }
   }
   return $node;
}

/*
 * Generates nodes for given module including extendable nodes for its related modules
 */

function getModuleElements($module, $relationship = "") {
   $nodes = array();
   list($focus, $field_defs, $link_defs, $module_dir) = getFocusAndFieldsDefsAndLinkDefs($module);
   $key = '$';
   $rel = substr($relationship, 0, -2);
   if ( !empty($relationship) ) {
      // if related module add additional tags for repeating beans and such
      $nodes[] = generateNode("RELBLOCK", "[ " . translate("LBL_INSERT_BLOCK", "PDFTemplates") . " ]", true, false, array( "href" => "javascript:insertRelBlock('" . $rel . "');" ));
      $nodes[] = generateNode("COUNTER", "[ " . translate("LBL_REP_COUNTER", "PDFTemplates") . " ]", true, false, array( "href" => "javascript:insertToken('" . $key . "COUNT_" . $rel . "');" ));
   }
   $nodes[] = generateNode("CurrencyISO", "[ " . translate("LBL_CURRENCY_ISO", "PDFTemplates") . " ]", true, false, array( "href" => "javascript:insertToken('" . $key . "CURRENCY_ISO_" . $rel . "');" ));
   $nodes[] = generateNode("RELBLOCK", "[ " . translate("LBL_CURRENCY_SYMBOL", "PDFTemplates") . " ]", true, false, array( "href" => "javascript:insertToken('" . $key . "CURRENCY_SYMBOL_" . $rel . "');" ));
   // get node representation for each bean field defined in vardefs that wasn't specificly pointed as not for pdf  	
   foreach ( $field_defs as $field ) {
      if ( checkIfFieldRaportableAndForPdf($field) ) {
         $nodes = getNodesForFieldRaportableAndForPdf($field, $module_dir, $key, $relationship, $nodes);
      }
   }
   // add nodes for related modules
   foreach ( $link_defs as $field ) {
      if ( checkIfFieldRaportableAndForPdf($field) ) {
         $nodes = getNodesForLinkField($field, $module_dir, $focus, $nodes);
      }
   }
   return $nodes;
}

function getFocusAndFieldsDefsAndLinkDefs($module) {
   global $beanList, $beanFiles;
   $bean = $beanList[$module];
   require_once($beanFiles[$bean]);
   $focus = new $bean();
   $focus->load_relationships();
   $field_defs = $focus->getFieldDefinitions();
   $link_defs = $focus->get_linked_fields();
   $module_dir = $focus->module_dir;
   return array( $focus, $field_defs, $link_defs, $module_dir );
}

function checkIfFieldRaportableAndForPdf($field) {
   if ( !isset($field['reportable']) || $field['reportable'] == true ) {
      if ( !isset($field['for_pdf']) || $field['for_pdf'] == true ) {
         return true;
      }
   }
   return false;
}

function getNodesForLinkField($field, $module_dir, $focus, $nodes) {
   $rel_module = $field['module'];
   
   if ( !empty($rel_module) ) {//strange, non-existent sugar relations
      if ( empty($field['vname']) ) {
         $field['vname'] = 'LBL_NO_LABEL';
      }
      $return = generateNode($field['name'], translate($field['vname'], $module_dir) . " (" . $rel_module . ")", false, true, array( "related_module" => array( $rel_module, TRUE ), "relationship" => array( $field["name"], TRUE ) ));
   }
   if ( $return ) {
      $nodes[] = $return;
   }
   return $nodes;
}

function getNodesForNotMultienumField($field, $module_dir, $key, $relationship) {
   if ( $field['type'] == 'id' && $field['reportable'] == false ) {
      
   } else {
      if ( empty($field['vname']) ) {
         $field['vname'] = 'LBL_NO_LABEL';
      }
      return generateNode($field['name'], translate($field['vname'], $module_dir), true, false, array( "href" => "javascript:insertToken('" . $key . $relationship . $field['name'] . "');" ));
   }
}

function getNodesForMultienumField($field, $module_dir, $relationship) {
   if ( empty($field['vname']) ) {
      $field['vname'] = 'LBL_NO_LABEL';
   }
   return generateNode($field['name'], translate($field['vname'], $module_dir), true, false, array( "href" => "javascript:insertMultienum('" . $relationship . $field['name'] . "');" ));
}

function getNodesForFieldRaportableAndForPdf($field, $module_dir, $key, $relationship, $nodes) {
   if ( $field['type'] != 'link' && $field['type'] != 'multienum' ) {
      $return = getNodesForNotMultienumField($field, $module_dir, $key, $relationship);
   } else if ( $field['type'] == 'multienum' ) {
      $return = getNodesForMultienumField($field, $module_dir, $relationship);
   }
   if ( $return ) {
      $nodes[] = $return;
   }
   return $nodes;
}

?>
