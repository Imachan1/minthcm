<?php

$popupMeta = array(
   'moduleMain' => 'Candidatures',
   'varName' => 'Candidatures',
   'orderBy' => 'candidatures.name',
   'whereClauses' => array(
      'name' => 'candidatures.name',
      'candidate_name' => 'candidatures.candidate_name',
      'status' => 'candidatures.status',
      'source' => 'candidatures.source',
      'assigned_user_id' => 'candidatures.assigned_user_id',
      'favorites_only' => 'candidatures.favorites_only',
   ),
   'searchInputs' => array(
      'name',
      'candidate_name',
      'status',
      'source',
      'assigned_user_id',
      'favorites_only',
      'reason_for_rejection',
   ),
   'searchdefs' => array(
      'name' => array(
         'name' => 'name',
      ),
      'candidate_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_CANDIDATES_TITLE',
         'id' => 'CANDIDATES_CANDIDATURESCANDIDATES_IDA',
         'sortable' => false,
         'name' => 'candidate_name',
      ),
      'status' => array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'name' => 'status',
      ),
      'source' => array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_SOURCE',
         'name' => 'source',
      ),
      'reason_for_rejection' => array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_REASON_FOR_REJECTION',
         'name' => 'reason_for_rejection',
      ),
      'assigned_user_id' => array(
         'name' => 'assigned_user_id',
         'label' => 'LBL_ASSIGNED_TO',
         'type' => 'enum',
         'function' => array(
            'name' => 'get_user_array',
            'params' => array(
               false,
            ),
         ),
      ),
      'favorites_only' => array(
         'name' => 'favorites_only',
         'label' => 'LBL_FAVORITES_FILTER',
         'type' => 'bool',
      ),
   ),
   'listviewdefs' => array(
      'NAME' => array(
         'label' => 'LBL_NAME',
         'default' => true,
         'link' => true,
         'name' => 'name',
      ),
      'STATUS' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'name' => 'status',
      ),
      'TO_DECISION' => array(
         'type' => 'bool',
         'default' => true,
         'label' => 'LBL_TO_DECISION',
      ),
      'CANDIDATES_NAME' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_CANDIDATES_TITLE',
         'id' => 'CANDIDATES_CANDIDATURESCANDIDATES_IDA',
         'sortable' => false,
         'default' => true,
         'name' => 'candidate_name',
      ),
      'NET_AMOUNT' => array(
         'related_fields' => array(
            'currency_id',
         ),
         'type' => 'currency',
         'default' => true,
         'label' => 'LBL_NET_AMOUNT',
         'currency_format' => true,
      ),
      'GROSS_AMOUNT' => array(
         'related_fields' => array(
            'currency_id',
         ),
         'type' => 'currency',
         'default' => true,
         'label' => 'LBL_GROSS_AMOUNT',
         'currency_format' => true,
      ),
      'DG_AMOUNT' => array(
         'related_fields' => array(
            'currency_id',
         ),
         'type' => 'currency',
         'default' => true,
         'label' => 'LBL_DG_AMOUNT',
         'currency_format' => true,
      ),
      'SCORING' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'label' => 'SCORING',
      ),
      'ASSIGNED_USER_NAME' => array(
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'module' => 'Employees',
         'id' => 'ASSIGNED_USER_ID',
         'default' => true,
         'name' => 'assigned_user_name',
      ),
      'DATE_MODIFIED' => array(
         'type' => 'datetime',
         'studio' => array(
            'portaleditview' => false,
         ),
         'readonly' => true,
         'label' => 'LBL_DATE_MODIFIED',
         'default' => true,
      ),
   ),
);
