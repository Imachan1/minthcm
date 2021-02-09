<?php

use Sugarcrm\Sugarcrm\Security\Csrf\CsrfAuthenticator;

require_once 'include/MVC/View/views/view.edit.php';

class AdministrationViewDlncsettings extends ViewEdit
{

    public $ev;
    //public $type = 'dlncsettings';
    public $useForSubpanel = false; //boolean variable to determine whether view can be used for subpanel creates
    public $useModuleQuickCreateTemplate = false; //boolean variable to determine whether or not SubpanelQuickCreate has a separate display function
    public $showTitle = true;

    // public function AdministrationSynchronization()
    // {
    //     parent::CustomViewEdit();
    // }

    public function _getModuleTitleParams($browserTitle = false)
    {
        $params = parent::_getModuleTitleListParam($browserTitle);
        $action = strtolower($this->action);
        if ($action == $this->type . '_config') {
            $params[] = $GLOBALS['mod_strings']['LBL_' . strtoupper($action)];
        }
        return $params;
    }

    public function preDisplay()
    {
        $metadataFile = 'modules/' . $this->module . '/metadata/dlncsettingsdefs.php';
        $config = new Administration();
        $this->ev = new EditView();
        $this->ev->view = "dlncsettings";
        $this->ev->ss = &$this->ss;
        $config->retrieveSettings('DLNC');
        $this->ev->ss->assign('DLNC_flag', $config->settings['DLNC_flag']);
        $csrf = CsrfAuthenticator::getInstance();
        $this->ev->ss->assign('csrf_token', $csrf->getFormToken());

        $this->ev->setup($this->module, $this->bean, $metadataFile, SugarAutoLoader::existingCustomOne('include/EditView/EditView.tpl'));
    }

    public function display()
    {
        $this->ev->process();
        if ($this->ev->isDuplicate) {
            foreach ($this->ev->fieldDefs as $name => $defs) {
                if (!empty($defs['auto_increment'])) {
                    $this->ev->fieldDefs[$name]['value'] = '';
                }
            }
        }
        echo $this->ev->display($this->showTitle);
    }

}
