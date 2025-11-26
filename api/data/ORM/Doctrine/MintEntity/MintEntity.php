<?php

namespace MintHCM\Data\ORM\Doctrine\MintEntity;

abstract class MintEntity
{
    use Traits\LegacyActions;

    public const LEGACY_ACTIONS = true;

    public function hasLegacyActions(): bool
    {
        return static::LEGACY_ACTIONS;
    }

    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    public function getModuleName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function hasAccess(string $view, bool $is_owner = false, bool $in_group = false): bool
    {
        return $this->checkLegacyAccess($view, $is_owner ?: 'not_set', $in_group ?: 'not_set');
    }
}   