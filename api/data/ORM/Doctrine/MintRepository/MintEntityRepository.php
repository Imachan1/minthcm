<?php

namespace MintHCM\Data\ORM\Doctrine\MintRepository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;
use MintHCM\Data\ORM\Doctrine\MintRepository\Traits\EntitiesListTrait;
use MintHCM\Data\ORM\Doctrine\MintRepository\Traits\LegacyActionsTrait;

class MintEntityRepository extends EntityRepository
{
    use LegacyActionsTrait;
    use EntitiesListTrait;

    public function getNewEntity()
    {
        $entityClass = $this->getClassName();
        return new $entityClass();
    }

    public function getEntityShortName(): string
    {
        $entityClass = $this->getClassName();
        return (new \ReflectionClass($entityClass))->getShortName();
    }

    /**
     * Save the entity 
     *
     * @param object $entity The entity to save
     * @param bool $should_flush Whether to flush changes to the database immediately
     * @return void
     */
    public function save($entity, bool $should_flush = false): void
    {
        if (! $entity instanceof MintEntity) {
            throw ORMInvalidArgumentException::invalidObject('MintEntityRepository#save()', $entity);
        }

        $this->_em->persist($entity);

        if ($entity->hasLegacyActions()) {
            $this->legacySave($entity);
            return;
        }
        if ($should_flush) {
            $this->_em->flush();
        }
    }

    /**
     * Delete the entity
     *
     * @param object $entity The entity to delete
     * @param bool $should_flush Whether to flush changes to the database immediately
     * @return void
     */
    public function delete($entity, bool $should_flush = false): void
    {
        if (! $entity instanceof MintEntity) {
            throw ORMInvalidArgumentException::invalidObject('MintEntityRepository#delete()', $entity);
        }

        if ($entity->hasLegacyActions()) {
            $this->legacyDelete($entity);
            return;
        }

        $this->_em->remove($entity);
        if ($should_flush) {
            $this->_em->flush();
        }
    }
}
