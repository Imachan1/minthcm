<?php

namespace MintHCM\Modules\Comments\AccessChecker;

class AccessChecker
{
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function get()
    {
        return [
            'add' => $this->hasAddAccess(),
            'pin' => $this->hasPinAccess(),
        ];
    }

    protected function hasAddAccess()
    {
        return true;
    }

    protected function hasPinAccess()
    {
        global $current_user;
        return $current_user->isAdmin();
    }
}
