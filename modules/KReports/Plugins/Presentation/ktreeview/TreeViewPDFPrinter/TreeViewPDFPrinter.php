<?php

class TreeViewPDFPrinter {

   public $report_bean;
   public $tpls;
   protected $sugar_smarty;
   protected $columns;
   protected $records;
   protected $theme;

   const MAIN_PATH = 'modules/KReports/Plugins/Presentation/ktreeview/TreeViewPDFPrinter/';
   const TPLS_PATH = 'modules/KReports/Plugins/Presentation/ktreeview/TreeViewPDFPrinter/tpls/';
   const ERROR_TPL = 'modules/KReports/Plugins/Presentation/ktreeview/TreeViewPDFPrinter/tpls/error.tpl';
   const ERROR_HTML = '<h2>ERROR, check logs</h2>';

   public function __construct($theme = 'Default') {
      if ( $theme == 'Default' ) {
         $this->theme = 'lightblue1';
      } else {
         $this->theme = $theme;
      }
      $this->report_bean = null;
   }

   public function init($report_bean) {
      $this->report_bean = $report_bean;
      $this->sugar_smarty = new Sugar_Smarty();

      $tree_view_generator = new KTreeViewGenerator($report_bean);
      $this->columns = $tree_view_generator->generateColumns();
      $this->records = $tree_view_generator->generateChildrenRecordsForNode('root');
      $this->tpls = include(self::MAIN_PATH . 'tpls.php');

      return $this;
   }

   public function generateHTML() {
      if ( !isset($this->report_bean) ) {
         LoggerManager::getLogger()->fatal(basename(__FILE__) . ':' . __LINE__ . ':' . __FUNCTION__ . ':' . ' TreeViewPDFPrinter not initialized');
         return self::ERROR_HTML;
      }

      if ( count($this->records) == 0 ) {
         $this->sugar_smarty->assign('LBL_CHART_NODATA', $GLOBALS['mod_strings']['LBL_CHART_NODATA']);
         return $this->sugar_smarty->fetch($this->getTPLPath('NO_DATA'));
      } else {
         $this->sugar_smarty->assign('header_row', $this->generateHeaderRow());

         $records_rows = '';
         foreach ( $this->records as $record ) {
            $records_rows .= $this->generateRecordRows($record);
         }
         $this->sugar_smarty->assign('records_rows', $records_rows);
      }
      return $this->sugar_smarty->fetch($this->getTPLPath('CSS')) . $this->sugar_smarty->fetch($this->getTPLPath('TABLE'));
   }

   protected function generateHeaderRow() {
      $html = '';
      foreach ( $this->columns as $column ) {
         $this->sugar_smarty->assign('column_data', $column);
         $html .= $this->sugar_smarty->fetch($this->getTPLPath('HEADER_CELL'));
      }
      $this->sugar_smarty->assign('header_cells', $html);
      $this->sugar_smarty->assign('is_first', true);
      return $this->sugar_smarty->fetch($this->getTPLPath('HEADER_ROW'));
   }

   protected function generateRecordRows($record) {
      $html = '';
      $record_cells = '';
      $this->sugar_smarty->assign('record_level', count($record['nodes_history']) - 1);
      $this->sugar_smarty->assign('column_count', count($this->columns));
      if ( !empty($record['children']) ) {
         $this->sugar_smarty->assign('has_children', true);
      } else {
         $this->sugar_smarty->assign('has_children', false);
      }

      foreach ( $this->columns as $index => $column ) {
         $data_index = $column['dataIndex'];
         if ( empty($record[$data_index]) ) {
            $record_cells = '';
            break;
         }
         $this->sugar_smarty->assign('cell_text', $record[$data_index]);
         $this->sugar_smarty->assign('column_data', $column);
         $record_cells .= $this->sugar_smarty->fetch($this->getTPLPath('RECORD_CELL'));
      }

      if ( !empty($record_cells) ) {
         $this->sugar_smarty->assign('record_cells', $record_cells);
         $html .= $this->sugar_smarty->fetch($this->getTPLPath('RECORD_ROW'));
         $this->sugar_smarty->assign('is_first', false);
      }

      if ( !$record['leaf'] ) {
         foreach ( $record['children'] as $index => $child_record ) {
            $html .= $this->generateRecordRows($child_record);
         }
      }
      return $html;
   }

   protected function getTPLPath($template) {
      $path = self::TPLS_PATH . $this->theme . '/' . $this->tpls[$template];
      if ( !file_exists($path) ) {
         LoggerManager::getLogger()->error(basename(__FILE__) . ':' . __LINE__ . ':' . __FUNCTION__ . ':' . ' TreeViewPDFPrinter theme template not found: ' . $path);
         return self::ERROR_TPL;
      }
      return $path;
   }

}
