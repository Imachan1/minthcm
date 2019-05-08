<?php

$module_name = 'Candidates';
$viewdefs [$module_name] = array(
   'DetailView' => array(
      'templateMeta' => array(
         'form' => array(
            'buttons' => array(
               'EDIT',
               'DUPLICATE',
               'DELETE',
               'FIND_DUPLICATES',
            ),
         ),
         'useTabs' => true,
         'maxColumns' => '2',
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
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_SHOW_MORE_INFORMATION' => array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_RECORDVIEW_PANEL1' => array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
            'LBL_RECORDVIEW_PANEL2' => array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' => array(
         'lbl_contact_information' => array(
            array(
               'full_name',
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
               array(
                  'name' => 'assigned_user_name',
                  'label' => 'LBL_ASSIGNED_TO_NAME',
               ),
               array(
                  'name' => 'employee_name',
                  'label' => 'LBL_CANDIDATE_EMPLOYEE_RELATE_FROM_CANDIDATE'
               )
            ),
            array(
               array(
                  'name' => 'date_entered',
                  'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                  'label' => 'LBL_DATE_ENTERED',
               ),
               array(
                  'name' => 'date_modified',
                  'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                  'label' => 'LBL_DATE_MODIFIED',
               ),
            ),
         ),
      ),
   ),
);
?>
