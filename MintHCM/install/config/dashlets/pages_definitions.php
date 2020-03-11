<?php

$main = array(
    'columns' => array(
        0 => array(
            'width' => '60%',
            'dashlets' => array(
                0 => 'main_offboarding_dashlet',
                2 => 'main_calendar_dashlet',
                3 => 'main_todays_work_schedule_dashlet',
                4 => 'main_leave_of_absence_dashlet',
                5 => 'main_appraisals_report_dashlet',
                6 => 'main_reservation_dashlet',
                7 => 'main_my_tasks_dashlet',
            ),
        ),
        1 => array(
            'width' => '40%',
            'dashlets' => array(
                0 => 'main_contracts_dashlet',
                1 => 'main_terms_of_employment_dashlet',
                3 => 'main_appraisals_dashlet',
                5 => 'main_competencies_dashlet',
                6 => 'main_daily_work_schedule_dashlet',
            ),
        ),
    ),
    'numColumns' => '3',
    'pageTitleLabel' => 'LBL_HOME_PAGE_1_NAME', //main_
);

$recruitment = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'recruitment_kreports_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'recruitment_kreports_second_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => $GLOBALS['app_strings']['LBL_RECRUITMENT_DASHBOARD'],
    'numColumns' => '2',
);

$hr_manager = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'hr_manager_trainings_dashlet',
                1 => 'hr_manager_appraisals_dashlet',
                2 => 'hr_manager_onboardings_dashlet',
                3 => 'hr_manager_offboardings_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'hr_manager_recruitments_dashlet',
                1 => 'hr_manager_candidatures_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => $GLOBALS['app_strings']['LBL_HR_MANAGER_DASHBOARD'], //hr_manager_
    'numColumns' => '2',
);

$my_team = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'my_team_ideas_dashlet',
                1 => 'my_team_applications_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'my_team_work_schedules_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => $GLOBALS['app_strings']['LBL_MY_TEAM_DASHBOARD'],
    'numColumns' => '2',
);

$employee = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'employee_work_schedules_dashlet',
                1 => 'employee_trainings_dashlet',
                2 => 'employee_goals_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'employee_ideas_dashlet',
                1 => 'employee_applications_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => $GLOBALS['app_strings']['LBL_MY_RECORDS_DASHBOARD'],
    'numColumns' => '2',
);

$hr_actions = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'hr_actions_my_meetings_dashlet',
                1 => 'hr_actions_my_calls_dashlet',
                2 => 'hr_actions_onboardings_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'hr_actions_recruitments_dashlet',
                1 => 'hr_actions_candidatures_dashlet',
                2 => 'hr_actions_offboardings_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => $GLOBALS['app_strings']['LBL_HR_ACTIONS_DASHBOARD'],
    'numColumns' => '2',
);

$settlements = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'settlements_work_schedules_dashlet',
                1 => 'settlements_contracts_dashlet',
                2 => 'settlements_delegations_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'settlements_second_work_schedules_dashlet',
                1 => 'settlements_second_settlements_contracts_dashlet',
                2 => 'settlements_second_delegations_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => $GLOBALS['app_strings']['LBL_SPENT_TIME_DASHBOARD'],
    'numColumns' => '2',
);
