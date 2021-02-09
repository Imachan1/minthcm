<?php

function post_install()
{
    repairAndRebuild();

    require_once 'custom/modules/Administration/AddLastNextContactPanel.php';
    
    generateSubscriptionRecord();
    
}

function repairAndRebuild()
{
    $autoexecute = true;
    $show_output = false;
    require_once "modules/Administration/QuickRepairAndRebuild.php";
    $repair = new RepairAndClear();
    $repair->repairAndClearAll(array('clearAll'), array(translate('LBL_ALL_MODULES')), $autoexecute, $show_output);
}

function generateSubscriptionRecord(){        
    require_once 'custom/include/evSubscription/DateLastNextContactSubscription.php';
    DateLastNextContactSubscription::validateKey();
}