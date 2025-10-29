<?php

namespace MintHCM\Data\ORM\Doctrine\MintEntity\Traits;

trait LegacyActions
{
    use LegacyBase;
    
    public function checkLegacyAccess(string $view, $is_owner = 'not_set', $in_group = 'not_set'): bool
    {
        $bean = $this->getMintBean();
        try {
            if ($bean->bean_implements('ACL')) {
                return $bean->ACLAccess($view, $is_owner, $in_group) === true;
            }
        } catch (\Exception $e) {
            return true;
        }
        
        return true;
    }

    public function legacySave(bool $check_notify = false): bool
    {
        try {
            $this->fillLegacyBean();
            $bean = $this->getMintBean();
            return !empty($bean->save($check_notify)) ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function legacyDelete(): bool
    {
        try {
            $this->fillLegacyBean();
            $bean = $this->getMintBean();
            $bean->mark_deleted($bean->id);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}