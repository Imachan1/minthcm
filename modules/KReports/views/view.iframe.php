<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class KReportsViewIframe extends ViewDetail {

   public function __construct() {
      $this->options = array(
         'show_header' => false,
         'show_title' => false,
         'show_subpanels' => false,
         'show_search' => false,
         'show_footer' => false,
         'show_javascript' => true,
         'view_print' => false
      );

      parent::__construct();
   }

   public function display() {
      $this->ss->assign("report_id", $_REQUEST['record']);
      $where_conditions = json_decode(html_entity_decode($this->bean->whereconditions), true);
      $this->ss->assign("use_autofilter", false);

      if ( isset($_REQUEST['use_autofilter']) && $_REQUEST['use_autofilter'] == '1' ) {
         $filter_module = $_REQUEST['filter_module'];
         foreach ( $where_conditions as $where_condition ) {
            $paths = explode('::', $where_condition['path']);
            foreach ( $paths as $path ) {
               $track = explode(':', $path);
               switch ( $track[0] ) {
                  case 'root':
                     $module = $track[1];
                     break;
                  case 'link':
                     $module = $track[1];
                     $link = $track[2];
                     break;
                  case 'field':
                     $field = $track[1];
                     break;
               }
            }
            if ( isset($link) ) {
               $bean = BeanFactory::getBean($module);
               if ( $bean && $bean->field_defs[$link] ) {
                  $module = $bean->field_defs[$link]['module'];
               }
            }
            if ( $field === 'id' && $module == $filter_module ) {
               $this->ss->assign("use_autofilter", true);
               $this->ss->assign('autofilter_fieldid', $where_condition['fieldid']);
               $this->ss->assign('autofilter_value', $_REQUEST['filter_record']);
               break;
            }
         }
      }

      $show_options = '';
      if ( $_REQUEST['show_data'] != '1' ) {
         $show_options .= 'kreporterView.getComponent(3).destroy();';
      }
      if ( $_REQUEST['show_chart'] != '1' ) {
         $show_options .= ' kreporterView.getComponent(2).destroy();';
      }
      if ( $_REQUEST['show_filters'] != '1' ) {
         $show_options .= ' kreporterView.getComponent(1).destroy();';
      }

      $this->ss->assign("show_options", $show_options);
      $this->ss->display("modules/KReports/tpls/view.iframe.tpl");
   }

}
