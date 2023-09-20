<?php

namespace MintHCM\Api\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\ReactionRepository")
 * @ORM\Table(name="reactions")
 */
class Reaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=36)
     */
    public $parent_id;

    /**
     * @ORM\Column(type="string", length=36)
     */
    public $created_by;

    /**
     * @ORM\Column(type="string", length=36)
     */
    public $assigned_user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_entered;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_modified;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $parent_type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $reaction_type;

    /**
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     * @ORM\JoinColumn(name="assigned_user_id", referencedColumnName="id")
     */
    public $assigned_user = null;

    public function __construct()
    {
        $this->date_entered = new \DateTime();
    }
}
