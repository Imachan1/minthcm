<?php

// created: 2015-05-04 12:54:03
$subpanel_layout['list_fields'] = array(
   'name' =>
   array(
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'default' => true,
   ),
   'candidate_name' =>
   array(
      'type' => 'relate',
      'link' => true,
      'vname' => 'LBL_CANDIDATES_TITLE',
      'id' => 'CANDIDATES_CANDIDATURESCANDIDATES_IDA',
      'sortable' => false,
      'default' => true,
      'widget_class' => 'SubPanelDetailViewLink',
      'target_module' => 'Candidates',
      'target_record_key' => 'candidate_id',
   ),
   'status' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_STATUS',
   ),
   'to_decision' =>
   array(
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_TO_DECISION',
   ),
   'net_amount' =>
   array(
      'related_fields' =>
      array(
         'currency_id',
      ),
      'type' => 'currency',
      'default' => true,
      'vname' => 'LBL_NET_AMOUNT',
      'currency_format' => true,
   ),
   'gross_amount' =>
   array(
      'related_fields' =>
      array(
         'currency_id',
      ),
      'type' => 'currency',
      'default' => true,
      'vname' => 'LBL_GROSS_AMOUNT',
      'currency_format' => true,
   ),
   'dg_amount' =>
   array(
      'related_fields' =>
      array(
         'currency_id',
      ),
      'type' => 'currency',
      'default' => true,
      'vname' => 'LBL_DG_AMOUNT',
      'currency_format' => true,
   ),
   'scoring' =>
   array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'SCORING',
   ),
   'date_modified' =>
   array(
      'vname' => 'LBL_DATE_MODIFIED',
      'default' => true,
   ),
);
