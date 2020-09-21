<?php
$module_name = 'SurveyResponses';
$viewdefs[$module_name] = array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    3 => 'FIND_DUPLICATES',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
        ),
        'panels' => array(
            'default' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => 'date_entered',
                    1 => 'date_modified',
                ),
                2 => array(
                    0 => 'description',
                    //MintHCM #74238 START
                    //     1 => array(
                    //         'name' => 'contact_name',
                    //     ),
                    // ),
                    // 3 => array(
                    //     0 => array(
                    //         'name' => 'account_name',
                    //     ),
                    //MintHCM #74238 END
                    1 => array(
                        'name' => 'survey_name',
                    ),
                ),
                //MintHCM #74238 START
                // 4 => array(
                3 => array(
                    0 => 'campaign_name',
                    1 => 'employee_name',
                    //MintHCM #74238 END
                ),
                //MintHCM #74238 START
                //5 => array(
                4 => array(
                    //MintHCM #74238 END
                    0 => array(
                        'name' => 'question_responses_display',
                    ),
                ),
            ),
        ),
    ),
);
