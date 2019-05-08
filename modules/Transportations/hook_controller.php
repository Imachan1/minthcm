<?php

class TransportationsLogicHooks {

   public function reformat_number(&$number) {
      $number = number_format($number, 2, '.', ' ');
   }

   /**
    * Pobranie id waluty po identyfikatorze ISO
    */
   public function retrieveIDByISO($iso, $bean) {
      $currency = new Currency();
      $defiso = $currency->getDefaultISO4217();

      if ( $defiso == $iso )
         return '-99';

      $query = "SELECT id FROM currencies WHERE iso4217='$iso' AND deleted=0 AND status='Active';";
      $result = $bean->db->query($query);
      if ( $result ) {
         $row = $bean->db->fetchByAssoc($result);
         if ( $row ) {
            return $row['id'];
         }
      }
      return '';
   }

   /**
    * Suma kosztow przejazdow
    */
   public function sum_costs_for_transports($currency_id, $bean) {
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.transportation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.currency_id='" . $currency_id . "'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      return $cost = ($row['sum'] > 0) ? $row['sum'] : "0";
   }

   /**
    * Przejazdy - przed wygenerowaniem pdf
    */
   public function transport_before_pdf(&$bean, $event, $arguments) {
      //find ids of currencies
      $pln_id = $this->retrieveIDByISO('PLN', $bean);
      $eur_id = $this->retrieveIDByISO('EUR', $bean);
      $usd_id = $this->retrieveIDByISO('USD', $bean);

      //sum the pln value (basic currency) and then eur
      $bean->pln_total = $this->sum_costs_for_transports($pln_id, $bean);
      $bean->eur_total = $this->sum_costs_for_transports($eur_id, $bean);
      $bean->usd_total = $this->sum_costs_for_transports($usd_id, $bean);
      $this->reformat_number($bean->pln_total);
      $this->reformat_number($bean->eur_total);
      $this->reformat_number($bean->usd_total);
   }

   public function countTransportationCostsInDelegation($bean, $event, $arguments) {
      if ( $arguments['relationship'] == 'transportations_delegations' && $arguments['related_id'] ) {
         $delegation = BeanFactory::getBean('Delegations', $arguments['related_id']);
         if ( $delegation && $delegation->id ) {
            $delegation->save();
         }
      }
   }

}
