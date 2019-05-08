<?php

$notice_config = array(
   array(
      'notice_type' => 'time',
      'notice_template' => 'a88154ad-140b-1fad-5795-56377df35887', //mail template id
      'notice_module' => 'Tasks',
      'emails' => array(
         array(
            'related_id' => 'assigned_user_id'
         ),
      ),
      'time_comparison_field' => 'date_due',
      'time_difference' => '0 days',
   ),
);
