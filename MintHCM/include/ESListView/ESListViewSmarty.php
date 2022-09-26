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

class ESListViewSmarty
{
    public $columns;
    public $search;
    public $ss; // the smarty object
    public $tpl;
    public $moduleString;
    public $seed;
    public $templateMeta = array();

    /**
     * Constructor, Smarty object immediately available after
     *
     */
    public function __construct()
    {
        $this->ss = new Sugar_Smarty();
    }

    public function setup($seed, $file)
    {

        $this->seed = $seed;
        $this->process($file);

        return true;
    }

    /**
     * Processes the request. Calls ListViewData process. Also assigns all lang strings, export links,
     * This is called from ListViewDisplay
     *
     * @param file $file Template file to use
     *
     */
    function process($file)
    {
        $this->tpl = $file;
        $this->ss->assign('module', $this->seed->module_name);
        $this->assignUserPreferences();
    }

    /**
     * Displays the xtpl, either echo or returning the contents
     *
     */
    function display()
    {
        $this->ss->assign('json', json_encode($this->prepareData()));
        return $this->ss->fetch($this->tpl);
    }

    protected function prepareData()
    {
        return [
            'columns' => $this->columns,
            'search' => $this->search,
        ];
    }

    protected function assignUserPreferences()
    {
        global $current_user;
        $test = [
            'saved_filters' => [
                [
                    'name' => 'Mój filtr 1',
                    'filters' => [
                        [
                            'field' => 'date_end',
                            'op' => 'next_30_days'
                        ]
                    ]
                ],
                [
                    'name' => 'Ostatnie rozmowy',
                    'filters' => [
                        [
                            'field' => 'date_entered',
                            'op' => 'last_7_days',
                        ],
                        [
                            'field' => 'date_start',
                            'op' => 'last_30_days',
                        ]
                    ]
                ],
                [
                    'name' => 'Rozmowy wychodzące',
                    'filters' => [
                        [
                            'field' => 'status',
                            'op' => 'equals'
                        ]
                    ]
                ],
            ]
        ];
        (new UserPreference($current_user))->setPreference($this->seed->module_name, $test, 'eslist');
        $preferences = (new UserPreference($current_user))->getPreference($this->seed->module_name, 'eslist');
        $this->ss->assign('preferences', json_encode($preferences));
    }
}