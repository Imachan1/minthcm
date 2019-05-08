<?php

/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of Christian Knoll
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */
if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$dictionary['view_tools_queue'] = array(
   'table' => 'view_tools_queue',
   'fields' => array(
      array(
         'name' => 'id',
         'type' => 'id',
      ),
      array(
         'name' => 'date_modified',
         'type' => 'datetime',
      ),
      array(
         'name' => 'module_name',
         'type' => 'varchar',
      ),
      array(
         'name' => 'record_id',
         'type' => 'id',
      ),
   ),
   'indices' => array(
      array(
         'name' => 'view_tools_queue_primary',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'view_tools_queue_date_modified',
         'type' => 'index',
         'fields' =>
         array( 'date_modified' )
      ),
   ),
);
