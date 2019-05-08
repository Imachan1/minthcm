<?php

/**
 * Returns result of custom Module Api method
 * EOU:
 * "callCustomApi( Accounts , getSomeInfo , param_1, param_2, ..., param_N )" where:
 * - Accounts - module name to find his Api class (here: AccountsApi) / this param is required
 * - getSomeInfo - method name to call in this Api class / this param is required
 * - param_1, param_2, ..., param_N - parameters given for method / this params are optional
 * Important info:
 * - location of Api: (custom/)modules/ModuleName/api/ModuleNameApi.php
 * -- example: modules/ev_Orders/api/ev_OrdersApi.php or custom/modules/Accounts/api/AccountsApi.php
 * - method called in this class should returns: boolean, integer, float, string, array
 * - if callCustomApi will not have first and second params (eg. Accounts, getSomeInfo) then callCustomApi returns false
 * - if called method doesn't exists or gives Fatal error then callCustomApi returns false
 */
class VTExpression_isSuperior extends VTExpression {

   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_validation' );
   public $serversideFrontend = true;
   public $sqlBackendFormula = false;

   public function backend($arguments = Array()) {
      global $current_user;
      if ( $current_user->isAdmin() ) {
         return true;
      }
      $result = false;
      $sugar_controller = ControllerFactory::getController('Users');
      $user_ids = $sugar_controller::getIDOfSubordinates(array( $current_user->id ));
      $user_ids[] = $current_user->id;
      if ( in_array($arguments[0], $user_ids) ) {
         $result = true;
      }
      return $result;
   }

}
