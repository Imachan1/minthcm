<?php

namespace MintHCM\Api\Repositories;

use MintHCM\Api\Entities\UserPreferences;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPreferencesRepository extends EntityRepository
{
    public function findAllUndeletedByUserId($user_id): array
    {
        $query = 'SELECT up.contents, up.category
            FROM MintHCM\Api\Entities\UserPreferences up
            WHERE up.assigned_user_id = :uid
                AND up.deleted = 0
        ';

        return $this->getEntityManager()
            ->createQuery($query)
            ->setParameter('uid', $user_id)
            ->getResult();
    }
}
