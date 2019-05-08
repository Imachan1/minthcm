<?php

require_once 'modules/Recruitments/Recruitments_sugar.php';
require_once 'modules/Recruitments/SugarFeeds/RecruitmentsFeed.php';

class Recruitments extends Recruitments_sugar {

   public $counted = false;

   public function save($check_notify = false) {

      if ( $this->recruitment_type == 'continuous' ) {
         $this->vacancy = '';
      }

      if ( !$this->counted ) {
         $this->load_relationship('candidatures_end');
         $employees_number = 0;
         $candidatures = $this->candidatures_end->getBeans();
         foreach ( $candidatures as $candidature ) {
            if ( $candidature->status == 'Hired' ) {
               $employees_number++;
            }
         }
         $this->employees_number = $employees_number;
      }

      $old_bean = $this->fetched_row;
      $beans = array();

      $this->name = $this->position_name . ' ' . $this->start_date;
      $curr_name = $this->name;
      $old_bean_name = $old_bean['name'];

      $this->calculateCurrencies();

      parent::save($check_notify);

      //name is a calculated field so it could be change due to change of the position name after save
      if ( ($curr_name != $this->name || $old_bean_name != $this->name) && $this->load_relationship('candidatures') ) {
         $beans = $this->candidatures->getBeans();
      }

      foreach ( $beans as $b ) {
         $b->generateName();
         $b->save();
      }

      $this->pushFeed();
   }

   protected function pushFeed() {
      $recruitments_feed = new RecruitmentsFeed();
      $recruitments_feed->pushFeed($this, '', array());
   }

   protected function calculateCurrencies() {
      $currency = new Currency();
      $currency->retrieve($this->currency_id);
      if ( isset($this->salary_from) ) {
         $this->salary_from = !number_empty($this->salary_from) ? $this->salary_from : 0.0;
         $this->salary_from_usdollar = $currency->convertToDollar(unformat_number($this->salary_from));
      }
      if ( isset($this->salary_to) ) {
         $this->salary_to = !number_empty($this->salary_to) ? $this->salary_to : 0.0;
         $this->salary_to_usdollar = $currency->convertToDollar(unformat_number($this->salary_to));
      }
   }

}
