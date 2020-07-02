<?php

class UsersApi
{
    public function isUserAllowedToCreate()
    {
        $test = ACLController::checkAccess('WorkSchedules', 'edit');
        return ACLController::checkAccess('WorkSchedules', 'edit') && ACLController::checkAccess('Meetings', 'edit') && ACLController::checkAccess('Calls', 'edit');
    }
}
