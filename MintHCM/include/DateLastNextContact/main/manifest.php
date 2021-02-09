<?php

$manifest = array(
    'acceptable_sugar_versions' => array(
        '6.5',
        '7.6',
        '7.7',
        '7.8',
        '7.9',
        '8.0',
        '9.0',
        '9.1',
        '10',
    ),
    'acceptable_sugar_flavors' => array(
        'PRO',
        'CORP',
        'ENT',
        'ULT',
        'CE',
    ),
    'readme' => '',
    'key' => 'ev',
    'author' => 'eVolpe',
    'description' => 'Data planowanego, ostatniego kontaktu',
    'icon' => '',
    'is_uninstallable' => true,
    'name' => 'Last/Next Contact Date',
    'published_date' => '2016-02-12 08:08:52',
    'type' => 'module',
    'version' => '1.14.120',
    'remove_tables' => 'prompt',
);
$CRM_version = isset($GLOBALS['sugar_config']['suitecrm_version']) && !empty($GLOBALS['sugar_config']['suitecrm_version']) ? 'SuiteCRM' : 'SugarCRM';

$installdefs = array(
    'id' => 'date-last-next-contacts',
    'administration' => array(
        array(
            'from' => '<basepath>/ext/administration/DLNCcalc.adminoption.php',
            'to' => 'modules/Administration/DLNCcalc.adminoption.php',
        ),
    ),
    'vardefs' => array(
        array(
            'from' => '<basepath>/ext/vardefs/last_next_contacts_date.Accounts.php',
            'to_module' => 'Accounts',
        ),
        array(
            'from' => '<basepath>/ext/vardefs/last_next_contacts_date.Contacts.php',
            'to_module' => 'Contacts',
        ),
        array(
            'from' => '<basepath>/ext/vardefs/last_next_contacts_date.Leads.php',
            'to_module' => 'Leads',
        ),
        array(
            'from' => '<basepath>/ext/vardefs/last_next_contacts_date.Prospects.php',
            'to_module' => 'Prospects',
        ),
        array(
            'from' => '<basepath>/ext/vardefs/last_next_contacts_date.Emails.php',
            'to_module' => 'Emails',
        ),
    ),
    'language' => array(
        array(
            'from' => '<basepath>/ext/language/pl_PL.Accounts.php',
            'to_module' => 'Accounts',
            'language' => 'pl_PL',
        ),
        array(
            'from' => '<basepath>/ext/language/en_us.Accounts.php',
            'to_module' => 'Accounts',
            'language' => 'en_us',
        ),
        array(
            'from' => '<basepath>/ext/language/pl_PL.Contacts.php',
            'to_module' => 'Contacts',
            'language' => 'pl_PL',
        ),
        array(
            'from' => '<basepath>/ext/language/en_us.Contacts.php',
            'to_module' => 'Contacts',
            'language' => 'en_us',
        ),
        array(
            'from' => '<basepath>/ext/language/pl_PL.Leads.php',
            'to_module' => 'Leads',
            'language' => 'pl_PL',
        ),
        array(
            'from' => '<basepath>/ext/language/en_us.Leads.php',
            'to_module' => 'Leads',
            'language' => 'en_us',
        ),
        array(
            'from' => '<basepath>/ext/language/pl_PL.Prospects.php',
            'to_module' => 'Prospects',
            'language' => 'pl_PL',
        ),
        array(
            'from' => '<basepath>/ext/language/en_us.Prospects.php',
            'to_module' => 'Prospects',
            'language' => 'en_us',
        ),
        array(
            'from' => '<basepath>/ext/language/en_us.Administration.php',
            'to_module' => 'Administration',
            'language' => 'en_us',
        ),
        array(
            'from' => '<basepath>/ext/language/pl_PL.Administration.php',
            'to_module' => 'Administration',
            'language' => 'pl_PL',
        ),
    ),
    'copy' => array(
        array(
            'from' => '<basepath>/include/config/last_next_contact_config_base.php',
            'to' => 'custom/include/config/last_next_contact_config_base.php',
        ),
        array(
            'from' => '<basepath>/include/LastNextContacts',
            'to' => 'custom/include/LastNextContacts',
        ),
        array(
            'from' => '<basepath>/include/SugarFields/Fields/DLNCdatetimecombo',
            'to' => 'custom/include//SugarFields/Fields/DLNCdatetimecombo',
        ),
        array(
            'from' => '<basepath>/modules/Administration/AddLastNextContactPanel.php',
            'to' => 'modules/Administration/AddLastNextContactPanel.php',
        ),
        array(
            'from' => '<basepath>/modules/Administration',
            'to' => 'custom/modules/Administration',
        ),
        array(
            'from' => '<basepath>/modules/Emails',
            'to' => 'custom/modules/Emails',
        ),
        array(
            'from' => '<basepath>/not_upgrade_safe/modules/Administration/metadata/dlncsettingsdefs.php',
            'to' => 'modules/Administration/metadata/dlncsettingsdefs.php',
        ),
    ),
    'entrypoints' => array(
        array(
            'from' => '<basepath>/include/LastNextContacts/entrypoints/CalculateDLNCEntrypoint.php',
            'to_module' => 'Administration',
        ),
    ),
    'hookdefs' => array(
        array(
            'from' => '<basepath>/ext/logic_hooks/last_next_contacts_date.php',
            'to_module' => 'application',
        ),
        array(
            'from' => '<basepath>/ext/logic_hooks/last_next_contacts_date.Emails.php',
            'to_module' => 'Emails',
        ),
    ),
    'relationships' => array(
        array(
            'meta_data' => '<basepath>/ext/relationships/last_next_contacts_queueMetaData.php',
        ),
    ),
    'scheduledefs' => array(
        array(
            'from' => '<basepath>/ext/schedulers/LastNextContactsQueueJob.php',
        ),
    ),
);

// SUBSKRYPCJE
$installdefs['copy'][] = array(
    'from' => '<basepath>/modules/ev_PackageSubscriptions/Logic/configs/date-last-next-contacts',
    'to' => 'custom/modules/ev_PackageSubscriptions/Logic/configs/date-last-next-contacts',
);

$installdefs['copy'][] = array(
    'from' => '<basepath>/include/evSubscription/DateLastNextContactSubscription.php',
    'to' => 'custom/include/evSubscription/DateLastNextContactSubscription.php',
);
