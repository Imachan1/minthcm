<?php

$module_name = 'Candidates';
$viewdefs [$module_name] = array(
   'EditView' => array(
      'templateMeta' => array(
         'maxColumns' => '2',
         'useTabs' => false,
         'widths' => array(
            array(
               'label' => '10',
               'field' => '30',
            ),
            array(
               'label' => '10',
               'field' => '30',
            ),
         ),
         'tabDefs' => array(
            'LBL_CONTACT_INFORMATION' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
            'LBL_SHOW_MORE_INFORMATION' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
            'LBL_RECORDVIEW_PANEL1' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
            'LBL_RECORDVIEW_PANEL2' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' => array(
         'lbl_contact_information' => array(
            array(
               array(
                  'name' => 'first_name',
                  'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
               ),
               'last_name',
            ),
            array(
               array(
                  'name' => 'phone_mobile',
                  'comment' => 'Mobile phone number of the contact',
                  'label' => 'LBL_MOBILE_PHONE',
               ),
               array(
                  'name' => 'email1',
                  'label' => 'LBL_EMAIL',
               ),
            ),
            array(
               array(
                  'name' => 'primary_address_street',
                  'hideLabel' => true,
                  'type' => 'address',
                  'displayParams' => array(
                     'key' => 'primary',
                     'rows' => 2,
                     'cols' => 30,
                     'maxlength' => 150,
                  ),
               ),
               array(
                  'name' => 'alt_address_street',
                  'hideLabel' => true,
                  'type' => 'address',
                  'displayParams' => array(
                     'key' => 'alt',
                     'copy' => 'primary',
                     'rows' => 2,
                     'cols' => 30,
                     'maxlength' => 150,
                  ),
               ),
            ),
            array(
               'birthdate',
               ''
            ),
         ),
         'LBL_SHOW_MORE_INFORMATION' => array(
            array(
               'potential',
               'relocation',
            ),
            array(
               'description',
            ),
         ),
         'LBL_RECORDVIEW_PANEL1' => array(
            array(
               'linkedin',
               'goldenline',
            ),
            array(
               'facebook',
               'skype',
            ),
         ),
         'LBL_RECORDVIEW_PANEL2' => array(
            array(
               'assigned_user_name',
               '',
            ),
         ),
      ),
   ),
);
