<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Reaction;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReactionsController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function react(Request $request, Response $response, array $args): Response
    {
        global $current_user;
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');
        $reaction_type = $request->getAttribute('reaction_type');

        if (empty($reaction_type)) {
            $response = $response->withStatus(400);
            return $response;
        }

        $reaction = null;
        $user_reaction_id = $this->entityManager->getRepository(Reaction::class)
            ->getUserReactionId($parent_type, $parent_id, $current_user->id);

        chdir('../legacy');
        if (!empty($user_reaction_id)) {
            $reaction = \BeanFactory::getBean('Reactions', $user_reaction_id);
        }
        if (empty($reaction->id)) {
            $reaction = \BeanFactory::newBean('Reactions');
            $reaction->assigned_user_id = $current_user->id;
            $reaction->parent_type = $parent_type;
            $reaction->parent_id = $parent_id;
        }
        $reaction->reaction_type = $reaction_type;
        $reaction->save(false);
        chdir('../api');
        
        return $response;
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        global $current_user;
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');

        $this->entityManager->getRepository(Reaction::class)
            ->deleteUserReaction($parent_type, $parent_id, $current_user->id);

        return $response;
    }
}
