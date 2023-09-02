<?php

namespace MintHCM\Api\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\UserPreferencesRepository")
 * @ORM\Table(name="user_preferences", indexes={
 *   @ORM\Index(name="idx_userprefnamecat", columns={"assigned_user_id", "category"})
 * })
 */
class UserPreferences
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $category;

    /**
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_entered;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_modified;

    /**
     * @ORM\Column(type="uuid", nullable=true)
     */
    public $assigned_user_id;

    /**
     * @ORM\Column(type="string")
     */
    public $assigned_user_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $contents;
}
