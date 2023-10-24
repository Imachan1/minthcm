<?php

namespace MintHCM\Api\Repositories;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function get($parent_type, $parent_id)
    {
        $sql = "SELECT comments.id
                    , comments.description
                    , comments.reply_to_id
                    , comments.date_entered
                    , comments.pinned
                    , comments.date_edited
                    , comments.removed
                    , JSON_OBJECT(
                        'id', users.id,
                        'name', CONCAT_WS(' ', users.first_name, users.last_name),
                        'photo', users.photo
                    ) assigned_user
                    , IF(reactions.id IS NULL, NULL, JSON_ARRAYAGG(JSON_OBJECT(
                        'type', reactions.reaction_type,
                        'user', JSON_OBJECT(
                            'id', reactions.assigned_user_id,
                            'name', CONCAT_WS(' ', reaction_user.first_name, reaction_user.last_name)
                        )
                    ))) reactions
                FROM comments
                INNER JOIN users
                    ON users.id = comments.assigned_user_id
                    AND users.deleted = 0
                LEFT JOIN reactions
                    ON reactions.parent_type = 'Comments'
                    AND reactions.parent_id = comments.id
                    AND reactions.deleted = 0
                LEFT JOIN users reaction_user
                    ON reaction_user.id = reactions.assigned_user_id
                    AND reaction_user.deleted = 0
                WHERE comments.deleted = 0
                    AND comments.parent_id = :parent_id
                    AND comments.parent_type = :parent_type
                GROUP BY comments.id
                ORDER BY comments.date_entered ASC
        ";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery([
            'parent_type' => $parent_type,
            'parent_id' => $parent_id,
        ]);
        $comments = $result->fetchAllAssociative();
        foreach ($comments as $index => $comment) {
            $comments[$index]['assigned_user'] = json_decode($comment['assigned_user'], true);
            $comments[$index]['reactions'] = json_decode($comment['reactions'], true);
            $comments[$index]['pinned'] = boolval($comment['pinned']);
            $comments[$index]['removed'] = boolval($comment['removed']);
        }
        return $comments;
    }
}
