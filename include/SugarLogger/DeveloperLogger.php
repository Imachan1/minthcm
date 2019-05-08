<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/SugarLogger/LoggerManager.php');
require_once('include/SugarLogger/LoggerTemplate.php');

class DeveloperLogger implements LoggerTemplate {

   private $logSize;
   private $full_log_file;
   private $initialized;
   private $fp;

   public function __construct() {
      $this->_doInitialization();
      LoggerManager::setLogger('dev', 'DeveloperLogger');
   }

   protected function _doInitialization() {
      $this->logSize = 1024 * 1024 * 10; // 10 MBytes
      $this->full_log_file = 'developer.log';
      $this->dateFormat = '%c';
      $this->rollLog();
      $this->initialized = $this->_fileCanBeCreatedAndWrittenTo();
   }

   /**
    * Checks to see if the SugarLogger file can be created and written to
    */
   protected function _fileCanBeCreatedAndWrittenTo() {
      $this->_attemptToCreateIfNecessary();
      return file_exists($this->full_log_file) && is_writable($this->full_log_file);
   }

   /**
    * Creates the SugarLogger file if it doesn't exist
    */
   protected function _attemptToCreateIfNecessary() {
      if ( file_exists($this->full_log_file) ) {
         return;
      }
      @touch($this->full_log_file);
   }

   protected function rollLog($force = false) {
      if ( empty($this->logSize) ) {
         return;
      }

      if ( filesize($this->full_log_file) >= $this->logSize ) {
         unlink($this->full_log_file);
      }
   }

   public function log($method, $message) {
      if ( !$this->initialized ) {
         return;
      }

      $userID = (!empty($GLOBALS['current_user']->id)) ? $GLOBALS['current_user']->id : '-none-';
      if ( !$this->fp ) {
         $this->fp = fopen($this->full_log_file, 'a');
      }

      if ( is_array($message) && count($message) == 1 ) {
         $message = array_shift($message);
      }

      if ( is_array($message) ) {
         $message = print_r($message, true);
      }

      fwrite($this->fp, strftime($this->dateFormat) . ' [' . getmypid() . '][' . $userID . '][' . strtoupper($method) . '] ' . $message . "\n");
   }

}
