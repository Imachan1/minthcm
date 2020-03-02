<?php
$dashlets_definitions =  array(
  'main_todays_work_schedule_dashlet' =>
  array(
    'className' => 'TodaysWorkScheduleDashlet',
    'module' => 'Home',
    'forceColumn' => 0,
    'fileLocation' => 'modules/Home/Dashlets/TodaysWorkScheduleDashlet/TodaysWorkScheduleDashlet.php',
    'options' =>
    array(),
  ),
  'main_my_tasks_dashlet' =>
  array(
    'className' => 'MyTasksDashlet',
    'module' => 'Tasks',
    'forceColumn' => 0,
    'fileLocation' => 'modules/Tasks/Dashlets/MyTasksDashlet/MyTasksDashlet.php',
    'options' =>
    array(),
  ),
  'main_offboarding_dashlet' =>  array(
    'className' => 'OffboardingTemplatesDashlet',
    'module' => 'OffboardingTemplates',
    'options' =>
    array(),
    'fileLocation' => 'modules/OffboardingTemplates/Dashlets/OffboardingTemplatesDashlet/OffboardingTemplatesDashlet.php',
  ),
  'main_contracts_dashlet' =>
  array(
    'className' => 'ContractsDashlet',
    'module' => 'Contracts',
    'options' =>
    array(),
    'fileLocation' => 'modules/Contracts/Dashlets/ContractsDashlet/ContractsDashlet.php',
  ),
  'main_terms_of_employment_dashlet' =>
  array(
    'className' => 'TermsOfEmploymentDashlet',
    'module' => 'TermsOfEmployment',
    'options' =>
    array(),
    'fileLocation' => 'modules/TermsOfEmployment/Dashlets/TermsOfEmploymentDashlet/TermsOfEmploymentDashlet.php',
  ),
  'main_appraisals_report_dashlet' =>
  array(
    'className' => 'KReportsDashlet',
    'module' => 'KReports',
    'options' =>
    array(
      'title' => 'Appraisals - Raport',
      'report_id' => '06ac7ffc-6548-4f76-b483-170768adfae9',
      'show_chart' => false,
      'show_data' => true,
      'show_filters' => true,
      'height' => '300',
      'autoRefresh' => '30',
    ),
    'fileLocation' => 'modules/KReports/Dashlets/KReportsDashlet/KReportsDashlet.php',
  ),
  'main_appraisals_dashlet' =>
  array(
    'className' => 'AppraisalsDashlet',
    'module' => 'Appraisals',
    'options' =>
    array(
      'filters' =>
      array(
        'date' =>
        array(),
      ),
      'title' => 'My Appraisals',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'displayRows' => '3',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'status',
        2 => 'type',
        3 => 'date',
        4 => 'employee_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Appraisals/Dashlets/AppraisalsDashlet/AppraisalsDashlet.php',
  ),
  'main_reservation_dashlet' => array(
    'className' => 'ReservationsDashlet',
    'module' => 'Reservations',
    'options' =>
    array(),
    'fileLocation' => 'modules/Reservations/Dashlets/ReservationsDashlet/ReservationsDashlet.php',
  ),
  'main_leave_of_absence_dashlet' =>
  array(
    'className' => 'LeaveOfAbsenceDashlet',
    'module' => 'Home',
    'options' =>
    array(
      'title' => 'Absences in Office',
      'url' => NULL,
      'height' => NULL,
      'autoRefresh' => 0,
      'show_days_of_week' =>
      array(
        'sunday' => true,
        'monday' => true,
        'tuesday' => true,
        'wednesday' => true,
        'thursday' => true,
        'friday' => true,
        'saturday' => true,
      ),
    ),
    'fileLocation' => 'modules/Home/Dashlets/LeaveOfAbsenceDashlet/LeaveOfAbsenceDashlet.php',
  ),
  'main_daily_work_schedule_dashlet' =>
  array(
    'className' => 'WorkSchedulesDashlet',
    'module' => 'WorkSchedules',
    'options' =>
    array(
      'filters' =>
      array(
        'schedule_date' =>
        array(),
      ),
      'title' => 'My Daily Work Schedule',
      'myItemsOnly' => 'true',
      'displayRows' => '1',
      'displayColumns' =>
      array(
        0 => 'assigned_user_name',
        1 => 'schedule_date',
        2 => 'type',
        3 => 'status',
        4 => 'spent_time',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/WorkSchedules/Dashlets/WorkSchedulesDashlet/WorkSchedulesDashlet.php',
  ),
  'main_competencies_dashlet' =>
  array(
    'className' => 'CompetenciesDashlet',
    'module' => 'Competencies',
    'options' =>
    array(
      'filters' =>
      array(
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      'title' => 'My Competencies',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'date_modified',
        2 => 'assigned_user_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Competencies/Dashlets/CompetenciesDashlet/CompetenciesDashlet.php',
  ),
  'main_calendar_dashlet' =>
  array(
    'className' => 'CalendarDashlet',
    'module' => 'Calendar',
    'fileLocation' => 'modules/Calendar/Dashlets/CalendarDashlet/CalendarDashlet.php',
    'options' =>
    array(),
  ),

  'recruitment_kreports_second_dashlet' =>
  array(
    'className' => 'KReportsDashlet',
    'module' => 'KReports',
    'options' =>
    array(
      // 'title' => 'Rekrutacje - Kandydatury na źródło / aY',
      'title' => 'Recruitment - Candidates for the source / aY',
      'report_id' => 'cd43dec0-477c-4500-bd9a-d9902cc10324',
      'show_chart' => true,
      'show_data' => false,
      'show_filters' => false,
      'height' => '350',
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/KReports/Dashlets/KReportsDashlet/KReportsDashlet.php',
  ),
  'recruitment_kreports_dashlet' =>
  array(
    'className' => 'KReportsDashlet',
    'module' => 'KReports',
    'options' =>
    array(
      //'title' => 'Rekrutacje - Kandydatury na źródło / pY',
      'title' => 'Recruitment - Candidates for source / pY',
      'report_id' => 'kba29c52282b3f8326b09a8bbad1f',
      'show_chart' => true,
      'show_data' => false,
      'show_filters' => false,
      'height' => '350',
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/KReports/Dashlets/KReportsDashlet/KReportsDashlet.php',
  ),
  'hr_manager_onboardings_dashlet' =>
  array(
    'className' => 'OnboardingsDashlet',
    'module' => 'Onboardings',
    'options' =>
    array(
      'filters' =>
      array(
        'date_start' =>
        array(),
        'status' =>
        array(
          0 => 'in_progress',
        ),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      'title' => 'Onboardings',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'date_start',
        2 => 'status',
        3 => 'onboardingtemplate_name',
        4 => 'employee_name',
        5 => 'assigned_user_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Onboardings/Dashlets/OnboardingsDashlet/OnboardingsDashlet.php',
  ),
  'hr_manager_offboardings_dashlet' =>
  array(
    'className' => 'OffboardingsDashlet',
    'module' => 'Offboardings',
    'options' =>
    array(
      'filters' =>
      array(
        'date_start' =>
        array(),
        'status' =>
        array(
          0 => 'in_progress',
        ),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      'title' => 'Offboardings',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'date_start',
        2 => 'status',
        3 => 'offboardingtemplate_name',
        4 => 'employee_name',
        5 => 'assigned_user_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Offboardings/Dashlets/OffboardingsDashlet/OffboardingsDashlet.php',
  ),
  'hr_manager_recruitments_dashlet' =>
  array(
    'className' => 'RecruitmentsDashlet',
    'module' => 'Recruitments',
    'options' =>
    array(),
    'fileLocation' => 'modules/Recruitments/Dashlets/RecruitmentsDashlet/RecruitmentsDashlet.php',
  ),
  'hr_manager_trainings_dashlet' =>
  array(
    'className' => 'TrainingsDashlet',
    'module' => 'Trainings',
    'options' =>
    array(
      'filters' =>
      array(
        'date_start' =>
        array(),
        'date_end' =>
        array(),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
        'assigned_user_id' =>
        array(),
      ),
      'title' => 'Trainings',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'status',
        2 => 'date_start',
        3 => 'date_end',
        4 => 'training_type',
        5 => 'parent_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Trainings/Dashlets/TrainingsDashlet/TrainingsDashlet.php',
  ),
  'hr_manager_candidatures_dashlet' =>
  array(
    'className' => 'CandidaturesDashlet',
    'module' => 'Candidatures',
    'options' =>
    array(),
    'fileLocation' => 'modules/Candidatures/Dashlets/CandidaturesDashlet/CandidaturesDashlet.php',
  ),
  'my_team_work_schedules_dashlet' =>
  array(
    'className' => 'WorkSchedulesDashlet',
    'module' => 'WorkSchedules',
    'options' =>
    array(
      'filters' =>
      array(
        'type' =>
        array(
          0 => 'holiday',
          1 => 'leave_at_request',
        ),
        'supervisor_acceptance' =>
        array(
          0 => 'wait',
        ),
        'schedule_date' =>
        array(),
      ),
      //'title' => 'Urlopy do zatwierdzenia',
      'title' => 'Leave for approval',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => 'true',
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'assigned_user_name',
        2 => 'schedule_date',
        3 => 'type',
        4 => 'status',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/WorkSchedules/Dashlets/WorkSchedulesDashlet/WorkSchedulesDashlet.php',
  ),
  'hr_manager_appraisals_dashlet' =>
  array(
    'className' => 'AppraisalsDashlet',
    'module' => 'Appraisals',
    'options' =>
    array(
      'filters' =>
      array(
        'date' =>
        array(),
        'status' =>
        array(
          0 => 'planned',
          1 => 'in_progress',
        ),
      ),
      //'title' => 'Moje oceny',
      'title' => 'My ratings',
      'myItemsOnly' => 'true',
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'status',
        2 => 'type',
        3 => 'date',
        4 => 'employee_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Appraisals/Dashlets/AppraisalsDashlet/AppraisalsDashlet.php',
  ),
  'my_team_applications_dashlet' =>
  array(
    'className' => 'ApplicationsDashlet',
    'module' => 'Applications',
    'options' =>
    array(
      'filters' =>
      array(
        'status' =>
        array(
          0 => 'new',
          1 => 'in_progress',
        ),
      ),
      //'title' => 'Wnioski mojego zespołu',
      'title' => 'My team\'s applications',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => 'true',
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'date_modified',
        2 => 'date_entered',
        3 => 'assigned_user_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Applications/Dashlets/ApplicationsDashlet/ApplicationsDashlet.php',
  ),
  'my_team_ideas_dashlet' =>
  array(
    'className' => 'IdeasDashlet',
    'module' => 'Ideas',
    'options' =>
    array(
      'filters' =>
      array(
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
        'status' =>
        array(
          0 => 'new',
          1 => 'in_progress',
        ),
      ),
      //'title' => 'Pomysły mojego zespołu',
      'title' => 'My team\'s ideas',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => 'true',
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'status',
        2 => 'assigned_user_name',
        3 => 'user_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Ideas/Dashlets/IdeasDashlet/IdeasDashlet.php',
  ),
  'employee_ideas_dashlet' =>
  array(
    'className' => 'IdeasDashlet',
    'module' => 'Ideas',
    'options' =>
    array(),
    'fileLocation' => 'modules/Ideas/Dashlets/IdeasDashlet/IdeasDashlet.php',
  ),
  'employee_goals_dashlet' =>
  array(
    'className' => 'GoalsDashlet',
    'module' => 'Goals',
    'options' =>
    array(
      'filters' =>
      array(
        'date_start' =>
        array(),
        'date_end' =>
        array(),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      //'title' => 'Cele własne',
      'title' => 'My Goals',
      'myItemsOnly' => 'true',
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'status',
        2 => 'date_start',
        3 => 'date_end',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Goals/Dashlets/GoalsDashlet/GoalsDashlet.php',
  ),
  'employee_trainings_dashlet' =>
  array(
    'className' => 'TrainingsDashlet',
    'module' => 'Trainings',
    'options' =>
    array(
      'filters' =>
      array(
        'date_start' =>
        array(),
        'date_end' =>
        array(),
        'status' =>
        array(
          0 => 'planned',
        ),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      'title' => 'Trainings',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'status',
        2 => 'date_start',
        3 => 'date_end',
        4 => 'training_type',
        5 => 'parent_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Trainings/Dashlets/TrainingsDashlet/TrainingsDashlet.php',
  ),
  'employee_applications_dashlet' =>
  array(
    'className' => 'ApplicationsDashlet',
    'module' => 'Applications',
    'options' =>
    array(),
    'fileLocation' => 'modules/Applications/Dashlets/ApplicationsDashlet/ApplicationsDashlet.php',
  ),
  'employee_work_schedules_dashlet' =>
  array(
    'className' => 'WorkSchedulesDashlet',
    'module' => 'WorkSchedules',
    'options' =>
    array(
      'filters' =>
      array(
        'status' =>
        array(
          0 => 'planned',
          1 => 'worked',
        ),
        'schedule_date' =>
        array(
          'type' => 'TP_last_30_days',
        ),
      ),
      //'title' => 'Moje niezatwierdzone plany pracy',
      'title' => 'My Unconfirmed Work Schedules',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'schedule_date',
        2 => 'type',
        3 => 'status',
        4 => 'spent_time',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/WorkSchedules/Dashlets/WorkSchedulesDashlet/WorkSchedulesDashlet.php',
  ),
  'hr_actions_my_meetings_dashlet' =>
  array(
    'className' => 'MyMeetingsDashlet',
    'module' => 'Meetings',
    'options' =>
    array(
      'filters' =>
      array(
        'status' =>
        array(
          0 => 'Planned',
        ),
        'date_start' =>
        array(),
        'date_entered' =>
        array(),
      ),
      'title' => 'My Meetings',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'set_complete',
        1 => 'name',
        2 => 'parent_name',
        3 => 'date_start',
        4 => 'set_accept_links',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Meetings/Dashlets/MyMeetingsDashlet/MyMeetingsDashlet.php',
  ),
  'hr_actions_my_calls_dashlet' =>
  array(
    'className' => 'MyCallsDashlet',
    'module' => 'Calls',
    'options' =>
    array(),
    'fileLocation' => 'modules/Calls/Dashlets/MyCallsDashlet/MyCallsDashlet.php',
  ),
  'hr_actions_recruitments_dashlet' =>
  array(
    'className' => 'RecruitmentsDashlet',
    'module' => 'Recruitments',
    'options' =>
    array(
      'filters' =>
      array(
        'project_status' =>
        array(
          0 => 'for_approval',
          1 => 'plan',
          2 => 'open',
        ),
      ),
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'mySubordinates' => false,
    ),
    'fileLocation' => 'modules/Recruitments/Dashlets/RecruitmentsDashlet/RecruitmentsDashlet.php',
  ),
  'hr_actions_candidatures_dashlet' =>
  array(
    'className' => 'CandidaturesDashlet',
    'module' => 'Candidatures',
    'options' =>
    array(
      'filters' =>
      array(
        'status' =>
        array(
          0 => 'Acceptance',
          1 => 'AfterEntryInterview',
          2 => 'EntryInterview',
          3 => 'InProgress',
          4 => 'MeetingAdditional',
          5 => 'MeetingPrimary',
          6 => 'MeetingTask',
          7 => 'Negotation',
          8 => 'New',
          9 => 'Offer',
          10 => 'PracticalTask',
          11 => 'Preselection',
          12 => 'Scored',
          13 => 'Scored2',
        ),
      ),
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'mySubordinates' => false,
    ),
    'fileLocation' => 'modules/Candidatures/Dashlets/CandidaturesDashlet/CandidaturesDashlet.php',
  ),
  'hr_actions_onboardings_dashlet' =>
  array(
    'className' => 'OnboardingsDashlet',
    'module' => 'Onboardings',
    'options' =>
    array(
      'filters' =>
      array(
        'date_start' =>
        array(),
        'status' =>
        array(
          0 => 'in_progress',
        ),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      'title' => 'Onboardings',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'date_start',
        2 => 'status',
        3 => 'onboardingtemplate_name',
        4 => 'employee_name',
        5 => 'assigned_user_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Onboardings/Dashlets/OnboardingsDashlet/OnboardingsDashlet.php',
  ),
  'hr_actions_offboardings_dashlet' =>
  array(
    'className' => 'OffboardingsDashlet',
    'module' => 'Offboardings',
    'options' =>
    array(
      'filters' =>
      array(
        'date_start' =>
        array(),
        'status' =>
        array(
          0 => 'in_progress',
        ),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      'title' => 'Offboardings',
      'myItemsOnly' => 'true',
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'date_start',
        2 => 'status',
        3 => 'offboardingtemplate_name',
        4 => 'employee_name',
        5 => 'assigned_user_name',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Offboardings/Dashlets/OffboardingsDashlet/OffboardingsDashlet.php',
  ),
  'settlements_second_work_schedules_dashlet' =>
  array(
    'className' => 'WorkSchedulesDashlet',
    'module' => 'WorkSchedules',
    'options' =>
    array(
      'filters' =>
      array(
        'status' =>
        array(
          0 => 'planned',
          1 => 'worked',
        ),
        'schedule_date' =>
        array(
          'type' => 'TP_last_month',
        ),
      ),
      'title' => 'Unconfirmed Work Schedules - Previous Month',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'assigned_user_name',
        2 => 'schedule_date',
        3 => 'type',
        4 => 'status',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/WorkSchedules/Dashlets/WorkSchedulesDashlet/WorkSchedulesDashlet.php',
  ),
  'settlements_work_schedules_dashlet' =>
  array(
    'className' => 'WorkSchedulesDashlet',
    'module' => 'WorkSchedules',
    'options' =>
    array(
      'filters' =>
      array(
        'status' =>
        array(
          0 => 'planned',
          1 => 'worked',
        ),
        'schedule_date' =>
        array(
          'type' => 'TP_last_30_days',
        ),
      ),
      'title' => 'Unconfirmed Work Schedules - Last 30 days',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'assigned_user_name',
        2 => 'schedule_date',
        3 => 'type',
        4 => 'status',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/WorkSchedules/Dashlets/WorkSchedulesDashlet/WorkSchedulesDashlet.php',
  ),
  'settlements_contracts_dashlet' =>
  array(
    'className' => 'ContractsDashlet',
    'module' => 'Contracts',
    'options' =>
    array(
      'filters' =>
      array(
        'contract_starting_date' =>
        array(),
        'contract_ending_date' =>
        array(
          'type' => 'TP_next_30_days',
        ),
        'status' =>
        array(
          0 => 'active',
        ),
        'date_of_signing' =>
        array(),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      //'title' => 'Wygasające umowy',
      'title' => 'Expiring contracts',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'employee_name',
        2 => 'status',
        3 => 'contract_type',
        4 => 'contract_starting_date',
        5 => 'contract_ending_date',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Contracts/Dashlets/ContractsDashlet/ContractsDashlet.php',
  ),
  'settlements_second_settlements_contracts_dashlet' =>
  array(
    'className' => 'ContractsDashlet',
    'module' => 'Contracts',
    'options' =>
    array(
      'filters' =>
      array(
        'contract_starting_date' =>
        array(),
        'contract_ending_date' =>
        array(),
        'status' =>
        array(
          0 => 'pending',
        ),
        'date_of_signing' =>
        array(),
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
      ),
      //'title' => 'Oczekujące umowy',
      'title' => 'Pending contracts',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'status',
        2 => 'contract_type',
        3 => 'contract_starting_date',
        4 => 'contract_ending_date',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Contracts/Dashlets/ContractsDashlet/ContractsDashlet.php',
  ),
  'settlements_second_delegations_dashlet' =>
  array(
    'className' => 'DelegationsDashlet',
    'module' => 'Delegations',
    'options' =>
    array(
      'filters' =>
      array(
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
        'start_date' =>
        array(
          'type' => 'TP_last_month',
        ),
        'end_date' =>
        array(),
      ),
      //'title' => 'Delegacje - poprzedni miesiąc',
      'title' => 'Delegations - previous month',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'assigned_user_name',
        2 => 'purpose',
        3 => 'total_expenses',
        4 => 'start_date',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Delegations/Dashlets/DelegationsDashlet/DelegationsDashlet.php',
  ),
  'settlements_delegations_dashlet' =>
  array(
    'className' => 'DelegationsDashlet',
    'module' => 'Delegations',
    'options' =>
    array(
      'filters' =>
      array(
        'date_entered' =>
        array(),
        'date_modified' =>
        array(),
        'start_date' =>
        array(
          'type' => 'TP_this_month',
        ),
        'end_date' =>
        array(),
      ),
      //'title' => 'Delegacje - aktualny miesiąc',
      'title' => 'Delegations - this month',
      'myItemsOnly' => false,
      'myFavorites' => false,
      'mySubordinates' => false,
      'displayRows' => '5',
      'displayColumns' =>
      array(
        0 => 'name',
        1 => 'assigned_user_name',
        2 => 'purpose',
        3 => 'total_expenses',
        4 => 'start_date',
      ),
      'autoRefresh' => '-1',
    ),
    'fileLocation' => 'modules/Delegations/Dashlets/DelegationsDashlet/DelegationsDashlet.php',
  ),
);
