<?php

$config_override = array(
   'package_id' => 'date-last-next-contacts', // shortname of package send to Api to validate key
   'package_name' => 'Last/Next Contact Date',
   'suppliers' => array( 'eVolpe'), //define which types of license is available for that package
   'type' => eVolpe\Subscriptions\Config\BasePackageConfig::VALIDATE_INSTANCE, //define what type of license it is, user or instance
   'validation_period' => '24',
   'default_supplier' => 'eVolpe',
);
