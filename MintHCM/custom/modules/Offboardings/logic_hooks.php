<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
if(!isset($hook_array)){ $hook_array = Array();} 
// position, file, function 
if(!isset($hook_array['before_save'])){ $hook_array['before_save'] = Array(); }
$hook_array['before_save'][] = Array(1, 'Offboardings push feed', 'modules/Offboardings/SugarFeeds/OffboardingsFeed.php','OffboardingsFeed', 'pushFeed'); 



?>