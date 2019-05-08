<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Benefits/Benefits.php');

class BenefitsDashlet extends DashletGeneric {
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
        require('modules/Benefits/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_HOMEPAGE_TITLE', 'Benefits');
        }

        $this->searchFields = $dashletData['BenefitsDashlet']['searchFields'];
        $this->columns = $dashletData['BenefitsDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('Benefits'); 
    }
}
