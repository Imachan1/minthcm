<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/Administration/controller.php';

class CustomAdministrationController extends AdministrationController
{

    public function action_DLNCSettings()
    {
        $this->view = "dlncsettings";
    }

    public function action_save_dlncsettings_config()
    {

        $lock_config_var = ['dlnc_flag' => 0];
        if (isset($_REQUEST['save_config']) && $_REQUEST['save_config'] != '') {
            $lock_config_var['dlnc_flag'] = $_REQUEST['dlnc_flag'];
        }
        $config = new Administration();
        $config->retrieveSettings('DLNC');
        $config->saveSetting('DLNC', 'flag', $lock_config_var['dlnc_flag']);

        SugarApplication::redirect("index.php?module=Administration&action=index");
    }
    //

}
