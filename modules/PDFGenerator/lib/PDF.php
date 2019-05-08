<?php

require_once('modules/PDFGenerator/lib/BasePDFGenerator.php');

class PDF extends BasePDFGenerator {

    protected function getNewBlockForBlockLink($block, $parent_bean, $relation_field_name, $depth, $block_after) {
        $module_name = $parent_bean->$relation_field_name->getRelatedModuleName();
        $bean = BeanFactory::newBean($module_name);
        $table = $bean->getTableName();
        $q = $parent_bean->{$relation_field_name}->getQuery();
        $query = "SELECT id FROM {$table} WHERE id IN({$q})";
        //check ordering
        if (isset($block->orderby)) {

            if ($bean->getFieldDefinition($block->orderby)) {
                $dir = $this->getBlockDir($block);
                $query .= "ORDER BY {$block->orderby} {$dir}";
            }
        }
        $result = $parent_bean->db->query($query);
        $count = $parent_bean->db->getAffectedRowCount($result);
        $new_block = '';
        $counter = 0;
        if ($count > 0) {
            while (($row = $parent_bean->db->fetchByAssoc($result) ) != null) {
                $new_block .= $this->setNewBlockLink($module_name, $row['id'], $depth, ++$counter, $block_after, $parent_bean);
            }
        }
        return $this->setNewBlockForBlockLink($block, $counter, $new_block);
    }

    

    function setNewBlockLink($module_name, $bean_id, $depth, $counter, $block_after, $parent_bean = null) {
        $newbean = $this->getBean($module_name, $bean_id, $parent_bean);
        return $this->parse($block_after->innertext, $newbean, $block_after->relationship, $counter, $depth + 1);
    }

    function getBlockDir($block) {
        if (isset($block->dir) && in_array(strtoupper($block->dir), array("ASC", "DESC"))) {
            $dir = $block->dir;
        } else {
            $dir = "ASC";
        }
        return $dir;
    }

}
