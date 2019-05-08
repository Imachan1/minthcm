<?php

require_once('include/MVC/Controller/SugarController.php');
require_once('include/database/DBManagerFactory.php');
require_once('modules/Administration/QuickRepairAndRebuild.php');
require_once('modules/PDFGenerator/config/config.php');
require_once 'modules/PDFGenerator/ButtonParser.php';

class PDFGeneratorController extends SugarController {

    public function action_repair() {
        $parser = new ButtonParser();
        $parser->rebuildAll();
        header('Location: index.php?module=Administration&action=index');
    }

    public function action_Popup() {
        $parser = $this->getPDFController();
        $parser->process();
    }

    public function action_Preview() {
        $parser = $this->getPDFController();
        $parser->process('PDFPreView', array('tmp_tpl' => $_REQUEST['temp_template']));
    }
    protected function getPDFController(){
        require_once 'modules/PDFGenerator/PDFController.php';
        $template_id = $_REQUEST['template'];
        $module_name = $_REQUEST['module_name'];
        $root_ids = array();
        if (!empty($_REQUEST['rec'])) {
            $root_ids = explode('|', $_REQUEST['rec']); //TODO
        } else if (!empty($_REQUEST['record'])) {
            $root_ids = explode('|', $_REQUEST['record']);
        }
        $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'FILE';
        $filename_regex = $_REQUEST['filename_regex'];

        return new PDFController($template_id, $module_name, $root_ids, $mode, $filename_regex);
    }
}
