<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Comment;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use MintHCM\Modules\Comments\AccessChecker;

class CommentsController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getInitialData(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        if (empty($parent->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$parent->ACLAccess('view')) {
            return $response->withStatus(403);
        }
        $db = \DBManagerFactory::getInstance();
        $sql = "SELECT id, user_name, CONCAT_WS(' ', first_name, last_name) name, status, photo FROM users WHERE deleted = 0";
        $result = $db->query($sql);
        $users = [];
        while ($row = $db->fetchByAssoc($result)) {
            $users[] = $row;
        }
        chdir('../api');

        $init_controller = new Init\Init($this->entityManager);
        $languages_controller = new Init\Languages();

        $response->getBody()->write(json_encode([
            'user' => $init_controller->getCurrentUserData(),
            'languages' => $languages_controller->getLanguages(),
            'access' => $this->getAccess($parent),
            'comments' => $this->entityManager->getRepository(Comment::class)->get($parent_type, $parent_id),
            'users' => $users,
        ]));

        return $response;
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        if (empty($parent->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$parent->ACLAccess('view')) {
            return $response->withStatus(403);
        }
        chdir('../api');

        $comments = $this->entityManager->getRepository(Comment::class)->get($parent_type, $parent_id);

        $response->getBody()->write(json_encode($comments));
        return $response;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        global $current_user;

        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');
        $description = $request->getAttribute('description');
        $reply_to_id = $request->getAttribute('reply_to_id');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        if (empty($parent->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        $access = $this->getAccess($parent);
        if (!$access['add']) {
            $response = $response->withStatus(403);
            return $response;
        }
        $comment = \BeanFactory::newBean('Comments');
        $comment->description = $description;
        $comment->assigned_user_id = $current_user->id;
        $comment->parent_id = $parent_id;
        $comment->parent_type = $parent_type;
        if (!empty($reply_to_id)) {
            $comment->reply_to_id = $reply_to_id;
        }
        $comment->save(false);
        chdir('../api');

        return $response;
    }

    public function update(Request $request, Response $response, array $args)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');
        $comment_id = $request->getAttribute('id');
        $attributes = $request->getAttribute('attributes');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        $comment = \BeanFactory::getBean('Comments', $comment_id);
        if (empty($parent->id) || empty($comment->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$comment->ACLAccess('edit')) {
            return $response->withStatus(403);
        }
        foreach ($attributes as $field => $value) {
            if (isset($comment->field_defs[$field]) && $field !== 'id') {
                //TODO: field edit access (pinned/edited/removed) ?
                $comment->$field = $value;
            }
        }
        $comment->save(false);
        chdir('../api');

        $response = $response->withStatus(200);
        return $response;
    }

    protected function getAccess($parent)
    {
        $class_path = "\MintHCM\Modules\Comments\AccessChecker\\{$parent->module_name}AccessChecker";
        $access_checker = class_exists($class_path) ? new $class_path($parent) : new AccessChecker\AccessChecker($parent);
        return $access_checker->get();
    }
}
