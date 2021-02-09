<?php

$dictionary["Email"]['indices']['dlnc_email'] = array(
   'name' => 'dlnc_email',
   'type' => 'index',
   'fields' => array( 'id','deleted','status' ),
);

if(isset($dictionary["Email"]['fields']['date_sent'])) {
   $dictionary["Email"]['indices']['dlnc_email']['fields'][] = 'date_sent';
}
else if(isset($dictionary["Email"]['fields']['date_sent_received'])) {
   $dictionary["Email"]['indices']['dlnc_email']['fields'][] = 'date_sent_received';
}