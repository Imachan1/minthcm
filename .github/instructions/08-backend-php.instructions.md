---
applyTo:
  - "api/**/*.php"
  - "api/**/*.inc"
  - "modules/**/*.php"
  - "legacy/**/*.php"
  - "index.php"
---

# PHP Backend Guide

MintHCM backend uses modern PHP 8.2 with Slim Framework and Doctrine ORM.

## Tech Stack

- **PHP**: 8.2+
- **Framework**: Slim 4 (PSR-7)
- **ORM**: Doctrine 2
- **DI**: PHP-DI 7
- **Validation**: Respect\Validation
- **Testing**: PHPUnit 9

## Project Structure

```
api/
├── app/
│   ├── Controllers/        # Request handlers
│   ├── Entities/           # Doctrine entities (auto-generated)
│   ├── Repositories/       # Business logic
│   ├── Routes/             # Route definitions
│   ├── Middlewares/        # Request/response middlewares
│   └── Services/           # Shared services
├── lib/
│   ├── ApiManager.php      # Bootstrap
│   └── CustomLoader.php    # Custom namespace loader
├── configs/                # Runtime configs (not in git)
├── constants/              # Version-controlled constants
└── custom/
    └── app/                # Mirror app/ structure for customizations
```

## Request Lifecycle

```
HTTP Request
  ↓
ApiManager.php (bootstrap)
  ↓
Slim routing (api/app/Routes/*.php)
  ↓
AuthMiddleware (JWT validation)
  ↓
RouteAccessMiddleware (ACL check)
  ↓
Controller (process request)
  ↓
Repository (business logic + Doctrine)
  ↓
PSR-7 Response (JSON)
```

## Key Patterns

### Controllers

```php
namespace MintHCM\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmployeesController {
    public function getRecord(Request $request, Response $response, $args) {
        $id = $args['id'];
        $repository = $this->entityManager->getRepository('Employees');
        $record = $repository->getBean($id);
        
        $response->getBody()->write(json_encode($record));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

### Repositories

```php
namespace MintHCM\App\Repositories;

use Doctrine\ORM\EntityRepository;

class EmployeesRepository extends EntityRepository {
    public function findActiveEmployees(): array {
        return $this->createQueryBuilder('e')
            ->where('e.status = :status')
            ->setParameter('status', 'Active')
            ->getQuery()
            ->getResult();
    }
}
```

## Customization

Extend in `api/custom/app/`:

```php
// api/custom/app/Controllers/EmployeesController.php
namespace MintHCM\Custom\App\Controllers;

class EmployeesController extends \MintHCM\App\Controllers\EmployeesController {
    public function getRecord($request, $response, $args) {
        // Custom logic
        return parent::getRecord($request, $response, $args);
    }
}
```

**Full Documentation**: `api/documentation/*.md`

---

**Related**: [Routing & Controllers](09-routing-controllers.md), [Doctrine ORM](10-doctrine-orm.md), [Legacy Integration](11-legacy-integration.md)
