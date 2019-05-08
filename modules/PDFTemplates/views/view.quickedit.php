<?php

require_once 'include/MVC/View/views/view.quickedit.php';

class PDFTemplatesViewQuickedit extends ViewQuickedit {

    function display() {
        // $this->defaultButtons=$this->changeButtons;
        $no_defs_js = '<script>DCMenu.hideQEPanel();SUGAR.ajaxUI.loadContent("index.php?return_module=' . $this->bean->module_dir . '&module=' . $this->bean->module_dir . '&action=EditView&record=' . $this->bean->id . '")</script>';
        echo json_encode(array('scriptOnly' => $no_defs_js));
        return;
    }

}
