<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/EmployeeRoles/EmployeeRoles.php');

class EmployeeRolesDashlet extends DashletGeneric {
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
        require('modules/EmployeeRoles/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_HOMEPAGE_TITLE', 'EmployeeRoles');
        }

        $this->searchFields = $dashletData['EmployeeRolesDashlet']['searchFields'];
        $this->columns = $dashletData['EmployeeRolesDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('EmployeeRoles');
    }
}
