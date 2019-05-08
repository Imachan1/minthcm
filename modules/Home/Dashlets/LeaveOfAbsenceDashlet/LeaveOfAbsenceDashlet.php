<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once 'include/Dashlets/Dashlet.php';
require_once 'include/Sugar_Smarty.php';

class LeaveOfAbsenceDashlet extends Dashlet {

   protected $url = 'http://www.sugarcrm.com/crm/aggregator/rss/1';
   protected $height = '200'; // height of the pad
   protected $images_dir = 'modules/Home/Dashlets/LeaveOfAbsenceDashlet/images';

   /**
    * Constructor
    *
    * @global string current language
    * @param guid $id id for the current dashlet (assigned from Home module)
    * @param array $def options saved for this dashlet
    */
   public function __construct($id, $def) {
      $this->loadLanguage('LeaveOfAbsenceDashlet', 'modules/Home/Dashlets/'); // load the language strings here

      if ( !empty($def['height']) ) { // set a default height if none is set
         $this->height = $def['height'];
      }

      if ( !empty($def['url']) ) {
         $this->url = $def['url'];
      }

      if ( !empty($def['title']) ) {
         $this->title = $def['title'];
      } else {
         $this->title = $this->dashletStrings['LBL_TITLE'];
      }

      $this->autoRefresh = false;

      parent::__construct($id); // call parent constructor

      $this->isConfigurable = false; // dashlet is configurable
      $this->hasScript = true; // dashlet has javascript attached to it
   }

   /**
    * Displays the dashlet
    *
    * @return string html to display dashlet
    */
   public function display() {
      $ss = new Sugar_Smarty();
      $ss->assign('saving', $this->dashletStrings['LBL_SAVING']);
      $ss->assign('saved', $this->dashletStrings['LBL_SAVED']);
      $ss->assign('id', $this->id);
      $ss->assign('height', $this->height);
      $lang = strtolower(substr($GLOBALS['current_language'], 0, 2));
      $ss->assign('lang', $lang);
      $str = $ss->fetch('modules/Home/Dashlets/LeaveOfAbsenceDashlet/LeaveOfAbsenceDashlet.tpl');
      return parent::display($this->dashletStrings['LBL_DBLCLICK_HELP']) . $str;
   }

   public function displayScript() {
      
   }

   /**
    * called to filter out $_REQUEST object when the user submits the configure dropdown
    *
    * @param array $req $_REQUEST
    * @return array filtered options to save
    */
   public function saveOptions(
      array $req
   ) {
      $options = array();
      $options['title'] = $req['title'];
      $options['url'] = $req['url'];
      $options['height'] = $req['height'];
      $options['autoRefresh'] = 0;

      return $options;
   }

}
