<?php

$module_name = 'Achievements';
$viewdefs[$module_name] = array(
    'EditView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    'SAVE',
                    'CANCEL',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                array('label' => '10', 'field' => '30'),
                array('label' => '10', 'field' => '30'),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_PANEL_ACHIEVEMENTS' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_ASSIGNMENT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'LBL_PANEL_ACHIEVEMENTS' => array(
                array('name', 'active'),
                array('category', 'award_type'),
                array('visibility', ''),
                array(
                    array(
                        'name' => 'icon',
                        'displayParams' => array('field_name' => 'icon'),
                    ),
                    '',
                ),
                array(
                    array(
                        'name' => 'description',
                        'displayParams' => array('rows' => 4, 'cols' => 60),
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' => array(
                array('assigned_user_name', ''),
            ),
        ),
    ),
);
