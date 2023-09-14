<?php

namespace MintHCM\Api\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\UsersPasswordLinkRepository")
 * @ORM\Table(name="users_password_link", indexes={
 *   @ORM\Index(name="idx_username", columns={"username"})
 * })
 */
class UsersPasswordLink
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
    public $username;


    /**
     * @ORM\Column(type="datetime")
     */
    public $date_generated;

    /**
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;

    public function __construct()
    {
        $this->date_generated = new \DateTime();
    }
}
