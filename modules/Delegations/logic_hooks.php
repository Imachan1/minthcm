<?php

$hook_version = 1;
$hook_array = Array();

$hook_array['before_pdf_generate'] = Array();
$hook_array['before_pdf_generate'][] = Array( 1, 'Before PDF generate', 'modules/Delegations/hook_controller.php', 'DelegationsLogicHooks', 'delegations_before_pdf' );