<?php

class CostsApi {

   public function validateSelectedCurrency($args) {
      $currency_id = $args['currency_id'];

      if ( $currency_id == '-99' ) {
         return true;
      }
      if ( !empty($args['delegation_id']) ) {
         $delegation_id = $args['delegation_id'];
      } else if ( !empty($args['transportation_id']) ) {
         $transport = BeanFactory::getBean('Transportations', $args['transportation_id']);
         $delegation_id = $transport->delegation_id;
      } else {
         return true;
      }

      $delegation = BeanFactory::getBean('Delegations', $delegation_id);
      $delegation_locale = BeanFactory::getBean('Delegations_locale', $delegation->delegation_locale_id);
      $locale_currency = $delegation_locale->currency_id;
      if ( $currency_id == $locale_currency ) {
         return true;
      }
      return false;
   }

}
