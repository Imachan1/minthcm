<?php

require_once 'include/ESListView/BaseListACL.php';

class SalaryRangesListACL extends BaseListACL
{
    protected function getFiltersByOwner(string $user_id): array
    {
        global $current_user;
        $filters = parent::getFiltersByOwner($user_id);
        
        $controller_career_path = ControllerFactory::getController('CareerPaths');
        $positions_ids = $controller_career_path::getRelatedPositionIds($current_user->position_id, true);
        $filters[] = [
            'terms' => [ 'position_id.keyword' => $positions_ids ],
        ];
        return $filters;
    }
}
