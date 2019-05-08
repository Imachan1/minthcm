<?php

class TermsOfEmployment extends Basic {

   public $new_schema = true;
   public $module_dir = 'TermsOfEmployment';
   public $object_name = 'TermsOfEmployment';
   public $table_name = 'termsofemployment';
   public $importable = true;
   public $id;
   public $name;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;
   public $created_by;
   public $created_by_name;
   public $description;
   public $deleted;
   public $created_by_link;
   public $modified_user_link;
   public $assigned_user_id;
   public $assigned_user_name;
   public $assigned_user_link;
   public $SecurityGroups;
   public $term_starting_date;
   public $term_ending_date;
   public $date_of_signing;
   public $gross;
   public $currency_id;
   public $net;
   public $employer_cost;

   public function bean_implements($interface) {
      if ( $interface === 'ACL' ) {
         return true;
      } else {
         return false;
      }
   }

   public function save($check_notify = false) {
      $old_starting_date = $this->fetched_row['term_starting_date'];
      $old_ending_date = $this->fetched_row['term_ending_date'];
      $old_contract_id = $this->fetched_row['contract_id'];
      $this->convertCurrencyFields();
      parent::save($check_notify);

      if ( $this->term_starting_date != $old_starting_date || $this->term_ending_date != $old_ending_date || $this->contract_id != $old_contract_id ) {
         $contract = BeanFactory::getBean('Contracts', $this->contract_id);
         if ( $contract && $contract->id ) {
            $contract->updateDates();
         }
      }
      if ( $this->contract_id != $old_contract_id ) {
         $old_contract = BeanFactory::getBean('Contracts', $old_contract_id);
         if ( $old_contract && $old_contract->id ) {
            $old_contract->updateDates();
         }
      }
   }

   public function mark_deleted($id) {
      $contract_id = $this->contract_id;
      parent::mark_deleted($id);
      $contract = BeanFactory::getBean('Contracts', $contract_id);
      if ( $contract && $contract->id ) {
         $contract->updateDates();
      }
   }

   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {
      $view = strtolower($view);
      if ( in_array($view, array( 'delete' )) ) {
         global $db;
         $query = "SELECT t1.id, t2.id "
                 . "FROM termsofemployment t1 JOIN termsofemployment t2 ON t1.contract_id = t2.contract_id "
                 . "WHERE t1.deleted = 0 AND t2.deleted = 0 AND t1.contract_id = '{$this->contract_id}'"
                 . "AND t1.term_ending_date < '{$this->term_starting_date}' AND t2.term_starting_date > IF "
                 . "('{$this->term_ending_date}' != '', '{$this->term_ending_date}', '2099-12-31')";
         $result = $db->query($query);
         if ( $row = $db->fetchByAssoc($result) ) {
            return false;
         }
      }
      return parent::ACLAccess($view, $is_owner, $in_group);
   }

   protected function convertCurrencyFields() {
      $currency = new Currency();
      $currency->retrieve($this->currency_id);

      if ( isset($this->gross) ) {
         $this->gross = !number_empty($this->gross) ? $this->gross : 0.0;
         $this->gross_usdollar = $currency->convertToDollar(unformat_number($this->gross));
      }
      if ( isset($this->net) ) {
         $this->net = !number_empty($this->net) ? $this->net : 0.0;
         $this->net_usdollar = $currency->convertToDollar(unformat_number($this->net));
      }
      if ( isset($this->employer_cost) ) {
         $this->employer_cost = !number_empty($this->employer_cost) ? $this->employer_cost : 0.0;
         $this->employer_cost_usdollar = $currency->convertToDollar(unformat_number($this->employer_cost));
      }
   }

}
