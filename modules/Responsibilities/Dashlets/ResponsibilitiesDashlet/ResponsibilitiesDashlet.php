<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Responsibilities/Responsibilities.php');

class ResponsibilitiesDashlet extends DashletGeneric {
    public function __construct($id, $def = null)
    {
        require('modules/Responsibilities/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_HOMEPAGE_TITLE', 'Responsibilities');
        }

        $this->searchFields = $dashletData['ResponsibilitiesDashlet']['searchFields'];
        $this->columns = $dashletData['ResponsibilitiesDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('Responsibilities'); 
    }
}
