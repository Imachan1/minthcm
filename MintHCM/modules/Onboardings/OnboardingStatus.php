<?php

class OnboardingStatus
{
    public function closeIfActivitiesAreHeld($boarding)
    {
        if ($this->areTrainingsHeld($boarding)
            && $this->areExitInterviewsHeld($boarding)
            && $this->areTasksHeld($boarding)) {
            $this->close($boarding);

        }

    }
    protected function areTrainingsHeld($boarding)
    {
        global $db;
        $boarding_id = $boarding->id;
        $sql = "SELECT id, status FROM trainings WHERE parent_id='{$boarding_id}'";
        $result = $db->query($sql);
        while (($row = $db->fetchByAssoc($result)) != null) {
            if ($row['status'] != 'held') {
                return false;
            }
        }
        return true;
    }

    protected function areExitInterviewsHeld($boarding)
    {
        global $db;
        $boarding_id = $boarding->id;
        $sql = "SELECT id, status FROM exitinterviews WHERE offboarding_id='{$boarding_id}'";
        $result = $db->query($sql);
        while (($row = $db->fetchByAssoc($result)) != null) {
            if ($row['status'] != 'held') {
                return false;
            }
        }
        return true;
    }

    protected function areTasksHeld($boarding)
    {
        global $db;
        $boarding_id = $boarding->id;
        $sql = "SELECT id, status FROM tasks WHERE parent_id='{$boarding_id}'";
        $result = $db->query($sql);
        while (($row = $db->fetchByAssoc($result)) != null) {
            if ($row['status'] != 'Completed') {
                return false;
            }
        }
        return true;
    }

    protected function close($boarding)
    {
        $boarding->status = 'Held';
        $boarding->save();
    }
}
