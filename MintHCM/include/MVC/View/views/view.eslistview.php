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
require_once('include/MVC/View/SugarView.php');

class ViewEslistView extends SugarView
{
    /**
     * @var string $type
     */
    public $type = 'ESList';

    /**
     * @var SugarBean
     */
    public $seed;

    public $ss; // smarty object

    /**
     * @var array $ESListViewDefs
     */
    public $ESListViewDefs;

    /**
     * ESListView constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        if (!$this->bean || !$this->bean->ACLAccess('list')) {
            ACLController::displayNoAccess();
        } else {
            $this->prepareESListView();
            $this->ss = new Sugar_Smarty();
            $this->ss->assign('config', $this->prepareConfig());
            $this->ss->assign('defs', $this->prepareDefs());
            $this->ss->assign('module', $this->bean->module_name);
            $this->ss->assign('preferences', $this->prepareUserPreferences());
            echo $this->ss->fetch($this->getTplFile());
        }
    }

    protected function prepareESListView()
    {
        if (!isset($this->bean->module_name)) {
            LoggerManager::getLogger()->fatal('Undefined module for eslist view');
            return false;
        }
        $metadataFile = $this->getMetaDataFile();
        if (!file_exists($metadataFile)) {
            sugar_die(sprintf($GLOBALS['app_strings']['LBL_NO_ACTION'], $this->do_action));
        }
        require($metadataFile);
        $this->ESListViewDefs = $ESListViewDefs;

        require('include/ESListView/eslist.map.php');
        $this->eslistmap = $eslistmap;
        $mappings = json_decode(file_get_contents('http://10.8.0.103:9205/ecc3aab136efd8f791a90c11b95afad8_shared/_mappings/' . $this->bean->module_name), true);
        $this->mappings = array_values($mappings)[0]['mappings'][$this->bean->module_name]['properties'];
    }

    protected function prepareConfig()
    {
        $config = json_decode(file_get_contents('include/ESListView/config-mint/eslist.config.json'), true);
        $variables = json_decode(file_get_contents('include/ESListView/config-mint/eslist.variables.json'), true);
        $theme = json_decode(file_get_contents('include/ESListView/config-mint/eslist.theme.json'), true);
        foreach ($theme as $property => $objects) {
            foreach ($objects as $object => $value) {
                $theme[$property][$object] = $variables[$property][$value];
            }
        }
        return json_encode([
            'config' => $config,
            'theme' => $theme,
        ]);
    }

    protected function prepareDefs()
    {
        return json_encode([
            'columns' => $this->prepareColumnsDefs(),
            'search' => $this->prepareSearchDefs(),
        ]);
    }

    protected function prepareColumnsDefs()
    {
        global $mod_strings;
        $columns = $this->ESListViewDefs[$this->module]['columns'];
        if (empty($columns)) {
            LoggerManager::getLogger()->fatal('Columns for ESList View are not defined');
            return false;
        }
        $columns = array_change_key_case($columns, CASE_LOWER);
        foreach ($columns as $field => $defs) {
            $columns[$field]['name'] = $defs['name'] ?? $field;
            $columns[$field]['key'] = $defs['key'] ?? $this->eslistmap[$field];
            if (empty($columns[$field]['key'])) {
                $columns[$field]['key'] = $field;
                if ($this->mappings[$field] && !in_array($this->mappings[$field]['type'], ['date', 'boolean'])) {
                    $columns[$field]['key'] .= '.keyword';
                }
            }
            if (empty($this->bean->field_name_map[$field])) {
                continue;
            }
            $field_defs = $this->bean->field_name_map[$field];
            $columns[$field]['type'] = $defs['type'] ?? $field_defs['type'];
            $columns[$field]['options'] = $field_defs['options'];
            $label = $defs['label'] ?? $field_defs['label'] ?? $field_defs['vname'];
            $columns[$field]['label'] = $mod_strings[$label] ?? $label;
        }
        return $columns;
    }

    protected function prepareSearchDefs()
    {
        global $mod_strings;
        $search = $this->ESListViewDefs[$this->module]['search'];
        if (empty($search)) {
            return false;
        }
        $search = array_change_key_case($search, CASE_LOWER);
        foreach ($search as $field => $defs) {
            $search[$field]['name'] = $defs['name'] ?? $field;
            $search[$field]['key'] = $defs['key'] ?? $this->eslistmap[$field];
            if (empty($search[$field]['key'])) {
                $search[$field]['key'] = $field;
                if ($this->mappings[$field] && !in_array($this->mappings[$field]['type'], ['date', 'boolean'])) {
                    $search[$field]['key'] .= '.keyword';
                }
            }
            if (empty($this->bean->field_name_map[$field])) {
                continue;
            }
            $field_defs = $this->bean->field_name_map[$field];
            $search[$field]['type'] = $defs['type'] ?? $field_defs['type'];
            $search[$field]['options'] = $field_defs['options'];
            $label = $defs['label'] ?? $field_defs['label'] ?? $field_defs['vname'];
            $search[$field]['label'] = $mod_strings[$label] ?? $label;
        }
        return $search;
    }

    protected function prepareUserPreferences()
    {
        global $current_user;
        $preferences = (new UserPreference($current_user))->getPreference($this->bean->module_name, 'eslist');
        return json_encode($preferences);
    }

    protected function getTplFile()
    {
        $module_path = 'modules/'.$this->module.'/include/ESListView/ESListViewGeneric.tpl';
        if (file_exists('custom/'.$module_path)) {
            return 'custom/'.$module_path;
        } else if (file_exists($module_path)) {
            return $module_path;
        }
        $include_path = 'include/ESListView/ESListViewGeneric.tpl';
        if (file_exists('custom/'.$include_path)) {
            return 'custom/'.$include_path;
        } else if (file_exists($include_path)) {
            return $include_path;
        }
        $GLOBALS['log']->fatal("ESList TPL file does not exist");
        return '';
    }
}
