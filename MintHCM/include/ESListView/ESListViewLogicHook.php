<?php

require_once 'include/ESListView/ESListViewController.php';

class ESListViewLogicHook
{

    // public function beforeSave($bean)
    // {
    //     $KVC = (new ESListViewController($bean));
    //     if(!isset($bean->from_ESList) && $KVC->shouldReorder()) {
    //         $KVC->setOrderFieldNull();
    //     }
    // }

    // public function afterSave($bean)
    // {
    //     $KVC = (new ESListViewController($bean));
    //     if($KVC->shouldReorder()) {
    //         $KVC->reorder();
    //     }
    // }

    // public function afterDelete($bean)
    // {
    //     (new ESListViewController($bean))->reorder();
    // }
}
