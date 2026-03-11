# API Extensions

## Custom Global Routes

Create `api/custom/app/Routes/routes/{feature}.php`:

```php
<?php
use MintHCM\Custom\Api\Controllers\ReportController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;

$routes = [
    'con.reports.list' => [
        'method' => 'GET',
        'path' => '/reports',
        'class' => ReportController::class,
        'function' => 'list',
        'desc' => 'List all reports',
        'queryParams' => [
            'page' => [
                'type' => IntType::class,
                'required' => false,
                'desc' => 'Page number',
                'default' => 1,
            ],
        ],
    ],
    'con.reports.generate' => [
        'method' => 'POST',
        'path' => '/reports/generate',
        'class' => ReportController::class,
        'function' => 'generate',
        'desc' => 'Generate a report',
        'bodyParams' => [
            'type' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Report type',
            ],
        ],
    ],
];
```

All routes are accessed at `/api/{path}` (e.g., `GET /api/reports`).

## Custom Module Routes

Create `api/custom/modules/{Module}/api/routes/{feature}.php`. Paths are auto-prefixed with `/{Module}`:

```php
<?php
use MintHCM\Custom\Api\Controllers\BankExportController;

$routes = [
    'export' => [
        'method' => 'GET',
        'path' => '/export',    // Becomes: /con_BankAccounts/export
        'class' => BankExportController::class,
        'function' => 'export',
    ],
];
```

## Custom Controllers

Create `api/custom/app/Controllers/{Controller}.php`:

```php
<?php
namespace MintHCM\Custom\Api\Controllers;

use MintHCM\Custom\Lib\Services\ReportService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class ReportController
{
    private ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function list(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $page = (int)($params['page'] ?? 1);
        $data = $this->service->getReports($page);
        return $response->withJson(['data' => $data]);
    }

    public function generate(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $report = $this->service->generate($body['type']);
        return $response->withJson(['data' => $report], 201);
    }

    public function detail(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];  // From path parameter {id}
        $report = $this->service->getById($id);
        if (!$report) {
            return $response->withJson(['error' => 'Not found'], 404);
        }
        return $response->withJson(['data' => $report]);
    }
}
```

### Request data access patterns

```php
$request->getQueryParams();     // ?page=1&limit=20 -> ['page'=>'1', 'limit'=>'20']
$request->getParsedBody();      // JSON body -> array
$args['id'];                    // URL path parameter {id}
$request->getHeaderLine('Authorization');   // Header value
$request->getAttribute('current_user');     // Middleware-set attributes
```

## Extending Core Controllers

```php
<?php
namespace MintHCM\Custom\Api\Controllers;

use MintHCM\Api\Controllers\ModuleController as Base;

class ModuleController extends Base
{
    public function list($request, $response)
    {
        // Add pre-processing
        $response = parent::list($request, $response);
        // Add post-processing
        return $response;
    }
}
```

Auto-loaded by `CustomLoader` -- no registration needed.

## Custom Services

Create `api/custom/lib/Services/{Service}.php` or `api/custom/{ProjectName}/Services/{Service}.php`:

```php
<?php
namespace MintHCM\Custom\Lib\Services;

use MintHCM\Api\Repositories\EmployeesRepository;

class ReportService
{
    private EmployeesRepository $repository;

    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getReports(int $page): array
    {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        return $this->repository->createQueryBuilder('e')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
```

PHP-DI auto-wires constructor dependencies.

## Custom Repositories

```php
<?php
// api/custom/app/Repositories/EmployeesRepository.php
namespace MintHCM\Custom\Api\Repositories;

use MintHCM\Api\Repositories\EmployeesRepository as Base;

class EmployeesRepository extends Base
{
    public function findActive(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.employee_status = :status')
            ->setParameter('status', 'Active')
            ->getQuery()
            ->getResult();
    }
}
```

## Custom Entities

Extend core entity for additional ORM-mapped properties:

```php
<?php
// api/custom/app/Entities/Employees.php
namespace MintHCM\Custom\Api\Entities;

use MintHCM\Api\Entities\Employees as Base;

class Employees extends Base
{
    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
```

**Note**: Entities are auto-generated from vardefs. Only extend for custom methods, not for adding fields (use vardefs for that).

## Custom Middlewares

```php
<?php
// api/custom/app/Middlewares/AuditMiddleware.php
namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuditMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Pre-processing
        $start = microtime(true);

        $response = $handler->handle($request);

        // Post-processing
        $duration = microtime(true) - $start;
        return $response->withHeader('X-Response-Time', round($duration * 1000) . 'ms');
    }
}
```

Register in `api/custom/app/ApiManager.php`:

```php
<?php
namespace MintHCM\Custom\Api;

use MintHCM\Api\ApiManager as Base;
use MintHCM\Custom\Api\Middlewares\AuditMiddleware;

class ApiManager extends Base
{
    protected function addBeforeRouteMiddlewares()
    {
        parent::addBeforeRouteMiddlewares();
        $this->app->add(new AuditMiddleware());
    }
}
```

## Custom Constants

Add files to `api/custom/constants/{constant_name}/`:

```php
// api/custom/constants/module_icons/con_modules.php
<?php
return [
    'con_BankAccounts' => 'account_balance',
    'con_Invoices' => 'receipt_long',
];

// api/custom/constants/quick_create/con_modules.php
<?php
return [
    'con_BankAccounts' => translate('LBL_LIST_TITLE', 'con_BankAccounts'),
];

// api/custom/constants/legacy_views/con_remove.php
// Return empty array to remove modules from legacy views (enable Vue views)
<?php
return [];
```

## Parameter Types for Routes

| Type | Class | Validates |
|------|-------|-----------|
| String | `StringType::class` | Text values |
| Integer | `IntType::class` | Integer numbers |
| Boolean | `BoolType::class` | true/false |
| Email | `EmailType::class` | Email format |
| Date | `DateType::class` | Date values |
| Float | `FloatType::class` | Decimal numbers |
| Array | `ArrayType::class` | Array values |

## Public Endpoints (No Auth)

```php
'con.public.status' => [
    'method' => 'GET',
    'path' => '/public/status',
    'class' => PublicController::class,
    'function' => 'status',
    'options' => [
        'auth' => false,  // No JWT required
    ],
],
```

## Common Mistakes

1. **Wrong namespace** -- custom controllers must use `MintHCM\Custom\Api\Controllers`, not `MintHCM\Api\Controllers`.
2. **Missing `$routes` variable** in route files -- the file must define `$routes = [...]`.
3. **Controller class not found** -- check namespace, file path, and that the class exists (route is silently skipped).
4. **Using `auth => false` for sensitive endpoints** -- only use for truly public endpoints like login or health checks.
5. **Not using constructor DI** -- PHP-DI auto-wires dependencies. Don't instantiate services manually.
