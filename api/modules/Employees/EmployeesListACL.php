<?php

namespace MintHCM\Modules\Employees;

use MintHCM\Lib\Search\ElasticSearch\BaseListACL;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;

class EmployeesListACL extends BaseListACL
{
    protected function getFiltersByOwner(string $user_id): array
    {
        $filters = parent::getFiltersByOwner($user_id);
        $bean = BeanFactory::newBean($this->module);
        $aclController = new LegacyConnector('ACLController');
        if (!$bean->bean_implements('ACL') || (
            !$aclController::requireOwner($bean->module_dir, 'list')
            && !$aclController::requireSecurityGroup($bean->module_dir, 'list')
        )) {
            return [];
        }
        $filters[] = [
            'term' => [ '_id' => $user_id ],
        ];
        return $filters;
    }
}
