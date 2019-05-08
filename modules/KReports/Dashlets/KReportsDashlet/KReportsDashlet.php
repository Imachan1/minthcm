<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

require_once('include/Dashlets/Dashlet.php');
require_once('modules/KReports/KReport.php');

class KReportsDashlet extends Dashlet {

   const MIN_DASHLET_HEIGHT = 150;
   const MAX_DASHLET_HEIGHT = 500;

   public $report_id;
   public $show_chart = true;
   public $show_data = true;
   public $show_filters = true;
   public $height = self::MAX_DASHLET_HEIGHT;

   public function __construct($id, $def = null) {
      $this->loadLanguage('KReportsDashlet', 'modules/KReports/Dashlets/');

      parent::__construct($id);
      $this->title = (empty($def['title'])) ? $this->dashletStrings['LBL_DEFAULT_TITLE'] : $def['title'];
      $this->report_id = $def['report_id'];

      $this->show_chart = (isset($def['show_chart'])) ? $def['show_chart'] : true;
      $this->show_data = (isset($def['show_data'])) ? $def['show_data'] : true;
      $this->show_filters = (isset($def['show_filters'])) ? $def['show_filters'] : true;
      $this->height = (isset($def['height'])) ? $def['height'] : $this->height;
      $this->autoRefresh = (isset($def['autoRefresh'])) ? $def['autoRefresh'] : '0';

      $this->seedBean = new KReport();
      $this->isConfigurable = true;
      $this->hasScript = false;
   }

   public function display() {
      $ss = new Sugar_Smarty();

      $height = intval($this->height);
      if ( $height < self::MIN_DASHLET_HEIGHT ) {
         $height = self::MIN_DASHLET_HEIGHT;
      } else if ( $height > self::MAX_DASHLET_HEIGHT ) {
         $height = self::MAX_DASHLET_HEIGHT;
      }

      $ss->assign('id', $this->id);
      $ss->assign('title', $this->title);
      $ss->assign('report_id', $this->report_id);
      $ss->assign('show_chart', $this->show_chart);
      $ss->assign('show_data', $this->show_data);
      $ss->assign('show_filters', $this->show_filters);
      $ss->assign('height', $height);
      $ss->assign('dashletStrings', $this->dashletStrings);

      return $ss->fetch('modules/KReports/Dashlets/KReportsDashlet/KReportsDashlet.tpl');
   }

   public function displayOptions() {
      global $app_strings;

      $ss = new Sugar_Smarty();

      $ss->assign('report_id', '');
      $ss->assign('report_name', '');
      if ( !empty($this->report_id) ) {
         $report = BeanFactory::getBean('KReports', $this->report_id);

         if ( $report->id === $this->report_id ) {
            $ss->assign('report_id', $report->id);
            $ss->assign('report_name', $report->name);
         }
      }

      $ss->assign('id', $this->id);
      $ss->assign('title', $this->title);
      $ss->assign('show_chart', $this->show_chart);
      $ss->assign('show_data', $this->show_data);
      $ss->assign('show_filters', $this->show_filters);
      $ss->assign('height', $this->height);

      if ( $this->isAutoRefreshable() ) {
         $ss->assign('isRefreshable', true);
         $ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
         $ss->assign('autoRefreshSelect', $this->autoRefresh);
      }

      $this->dashletStrings['LBL_DASHLET_HEIGHT_HELP'] = sprintf($this->dashletStrings['LBL_DASHLET_HEIGHT_HELP'], self::MIN_DASHLET_HEIGHT, self::MAX_DASHLET_HEIGHT);
      $ss->assign('dashletStrings', $this->dashletStrings);
      $ss->assign('APP', $app_strings);

      return $ss->fetch('modules/KReports/Dashlets/KReportsDashlet/KReportsDashletConfigure.tpl');
   }

   public function saveOptions($req) {
      $options = array();

      $options['title'] = $req['title'];
      $options['report_id'] = $req['report_id'];
      $options['show_chart'] = (isset($req['show_chart'])) ? true : false;
      $options['show_data'] = (isset($req['show_data'])) ? true : false;
      $options['show_filters'] = (isset($req['show_filters'])) ? true : false;
      $options['height'] = $req['height'];
      $options['autoRefresh'] = $req['autoRefresh'];

      return $options;
   }

}
