<?php

$admin_option_defs = array();
$admin_option_defs['Administration']['AddLastNextContactPanel'] = array(
    'Administration',
    'LBL_ADD_LAST_NEXT_CONTACT_PANEL',
    'LBL_ADD_LAST_NEXT_CONTACT_PANEL_DESCRIPTION',
    'index.php?module=Administration&action=AddLastNextContactPanel',
);
$admin_option_defs['Administration']['AddLastNextContactTabs'] = array(
    'Administration',
    'LBL_ADD_LAST_NEXT_CONTACT_TABS',
    'LBL_ADD_LAST_NEXT_CONTACT_TABS_DESCRIPTION',
    'index.php?module=Administration&action=AddLastNextContactPanel&type=1',
);

$admin_option_defs['Administration']['DLNCSettings'] = array(
    'Administration',
    'LBL_DLNC_SETTINGS',
    'LBL_DLNC_SETTINGS_DESCRIPTION',
    'index.php?module=Administration&action=DLNCSettings&type=1',
);
$admin_option_defs['Administration']['configs'] = array(
    'Administration',
    'LBL_CALCULATE_DLNC',
    'LBL_CALCULATE_DLNC',
    'index.php?entryPoint=CalculateDLNC',
);

$admin_group_header[] = array('LBL_ADD_LAST_NEXT_CONTACT_PANEL', '', false, $admin_option_defs, '');
$config_categories[] = 'Administration';
