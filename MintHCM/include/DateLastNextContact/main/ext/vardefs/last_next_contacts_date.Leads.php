<?php

$dictionary['Lead']['fields']['last_time_contact'] = array(
   'name' => 'last_time_contact',
   'label' => 'LBL_LAST_TIME_CONTACT',
   'vname' => 'LBL_LAST_TIME_CONTACT',
   'required' => false,
   'readonly' => true,
   'type' => 'datetimecombo',
   'audited' => false,
   'massupdate' => false,
   'options' => 'date_range_search_dom',
   'importable' => false,
   'duplicate_merge' => false,
   'reportable' => true,
   'unified_search' => false,
   'enforced' => false,
   'enable_range_search' => true,
   'duplicate_on_record_copy' => 'no',
);

$dictionary['Lead']['fields']['date_planned_contact'] = array(
   'name' => 'date_planned_contact',
   'label' => 'LBL_DATE_PLANNED_CONTACT',
   'vname' => 'LBL_DATE_PLANNED_CONTACT',
   'required' => false,
   'readonly' => true,
   'type' => 'datetimecombo',
   'audited' => false,
   'massupdate' => false,
   'options' => 'date_range_search_dom',
   'importable' => false,
   'duplicate_merge' => false,
   'reportable' => true,
   'unified_search' => false,
   'enable_range_search' => true,
   'duplicate_on_record_copy' => 'no',
);

$dictionary["Lead"]["acls"]["SugarACLSubscriptionFieldsPerInstance"] = [
    'package_id'=>'date-last-next-contacts',
    'verify_fields'=>['last_time_contact','date_planned_contact'],
];