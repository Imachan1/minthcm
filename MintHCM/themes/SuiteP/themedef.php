<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2019 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$themedef = array(
    'name' => 'MintHCM',
    'description' => 'MintHCM Responsive Theme',
    'version' => array(
        'regex_matches' => array('.+'),
    ),
    'group_tabs' => true,
    'classic' => true,
    'configurable' => true,
    'config_options' => array(
        'display_sidebar' => array(
            'vname' => 'LBL_DISPLAY_SIDEBAR',
            'type' => 'bool',
            'default' => true,
        ),
        'sub_themes' => array(
            'vname' => 'LBL_SUBTHEME_OPTIONS',
            'type' => 'select',
            'default' => 'Mint',
        ),
    ),
    'fa_module_icons' => array(
        'Candidates' => 'fa-address-book',
        'Candidatures' => 'fa-address-card',
        'Positions' => 'fa-list',
        'Recruitments' => 'fa-user-plus',
        'Onboardings' => 'fa-sign-in-alt',
        'Offboardings' => 'fa-sign-out-alt',
        'OffboardingTemplates' => 'fa-sign-out-alt',
        'OnboardingTemplates' => 'fa-sign-in-alt',
        'ExitInterviews' => 'fa-user-times',
        'Delegations' => 'fa-plane',
        'WorkSchedules' => 'fa-business-time',
        'Transportations' => 'fa-car',
        'Costs' => 'fa-dollar-sign',
        'Reservations' => 'fa-calendar-check',
        'Resources' => 'fa-people-carry',
        'Trainings' => 'fa-user-graduate',
        'EmployeeRoles' => 'fa-user-shield',
        'OrganizationalUnits' => 'fa-users',
        'News' => 'fa-bell',
        'Ideas' => 'fa-lightbulb',
        'Conclusions' => 'fa-hand-point-left',
        'Problems' => 'fa-exclamation-triangle',
        'Responsibilities' => 'fa-list',
        'Activities' => 'fa-hand-point-right',
        'Competencies' => 'fa-list',
        'Contracts' => 'fa-file-signature',
        'TermsOfEmployment' => 'fa-list',
        'PeriodsOfEmployment' => 'fa-calendar',
        'Benefits' => 'fa-umbrella-beach',
        'Applications' => 'fa-user-plus',
        'Certificates' => 'fa-scroll',
        'Appraisals' => 'fa-door-closed',
        'Goals' => 'fa-bullseye',
    ),
);

if (!empty($app_strings['LBL_SUBTHEMES'])) {
    // if statement removes the php notice
    $themedef['config_options']['sub_themes']['options'] = array(
        $app_strings['LBL_SUBTHEMES'] => array(
            'Mint' => 'Mint',
        ),
    );
    $themedef['config_options']['sub_themes']['default'] = 'Mint';
}
