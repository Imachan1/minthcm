<?php
$layout_defs["TermsOfEmployment"]["subpanel_setup"] = array(
    'documents' => array(
        'order' => 25,
        'module' => 'Documents',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_DOCUMENTS',
        'get_subpanel_data' => 'documents',
        'top_buttons' =>
        array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'mode' => 'MultiSelect',
            ),
        ),
    ),
    'securitygroups' => array(
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'popup_module' => 'SecurityGroups',
                'mode' => 'MultiSelect'
            ),
        ),
        'order' => 900,
        'sort_by' => 'name',
        'sort_order' => 'asc',
        'module' => 'SecurityGroups',
        'refresh_page' => 1,
        'subpanel_name' => 'default',
        'get_subpanel_data' => 'SecurityGroups',
        'add_subpanel_data' => 'securitygroup_id',
        'title_key' => 'LBL_SECURITYGROUPS_SUBPANEL_TITLE',
    ),
);
