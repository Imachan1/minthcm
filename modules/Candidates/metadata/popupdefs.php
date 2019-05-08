<?php

$popupMeta = array(
   'moduleMain' => 'Candidates',
   'varName' => 'Candidates',
   'orderBy' => 'candidates.first_name, candidates.last_name',
   'whereClauses' => array(
      'first_name' => 'candidates.first_name',
      'last_name' => 'candidates.last_name',
      'address_city' => 'candidates.address_city',
      'created_by_name' => 'candidates.created_by_name',
      'favorites_only' => 'candidates.favorites_only',
      'relocation' => 'candidates.relocation',
   ),
   'searchInputs' => array(
      'first_name',
      'last_name',
      'address_city',
      'created_by_name',
      'email',
      'favorites_only',
      'relocation',
   ),
   'searchdefs' => array(
      'first_name' => array(
         'name' => 'first_name',
      ),
      'last_name' => array(
         'name' => 'last_name',
      ),
      'address_city' => array(
         'name' => 'address_city',
      ),
      'created_by_name' => array(
         'name' => 'created_by_name',
      ),
      'email' => array(
         'name' => 'email',
      ),
      'favorites_only' => array(
         'name' => 'favorites_only',
         'label' => 'LBL_FAVORITES_FILTER',
         'type' => 'bool',
      ),
      'relocation' => array(
         'type' => 'bool',
         'label' => 'LBL_RELOCATION',
         'name' => 'relocation',
      ),
   ),
   'listviewdefs' => array(
      'NAME' => array(
         'label' => 'LBL_LIST_NAME',
         'link' => true,
         'default' => true,
         'related_fields' => array(
            'first_name',
            'last_name',
            'salutation',
         )
      ),
      'ADDRESS_CITY' => array(
         'label' => 'LBL_PRIMARY_ADDRESS_CITY',
         'name' => 'address_city',
         'default' => true,
      ),
      'CREATED_BY_NAME' => array(
         'label' => 'LBL_CREATED',
         'name' => 'created_by_name',
         'default' => true,
      ),
      'email' =>
      array(
         'name' => 'email',
         'width' => '10%',
      ),
      'RELOCATION' => array(
         'type' => 'bool',
         'default' => true,
         'label' => 'LBL_RELOCATION',
         'name' => 'relocation',
      ),
   ),
);
