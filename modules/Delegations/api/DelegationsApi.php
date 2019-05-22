<?php

class DelegationsApi {

   public function checkDelegationLocalCurrencyId($delegation_locale_id) {
      if( is_array($delegation_locale_id)) {
         $delegation_locale_id = $delegation_locale_id['delegation_locale_id'];
      }
      $delegation_locale = BeanFactory::getBean('DelegationsLocale', $delegation_locale_id);
      if ( !empty($delegation_locale) && $delegation_locale->id && $delegation_locale->currency_id != '-99' ) {
         return $delegation_locale->currency_id;
      }
      return false;
   }

}
