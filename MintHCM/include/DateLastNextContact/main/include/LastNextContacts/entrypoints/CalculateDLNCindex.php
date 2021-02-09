<?php
require_once 'custom/include/evSubscription/DateLastNextContactSubscription.php';
global $current_user;
if (!DateLastNextContactSubscription::validateKey()) {
    echo "Warning: eVolpe Subscription for Last Next Contact is invalid or inactive, check its status in the Subscription Management section.";
    return;
}
if (!is_admin($current_user)) {
    echo "Error: Access denied.";
    return;
}

global $current_language;
$smarty = new Sugar_Smarty();
$smarty->assign('lang', return_module_language($current_language, 'Administration'));
echo $smarty->fetch('custom/include/LastNextContacts/entrypoints/CalculateDLNC.tpl');
