<?php

namespace MintHCM\Api\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\CommentRepository")
 * @ORM\Table(name="comments")
 */
class Comment
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
    public $reply_to_id;

    /**
     * @ORM\Column(type="string", length=36)
     */
    public $created_by;

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
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;

    /**
     * @ORM\Column(type="boolean")
     */
    public $pinned = false;

    public function __construct()
    {
        $this->date_entered = new \DateTime();
    }
}
