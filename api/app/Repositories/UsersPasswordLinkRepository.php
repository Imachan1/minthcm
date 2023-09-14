<?php

namespace MintHCM\Api\Repositories;

use MintHCM\Api\Entities\UsersPasswordLink;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UsersPasswordLinkRepository extends EntityRepository
{
    public function markAllAsDeletedByUsername($username): int
    {
        $query = 'UPDATE MintHCM\Api\Entities\UsersPasswordLink upl
            SET upl.deleted = 1
            WHERE upl.username = :username
        ';

        return $this->getEntityManager()
            ->createQuery($query)
            ->setParameter('username', $username)
            ->execute();
    }
}
