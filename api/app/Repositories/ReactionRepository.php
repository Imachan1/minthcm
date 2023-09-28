<?php

namespace MintHCM\Api\Repositories;

use Doctrine\ORM\EntityRepository;

class ReactionRepository extends EntityRepository
{
    public function getUserReactionId($parent_type, $parent_id, $user_id)
    {
        $sql = "SELECT id
                FROM reactions
                WHERE deleted = 0
                    AND parent_type = :parent_type
                    AND parent_id = :parent_id
                    AND assigned_user_id = :assigned_user_id";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery([
            'parent_type' => $parent_type,
            'parent_id' => $parent_id,
            'assigned_user_id' => $user_id,
        ]);
        return $result->fetchOne();
    }

    public function deleteUserReaction($parent_type, $parent_id, $user_id)
    {
        $sql = "DELETE
                FROM reactions
                WHERE parent_type = :parent_type
                    AND parent_id = :parent_id
                    AND assigned_user_id = :assigned_user_id";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->executeQuery([
            'parent_type' => $parent_type,
            'parent_id' => $parent_id,
            'assigned_user_id' => $user_id,
        ]);
    }
}
