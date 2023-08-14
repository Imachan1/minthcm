<?php

namespace MintHCM\Api\Containers\Doctrine;

use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class DoctrineContainerBuilder extends ContainerBuilder
{
    public function __construct()
    {
        parent::__construct();

        $this->setupExtensions();
        $this->addSettings();
        $this->addDependencies();
    }

    protected function setupExtensions()
    {
        Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
    }

    protected function addSettings()
    {
        global $mint_config;

        $this->addDefinitions([
            'settings' => [
                'doctrine' => [
                    'dev_mode' => true,
                    'cache_path' => __DIR__ . '/../../../var/cache/doctrine',
                    'proxy_path' => __DIR__ . '/../../../var/cache/doctrine/orm/Proxies',
                    'entity_paths' => [__DIR__ . '/../../Entities/'],
                    'connection' => $mint_config['database'],
                ]
            ]
        ]);
    }

    protected function addDependencies()
    {
        $this->addDefinitions([
            EntityManagerInterface::class => function (ContainerInterface $c): EntityManager {
                $doctrineSettings = $c->get('settings')['doctrine'];
                $config = ORMSetup::createAnnotationMetadataConfiguration(
                    $doctrineSettings['entity_paths'],
                    $doctrineSettings['dev_mode'],
                    $doctrineSettings['proxy_path'],
                    new FilesystemAdapter('', 0, $doctrineSettings['cache_path'] ?? null)
                );
                $connection = DriverManager::getConnection($doctrineSettings['connection'], $config);
                return new EntityManager($connection, $config);
            }
        ]);
    }
}
