---
applyTo:
    - "api/**/Routes/**"
    - "api/**/Controllers/**"
---

# Routing & Controllers

**Version**: 2.0  
**Last Updated**: 2026-01-02

Routes define URL patterns and map to controller methods.

## Route Definition Format

**Location**: `api/app/Routes/{Module}.php`

```php
return [
    [
        'method' => 'GET',
        'path' => '/employees/{id}',
        'class' => \MintHCM\App\Controllers\EmployeesController::class,
        'function' => 'getRecord',
        'auth' => true,  // Require authentication (default)
    ],
    [
        'method' => 'POST',
        'path' => '/employees',
        'class' => \MintHCM\App\Controllers\EmployeesController::class,
        'function' => 'createRecord',
        'auth' => true,
    ],
];
```

## Custom Routes

Create in `api/custom/app/Routes/{Module}.php`:

```php
return [
    [
        'method' => 'GET',
        'path' => '/employees/{id}/subordinates',
        'class' => \MintHCM\Custom\App\Controllers\EmployeesController::class,
        'function' => 'getSubordinates',
        'auth' => true,
    ],
];
```

## Controller Pattern

```php
namespace MintHCM\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmployeesController {
    public function getRecord(Request $request, Response $response, $args) {
        // Extract params
        $id = $args['id'];
        $queryParams = $request->getQueryParams();
        
        // Business logic
        $repository = $this->entityManager->getRepository('Employees');
        $record = $repository->getBean($id);
        
        // Return JSON response
        $response->getBody()->write(json_encode($record));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function createRecord(Request $request, Response $response, $args) {
        // Parse body
        $data = json_decode($request->getBody()->getContents(), true);
        
        // Validate
        // ...
        
        // Create entity
        $entity = new \MintHCM\App\Entities\Employee();
        $entity->setFirstName($data['first_name']);
        
        // Save
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        $response->getBody()->write(json_encode(['id' => $entity->getId()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
```

## Middlewares

Applied to all routes automatically:
- **AuthMiddleware**: JWT validation
- **RouteAccessMiddleware**: ACL check

## Public Endpoints

Set `auth => false` for public access:

```php
[
    'method' => 'POST',
    'path' => '/login',
    'class' => \MintHCM\App\Controllers\AuthController::class,
    'function' => 'login',
    'auth' => false,  // No authentication required
],
```

**Full Documentation**: `api/documentation/03-routing.md`

---

**Related**: [PHP Backend](08-backend-php.md), [Doctrine ORM](10-doctrine-orm.md), [Validation & Security](13-validation-security.md)
