<?php

require_once('modules/PDFGenerator/lib/BasePDFGenerator.php');

class PDFPreView extends BasePDFGenerator {
    
    
    protected function initiate($template_id = null, $module_name = null, $params = null) {
        if (!$params['tmp_tpl']) {
             $this->pdftemplate = BeanFactory::getBean("PDFTemplates", $template_id);
        } elseif ($template_id && file_exists(PDFTEMPLATES_PATH_TEMP . 'template-' . $template_id)) {
            $this->pdftemplate = BeanFactory::getBean("PDFTemplates", $template_id);
            $this->pdftemplate->template = sugar_file_get_contents(PDFTEMPLATES_PATH_TEMP . 'template-' . $template_id);
            $this->pdftemplate->relatedmodule = $module_name;
        }       
        $this->page_orientation = empty($this->pdftemplate->orientation) ? 'P' : $this->pdftemplate->orientation;
    }
    public function Output($root_ids = null, $filename_regex = null, $mode = 'I') {
        parent::Output(array('FAKE_ID'));
    }
    public function preview($module_name, $template_id, $use_tmp_template) {
        if (!$use_tmp_template) {
             $this->pdftemplate = BeanFactory::getBean("PDFTemplates", $template_id);
        } elseif ($template_id && file_exists(PDFTEMPLATES_PATH_TEMP . 'template-' . $template_id)) {
            $this->pdftemplate = BeanFactory::getBean("PDFTemplates", $template_id);
            $this->pdftemplate->template = sugar_file_get_contents(PDFTEMPLATES_PATH_TEMP . 'template-' . $template_id);
            $this->pdftemplate->relatedmodule = $module_name;
        }
       
        $this->page_orientation = empty($this->pdftemplate->orientation) ? 'P' : $this->pdftemplate->orientation;
        

        $this->Output(array('FAKE_ID'));
    }
    protected function getBean($module, $id) {
        return $this->prepareDemoData($module);
    }
    protected function getNewBlockForBlockLink($block, $parent_bean, $relation_field_name, $depth, $block_after) {
        
        $module_name = $parent_bean->$relation_field_name->getRelatedModuleName();
        $new_block = '';
        $counter = 0;
        for ($preview = 0; $preview < 3; $preview++) {

            $newbean = $this->prepareDemoData($module_name);
            $newbean->load_relationships();
            $new_block .= $this->parse($block_after->innertext, $newbean, $block_after->relationship, ++$counter, $depth + 1);
        }
        return $this->setNewBlockForBlockLink($block, $counter, $new_block);
    }

    protected function prepareDemoData($module_name) {
        $bean = BeanFactory::newBean($module_name);
        foreach ($bean->field_defs as $field_name) {
            $bean->{$field_name['name']} = $this->getValueByType($field_name, $module_name);
        }
        return $bean;
    }

    protected function getValueByType($field_name, $rel_module_long) {
        global $timedate;
        switch ($field_name['type']) {
            case 'varchar':
                $return = $this->prepareVarchar($field_name, $rel_module_long);
                break;
            case 'name':
            case 'relate':
            case 'bool':
                $return = translate($field_name['vname'], $rel_module_long);
                break;
            case 'text':
                $return = translate('LBL_PREVIEW_TEXT', 'PDFGenerator');
                break;
            case 'int':
            case 'decimal':
            case 'float':
                $return = $this->prepareNumber($field_name);
                break;
            case 'date':
            case 'datetime':
                $return = $timedate->to_display_date(date("Y-m-d"));
                break;
            case 'currency':
                $return = $this->prepareCurrency();
                break;
            case 'enum':
                $return = $this->prepareEnum($field_name);
                break;
            case 'multienum':
                $return = $this->prepareMultienum($field_name);
                break;
        }
        return $return;
    }

    protected function getFieldValueForRelated($name, $bean, $r) {
        $value = '';
        $name_tmp = substr($name, strpos($name, '__') + 2);
        $link_defs = $bean->get_linked_fields();
        $field = $link_defs[$r[0]];
        $module_name = $field['module'];
        $rel_bean = $this->prepareDemoData($module_name);
        if (!empty($rel_bean)) {
            $value = $this->getFieldValue($name_tmp, $rel_bean);
        }
        return $value;
    }

    protected function prepareVarchar($field_name, $module) {
        if (strpos($field_name['name'], 'street') !== false) {
            $return = translate('LBL_PREVIEW_STREET', 'PDFGenerator');
        } elseif (strpos($field_name['name'], 'country') !== false) {
            $return = translate('LBL_PREVIEW_COUNTRY', 'PDFGenerator');
        } elseif (strpos($field_name['name'], 'postalcode') !== false) {
            $return = translate('LBL_PREVIEW_POSTALCODE', 'PDFGenerator');
        } elseif (strpos($field_name['name'], 'state') !== false) {
            $return = translate('LBL_PREVIEW_STATE', 'PDFGenerator');
        } elseif (strpos($field_name['name'], 'city') !== false) {
            $return = translate('LBL_PREVIEW_CITY', 'PDFGenerator');
        } else {
            $return = translate($field_name['name'], $module);
        }
        return $return;
    }

    protected function prepareCurrency() {
        global $sugar_config;
        return number_format((double) (rand(100, 50000) + (rand(1, 99) / 100)), 2, $sugar_config['default_decimal_seperator'], $sugar_config['default_number_grouping_seperator']);
    }

    protected function prepareNumber($field_name) {
        global $sugar_config;
        if ($field_name['disable_format_number'] == '1') {
            $return = rand(1, 100);
        } else {
            $p = (isset($field_name['precision'])) ? $field_name['precision'] : 2;
            $v = ($field_name['type'] == 'float') ? (rand(1, 100) + (rand(1, 99) / 100)) : rand(1, 100);

            $return = number_format((double) $v, $p, $sugar_config['default_decimal_seperator'], $sugar_config['default_number_grouping_seperator']);
        }
        return $return;
    }

    protected function prepareMultienum($field_name) {
        global $app_list_strings;
        $temp = '';
        if (isset($field_name['options']) && isset($app_list_strings[$field_name['options']])) {
            $list_count = count($app_list_strings[$field_name['options']]);
            if ($list_count > 2) {
                $loop = 3;
            } elseif ($list_count > 1) {
                $loop = 2;
            } else {
                $loop = 1;
            }
            foreach ((array) $app_list_strings[$field_name['options']] as $list_key => $list_value) {
                $temp .= '^' . $list_key . '^';
                $loop--;
                if (!$loop) {
                    break;
                } else {
                    $temp .= ',';
                }
            }
        }
        return $temp;
    }

    protected function prepareEnum($field_name) {
        global $app_list_strings;
        $return = '';
        if ($field_name['name'] == 'vat' && isset($field_name['function']['name']) && isset($field_name['function']['include'])) {
            include_once $field_name['function']['include'];
            $taxRates = $field_name['function']['name']();
            $return = key(array_slice($taxRates, rand(0, count($taxRates)), 1));
        } else if (isset($field_name['options']) && isset($app_list_strings[$field_name['options']])) {
            $list_count = count($app_list_strings[$field_name['options']]);
            $loop = 0;
            foreach ((array) $app_list_strings[$field_name['options']] as $list_key => $list_value) {
                if ($list_count > 1 && $loop == 1) {
                    $return = $list_key;
                    break;
                } elseif ($list_count == 1) {
                    $return = $list_key;
                    break;
                } elseif ($loop > 0) {
                    $return = $field_name['options'];
                    break;
                }
                $loop++;
            }
        }
        return $return;
    }

}
