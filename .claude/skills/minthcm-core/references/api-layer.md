# API Layer -- Core Developer Reference

## Routing

### Route Definition Format

```php
// api/app/Routes/routes/myfeature.php
<?php
use MintHCM\Api\Controllers\MyController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;
use MintHCM\Api\Middlewares\Params\ParamTypes\EmailType;

$routes = [
    'employee.create' => [
        'method' => 'POST',              // GET, POST, PUT, DELETE, or ['GET','POST']
        'path' => '/employees',           // URL pattern with {param} placeholders
        'class' => MyController::class,   // Controller class
        'function' => 'create',           // Method (omit for __invoke)
        'desc' => 'Create employee',      // Documentation
        'options' => ['auth' => true],    // auth=>false for public routes
        'bodyParams' => [
            'first_name' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'First name',
                'example' => 'John',
            ],
            'email' => [
                'type' => EmailType::class,
                'required' => true,
            ],
            'age' => [
                'type' => IntType::class,
                'required' => false,
                'default' => null,
            ],
        ],
        'pathParams' => [],
        'queryParams' => [],
    ],
];
```

### Route Locations

| Location | Type | Auto-prefix |
|----------|------|-------------|
| `app/Routes/routes/` | Core global | None |
| `custom/app/Routes/routes/` | Custom global | None |
| `app/Routes/modules/{Module}/` | Core module | `/{Module}` |
| `custom/app/Routes/modules/{Module}/` | Custom module | `/{Module}` |
| `modules/{Module}/api/routes/` | Module-owned | `/{Module}` |
| `custom/modules/{Module}/api/routes/` | Custom module-owned | `/{Module}` |

Module route names are auto-namespaced: `detail` becomes `Employees___detail`.

### Parameter Types

`StringType`, `IntType`, `BoolType`, `ArrayType`, `EmailType`, `DateType`, `FloatType` -- validation is automatic via `ParamsMiddleware`.

### Route Naming Convention

Dot notation: `module.action` (e.g., `employee.list`, `auth.login`).
RESTful: GET list, GET detail, POST create, PUT update, DELETE delete.

## Controllers

```php
<?php
namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use MintHCM\Api\Repositories\EmployeesRepository;

class EmployeeController
{
    private EmployeesRepository $repository;

    // Dependencies injected via PHP-DI constructor injection
    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $page = (int)($params['page'] ?? 1);
        $limit = (int)($params['limit'] ?? 20);
        $offset = ($page - 1) * $limit;

        $records = $this->repository->createQueryBuilder('e')
            ->orderBy('e.date_entered', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()->getResult();

        return $response->withJson(['data' => $records, 'total' => $this->repository->count([])]);
    }

    public function detail(Request $request, Response $response, array $args): Response
    {
        $record = $this->repository->find($args['id']);
        if (!$record) return $response->withJson(['error' => 'Not found'], 404);
        return $response->withJson(['data' => $record]);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();       // Already validated by ParamsMiddleware
        $entity = new \MintHCM\Api\Entities\Employees();
        $entity->first_name = $data['first_name'];
        $entity->date_entered = new \DateTime();

        $em = $this->repository->getEntityManager();
        $em->persist($entity);
        $em->flush();

        return $response->withJson(['data' => $entity], 201);
    }
}
```

### Key Request Methods

```php
$request->getQueryParams();          // ?page=1&limit=20
$request->getParsedBody();           // JSON body (parsed by JsonBodyParserMiddleware)
$args['id'];                         // Path parameters /resource/{id}
$request->getHeaderLine('Authorization');
$request->getAttribute('current_user'); // Set by AuthMiddleware
$request->getMethod();               // GET, POST, etc.
```

### Response Patterns

```php
$response->withJson($data);          // 200 + JSON
$response->withJson($data, 201);     // Created
$response->withJson(['error' => 'Not found'], 404);
$response->withJson(['errors' => $validationErrors], 400);
```

## Middlewares

### Built-in Middleware Stack (LIFO order)

```php
// ApiManager::addBeforeRouteMiddlewares()
$this->app->addBodyParsingMiddleware();
$this->app->add(ParamsMiddleware::class);         // 4. Validate params
$this->app->add(RouteAccessMiddleware::class);    // 3. ACL check
$this->app->add(AuthMiddleware::class);           // 2. JWT/OAuth2
$this->app->add(JsonBodyParserMiddleware::class); // 1. Parse JSON body
```

### Creating a Middleware

```php
<?php
namespace MintHCM\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class RateLimitMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Pre-processing
        if ($this->isRateLimited($request)) {
            $response = new Response();
            return $response->withStatus(429)->withJson(['error' => 'Rate limit exceeded']);
        }

        // Call next middleware/controller
        $response = $handler->handle($request);

        // Post-processing
        return $response->withHeader('X-RateLimit-Remaining', '99');
    }
}
```

### Registering Global Middleware

```php
// custom/app/ApiManager.php (extend core)
namespace MintHCM\Custom\Api;

use MintHCM\Api\ApiManager as BaseApiManager;

class ApiManager extends BaseApiManager
{
    protected function addBeforeRouteMiddlewares()
    {
        parent::addBeforeRouteMiddlewares();
        $this->app->add(new \MintHCM\Custom\Api\Middlewares\RateLimitMiddleware());
    }
}
```

## Auth

Routes require authentication by default. Disable with `'options' => ['auth' => false]`. Only use for login, health checks, and truly public endpoints.

## Testing

```php
// api/tests/Controllers/MyControllerTest.php
namespace MintHCM\Tests\Controllers;

use PHPUnit\Framework\TestCase;

class MyControllerTest extends TestCase
{
    private $repository;
    private $controller;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EmployeesRepository::class);
        $this->controller = new EmployeeController($this->repository);
    }

    public function testListReturnsData(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([['id' => '1', 'name' => 'Test']]);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([]);
        $response = new \Slim\Psr7\Response();

        $result = $this->controller->list($request, $response);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
```

Run tests: `cd api && vendor/bin/phpunit`

## Common Mistakes

1. **Forgetting `auth => false` for login endpoints** -- route will require JWT token that doesn't exist yet.
2. **Using `StringType` for emails** -- use `EmailType` for automatic format validation.
3. **Not defining parameter types** -- without `bodyParams`/`queryParams`, `ParamsMiddleware` can't validate input.
4. **Creating controller without DI** -- use constructor injection, not `new Repository()` inside methods.
5. **Returning wrong HTTP status** -- always 404 for not found, 400 for validation errors, 201 for created resources, not 200 for everything.
