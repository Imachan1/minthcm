<?php

namespace MintHCM\Data\ORM\Doctrine\MintEntity;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Data\MintBean;
use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;
use MintHCM\Data\ORM\Doctrine\MintTypes\MintTypeManager;

class MintEntitySerializer
{
    protected EntityManagerInterface $entity_manager;

    public function __construct(EntityManagerInterface $entity_manager)
    {
        $this->entity_manager = $entity_manager;
    }

    /**
     * Serialize a MintEntity to an array.
     */
    public function serialize(MintEntity $entity): array
    {
        $data = [];

        if (!empty($entity->getId())) {
            /** @var MintEntityRepository */
            $repository = $this->entity_manager->getRepository($entity->getModuleName());
            $repository->findWithRelatedFields($entity->getId()); // Ensure related fields are loaded
        }


        /** @var MintBean */
        $legacy_bean = $entity->getMintBean(false);
        if (empty($legacy_bean) || empty($legacy_bean->field_defs)) {
            $GLOBALS['log']->fatal('Legacy bean is not available for entity ' . get_class($entity));
            throw new \RuntimeException('Legacy bean is not available for entity ' . get_class($entity));
        }

        foreach ($legacy_bean->field_defs as $field_name => $field_def) {
            if (!property_exists($entity, $field_name)) {
                if ($field_def['type'] == 'relate') {
                    $data[$field_name] = $this->getRelateFieldValue($entity, $field_def);
                }
                if ($field_def['type'] == 'parent') {
                    $data[$field_name] = $this->getParentNameFieldValue($entity, $field_def);
                }
                continue;
            }

            if ($this->isRelationProperty($entity, $field_name)) {
                continue; // Skip relation properties for now
            }

            $data[$field_name] = $this->getConvertedValue($entity, $field_name, $entity->$field_name);
        }

        return $data;
    }

    protected function getParentNameFieldValue(MintEntity $entity, array $field_def)
    {
        $parent_type_field = $field_def['type_name'];
        $parent_id_field = $field_def['id_name'];

        if (!property_exists($entity, $parent_type_field) || !property_exists($entity, $parent_id_field)) {
            return null;
        }

        $parent_type = $entity->$parent_type_field;
        $parent_id = $entity->$parent_id_field;

        if (empty($parent_type) || empty($parent_id)) {
            return null;
        }

        /** @var MintEntityRepository */
        $parent_repository = $this->entity_manager->getRepository($parent_type);
        $parent_entity = $parent_repository->find($parent_id);
        if ($parent_entity instanceof MintEntity) {
            return $this->getConvertedValue($parent_entity, 'name', $parent_entity->getName() ?? null);
        }

        return null;
    }

    protected function getRelateFieldValue(MintEntity $entity, array $field_def)
    {
        $link_field = $field_def['link'];
        $relate_value = $field_def['rname'];

        if (!property_exists($entity, $link_field)) {
            return null;
        }

        $related_entity = $entity->$link_field;
        if ($related_entity instanceof MintEntity) {
            $value = in_array($relate_value, ['full_name', 'name']) ? $related_entity->getName() : ($related_entity->$relate_value ?: null);
            return $this->getConvertedValue($related_entity, $relate_value, $value);
        }

        return null;
    }

    protected function getConvertedValue(MintEntity $entity, string $property_name, $value)
    {
        $type_name = $entity->gerPropertyTypeName($property_name);
        $type = $this->getType($type_name);
        if ($type === null) {
            return $value;
        }

        if ($type instanceof \MintHCM\Data\ORM\Doctrine\MintTypes\MintTypeInterface) {
            return $type->convertToExternalFormat($value);
        }

        return $type->convertToDatabaseValue($value, $this->entity_manager->getConnection()->getDatabasePlatform());
    }

    protected function getType(?string $type_name): ?Type
    {
        if ($type_name === null) {
            return null;
        }

        return MintTypeManager::getType($type_name);
    }

    protected function isRelationProperty(MintEntity $entity, string $property_name): bool
    {
        $meta = $this->entity_manager->getClassMetadata($entity::class);
        return $meta->hasAssociation($property_name);
    }
}
