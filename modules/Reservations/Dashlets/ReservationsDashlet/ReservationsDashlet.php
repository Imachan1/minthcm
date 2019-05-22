<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Reservations/Reservations.php');

class ReservationsDashlet extends DashletGeneric {

   public function __construct($id, $def = null) {
      require('modules/Reservations/metadata/dashletviewdefs.php');

      parent::__construct($id, $def);

      if ( empty($def['title']) ) {
         $this->title = translate('LBL_HOMEPAGE_TITLE', 'Reservations');
      }

      $this->searchFields = $dashletData['ReservationsDashlet']['searchFields'];
      $this->columns = $dashletData['ReservationsDashlet']['columns'];

      $this->seedBean = BeanFactory::newBean('Reservations');
   }

}
