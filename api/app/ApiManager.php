<?php

namespace MintHCM\Api;

use MintHCM\Api\ExceptionHandlers\Doctrine\DoctrineConnectionExceptionHandler;
use MintHCM\Api\Middlewares\Auth\AuthMiddleware;
use MintHCM\Api\Middlewares\Params\ParamsMiddleware;
use MintHCM\Api\Middlewares\Parsers\JsonBodyParserMiddleware;
use MintHCM\Api\Routes\RouteManager;
use MintHCM\Utils\CustomLoader;

class ApiManager
{
    protected static $_instance;

    protected $app;
    protected $routeManager;

    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->routeManager = RouteManager::getInstance();
    }

    public static function getInstance()
    {
        if (!is_object(self::$_instance)) {
            self::$_instance = CustomLoader::getObject(ApiManager::class);
        }
        return self::$_instance;
    }

    public function execute()
    {
        $this->addBeforeRouteMiddlewares();
        $this->app->addRoutingMiddleware();
        $this->routeManager->execute();
        $this->setErrorMiddleware();
    }

    protected function addBeforeRouteMiddlewares()
    {
        $this->app->addBodyParsingMiddleware();
        $this->app->add(CustomLoader::getObject(AuthMiddleware::class));
        $this->app->add(CustomLoader::getObject(ParamsMiddleware::class));
        $this->app->add(CustomLoader::getObject(JsonBodyParserMiddleware::class));
    }

    protected function setErrorMiddleware()
    {
        $errorMiddleware = $this->app->addErrorMiddleware(true, false, false);
        $errorMiddleware->setErrorHandler(
            \Doctrine\DBAL\Exception\ConnectionException::class,
            DoctrineConnectionExceptionHandler::class
        );
    }
}
