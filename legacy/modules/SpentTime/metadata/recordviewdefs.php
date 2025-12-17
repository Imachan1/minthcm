<?php

$viewdefs['SpentTime'] = [
    'order' => ['basicInfo'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                [
                                    'name' => 'workschedule_name',
                                    'displayParams' => [
                                        'field_to_name_array' => [
                                            'id' => 'workschedule_id',
                                            'name' => 'workschedule_name',
                                            'date_start' => 'work_date',
                                        ],
                                        'call_back_function' => 'set_return_overload',
                                    ],
                                ],
                                'spent_time',
                                'category',
                            ],
                            [
                                [
                                    'name' => 'date_start',
                                    'displayParams' => [
                                        'minutesStep' => 5,
                                    ],
                                ],
                                [
                                    'name' => 'date_end',
                                    'displayParams' => [
                                        'minutesStep' => 5,
                                    ],
                                ],
                                [
                                    'name' => 'description',
                                    'span' => 12,
                                ],
                            ],
                        ],
                        'hiddenFields' => [
                            'projecttask_issue_tracker',
                            'current_user_is_admin',
                            'work_date',
                        ],
                        'includes' => [
                            'include/javascript/moment.min.js',
                            'modules/SpentTime/js/view.edit.js',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
