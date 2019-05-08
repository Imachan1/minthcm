<?php

$hook_version = 1;
$hook_array = Array();

$hook_array['before_pdf_generate'] = Array();
$hook_array['before_pdf_generate'][] = Array( 1, 'Before PDF generate', 'modules/Transportations/hook_controller.php', 'TransportationsLogicHooks', 'transport_before_pdf' );

$hook_array['after_relationship_add'] = Array();
$hook_array['after_relationship_add'][] = Array( 1, 'After relationship add', 'modules/Transportations/hook_controller.php', 'TransportationsLogicHooks', 'countTransportationCostsInDelegation' );

$hook_array['after_relationship_delete'] = Array();
$hook_array['after_relationship_delete'][] = Array( 1, 'After relationship delete', 'modules/Transportations/hook_controller.php', 'TransportationsLogicHooks', 'countTransportationCostsInDelegation' );