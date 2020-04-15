<?php

class PositionsApi
{

    public function getOnboardingOffboardingName($args)
    {
        $data = [];
        $employee = BeanFactory::getBean('Users', $args['employee_id']);
        $position_id = $employee->position_id;
        $focus = BeanFactory::getBean('Positions', $position_id);
        if ($args['boarding'] == 'Offboarding') {
            $data['parent_id'] = $focus->offboardingtemplate_id;
            $data['parent_name'] = $focus->offboardingtemplate_name;
        } elseif ($args['boarding'] == 'Onboarding') {
            $data['parent_id'] = $focus->onboardingtemplate_id;
            $data['parent_name'] = $focus->onboardingtemplate_name;
        }

        return $data;
    }

}
