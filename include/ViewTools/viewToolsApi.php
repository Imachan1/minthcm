<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module = $_POST['module'];
$action = $_POST['action'];
if ( $module != '' && $action != '' ) {
   try {
      //require api file from custom/modules
      if ( file_exists("custom/modules/{$module}/api/{$module}Api.php") ) {
         require_once("custom/modules/{$module}/api/{$module}Api.php");
      } else if ( file_exists("modules/{$module}/api/{$module}Api.php") ) {
         require_once("modules/{$module}/api/{$module}Api.php");
      } else {
         die('Api file not found for this module');
      }
      $className = $module . 'Api';
      $apiObj = new $className();
      $return = $apiObj->$action($_POST);
   } catch ( Exception $e ) {
      $return = $e;
   }
   if ( !isJson($return) ) {
      $return = json_encode($return);
   }
   echo $return;
}

function isJson($string) {
   if ( is_string($string) ) {
      $w = json_decode($string);
      return ($w !== NULL);
   } else {
      return false;
   }
}
