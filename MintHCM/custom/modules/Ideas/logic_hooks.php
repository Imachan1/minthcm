<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
if(!isset($hook_array)){ $hook_array = Array();} 
// position, file, function 
if(!isset($hook_array['before_save'])){ $hook_array['before_save'] = Array(); }
$hook_array['before_save'][] = Array(1, 'Ideas push feed', 'modules/Ideas/SugarFeeds/IdeasFeed.php','IdeasFeed', 'pushFeed'); 



?>