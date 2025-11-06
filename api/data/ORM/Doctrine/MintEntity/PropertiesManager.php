<?php 

namespace MintHCM\Data\ORM\Doctrine\MintEntity;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Data\ORM\Doctrine\MintTypes\MintTypeManager;
use MintHCM\Utils\LegacyConnector;

class PropertiesManager
{
    protected EntityManagerInterface $entity_manager;
    protected MintEntity $entity;

    public function __construct(EntityManagerInterface $entity_manager, MintEntity $entity)
    {
        $this->entity_manager = $entity_manager;
        $this->entity = $entity;
    }

    public function getConvertedToPHPValue(string $property, $value)
    {
        $type_name = $this->getPropertyType($property);
        if ($type_name === null) {
            return $value;
        }

        if (!MintTypeManager::hasType($type_name)) {
            return $value;
        }

        $mint_type = MintTypeManager::getType($type_name);
        if ($mint_type === null) {
            return $value;
        }

        return $mint_type->convertToPHPValue($value, $this->entity_manager->getConnection()->getDatabasePlatform());
    }

    public function getPropertyType(string $property): ?string
    {
        if (!property_exists($this->entity, $property)) {
            return null;
        }

        $entity_manager = $this->entity_manager;
        $meta = $entity_manager->getClassMetadata($this->entity::class);
        if ($meta->hasField($property)) {
            return $meta->getTypeOfField($property);
        }

        $legacy_type = $this->getLegacyPropertyType($property);
        if (!empty($legacy_type)) {
            return $legacy_type;
        }

        return gettype($this->$property);
    }

    public function getRelatedPropertiesNames(): array
    {
        $related_properties = [];
        $meta = $this->entity_manager->getClassMetadata($this->entity::class);
        foreach ($meta->getAssociationMappings() as $association_name => $association_mapping) {
            $related_fields_types = [
                \Doctrine\ORM\Mapping\ClassMetadata::MANY_TO_ONE,
                \Doctrine\ORM\Mapping\ClassMetadata::ONE_TO_ONE,
            ];
            if (!in_array($association_mapping['type'], $related_fields_types)) {
                continue;
            }
            
            $related_properties[] = $association_name;
        }
        return $related_properties;
    }

    protected function getLegacyPropertyType(string $property): ?string
    {
        $bean = $this->entity->getMintBean(false);
        $defs = $bean->field_defs[$property] ?? null;
        if (empty($defs['type']) && empty($defs['dbType'])) {
            return null;
        }

        $entity_creator_manager = new LegacyConnector(
            'EntityCreatorManager', 
            'include/EntityCreator/EntityCreatorManager.php',
        );

        $mapper = $entity_creator_manager::getORMMappingTypes();
        if (in_array($defs['type'], array_keys($mapper))) {
            return $mapper[$defs['type']];
        }
        if (in_array($defs['dbType'], array_keys($mapper))) {
            return $mapper[$defs['dbType']];
        }
        return null;
    }


}