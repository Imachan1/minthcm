# MintHCM API Directory Documentation

## Overview

The `api` directory is the backend API layer for MintHCM built on top of Slim Framework. It handles HTTP requests, business logic, data access, and modular features. The architecture uses dependency injection, middleware, and a sophisticated routing system.

## Directory Structure

- **index.php**: Main entry point for API requests
- **composer.json / composer.lock**: PHP dependency management  
- **phpunit.xml / runTests.php**: Test configuration and runner
- **app/**: Core application logic (detailed below)
- **bin/**: Executable scripts (e.g., Doctrine CLI)
- **configs/**: Configuration files
- **constants/**: Global constants and definitions
- **data/**: Data models and factories
- **lib/**: Shared libraries
- **modules/**: Modular features organized by business domain
- **tests/**: Unit and integration tests
- **utils/**: Utility classes and helpers
- **vendor/**: Third-party dependencies (Composer)

---

## The `app/` Subdirectory - Core Application Logic

### Structure Overview

- **ApiManager.php**: Central API manager
- **Config/**: Application configuration classes
- **Constansts/**: Global parameters
- **Containers/**: Dependency injection containers (Doctrine, etc.)
- **Controllers/**: HTTP request handlers for specific features
- **Entities/**: Doctrine ORM entities representing business objects
- **ExceptionHandlers/**: Custom exception handling
- **Middlewares/**: Request/response middleware pipeline
- **Repositories/**: Data access layer (repository pattern)
- **Routes/**: Route definitions and management system

### Key Components

#### Controllers
Controllers handle HTTP requests and orchestrate business logic:
- `AuthController.php`: Authentication and authorization
- `ModuleController.php`: Generic module operations (CRUD)
- `GlobalSearchController.php`: Search functionality
- `CommentsController.php`: Comment system
- `KudosController.php`: Kudos/recognition system
- `ReactionsController.php`: Reaction system

#### Entities
Doctrine ORM entities representing database tables and business objects:
- `Employees.php`: Employee management
- `Appraisals.php`: Performance appraisal system
- `Candidates.php`: Recruitment candidates
- `Goals.php`: Goal setting and tracking
- And many more HR-specific entities...

---

## Routing System

The MintHCM API uses a sophisticated routing system managed by `RouteManager.php` that supports both global and module-specific routes.

### Route Locations

The system scans for routes in multiple locations:

#### Global Routes
- `app/Routes/routes/` - Core API routes
- `custom/app/Routes/routes/` - Custom global routes

#### Module-Specific Routes  
- `app/Routes/modules/` - Core module routes
- `custom/app/Routes/modules/` - Custom module routes
- `modules/{module_name}/api/routes/` - Module-specific routes
- `custom/modules/{module_name}/api/routes/` - Custom module-specific routes

### Route Definition Structure

Routes are defined in PHP files that return an array. Each route has this structure:

```php
$routes = array(
    "route_name" => array(
        "method" => "GET|POST|PUT|DELETE", // or array for multiple methods
        "path" => "/api/path/{param}",
        "class" => ControllerClass::class,
        "function" => 'methodName', // optional, uses __invoke if not specified
        "desc" => "Route description",
        "options" => array(
            'auth' => true, // requires authentication
        ),
        "pathParams" => array(
            "param" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parameter description",
                "example" => 'example-value',
            ),
        ),
        "queryParams" => array(
            // Query parameter definitions
        ),
        "bodyParams" => array(
            // Request body parameter definitions
        ),
    ),
);
```

### Parameter Types

The system supports various parameter types:
- `StringType::class` - String values
- `IntType::class` - Integer values  
- `BoolType::class` - Boolean values
- `ArrayType::class` - Array values
- `EmailType::class` - Email addresses

### Adding New Routes

#### 1. Global Routes

Create a new file in `app/Routes/routes/` (or `custom/app/Routes/routes/` for customizations):

```php
<?php
// app/Routes/routes/my_feature.php

use MintHCM\Api\Controllers\MyFeatureController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "my_feature_list" => array(
        "method" => "GET",
        "path" => "/my-feature",
        "class" => MyFeatureController::class,
        "function" => 'list',
        "desc" => "Get list of my features",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(
            "limit" => array(
                "type" => IntType::class,
                "required" => false,
                "desc" => "Number of records to return",
                "example" => 10,
            ),
        ),
        "bodyParams" => array(),
    ),
    "my_feature_create" => array(
        "method" => "POST", 
        "path" => "/my-feature",
        "class" => MyFeatureController::class,
        "function" => 'create',
        "desc" => "Create new feature",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "name" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Feature name",
                "example" => 'My Feature Name',
            ),
        ),
    ),
);
```

#### 2. Module-Specific Routes

For module-specific routes, create files in the appropriate module directory. Module routes automatically get prefixed with the module name.

Example for `modules/Employees/api/routes/custom.php`:

```php
<?php
use MintHCM\Api\Controllers\Employees\CustomController;

$routes = array(
    "custom_action" => array(
        "method" => "POST",
        "path" => "/custom-action/{id}", // Will become /Employees/custom-action/{id}
        "class" => CustomController::class,
        "function" => 'customAction',
        "desc" => "Custom employee action",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Employee ID",
                "example" => '123-456-789',
            ),
        ),
    ),
);
```

#### 3. Create the Controller

Create the corresponding controller class:

```php
<?php
// app/Controllers/MyFeatureController.php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MyFeatureController 
{
    public function list(Request $request, Response $response, array $args): Response
    {
        // Implementation
        $data = ['features' => []];
        
        return $response->withJson($data);
    }
    
    public function create(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();
        
        // Implementation
        
        return $response->withJson(['success' => true]);
    }
}
```

### Route Registration Process

1. **Automatic Discovery**: `RouteManager` automatically scans all route locations
2. **Route Building**: Routes are processed and registered with Slim Framework
3. **Module Prefixing**: Module routes get prefixed with module name
4. **Dependency Injection**: Controllers are instantiated with proper dependencies
5. **Middleware Application**: Authentication and parameter validation middleware is applied

### Best Practices

1. **Naming Conventions**:
   - Use descriptive route names
   - Follow REST conventions (GET for retrieval, POST for creation, etc.)
   - Use kebab-case for URL paths

2. **Parameter Validation**:
   - Always define parameter types
   - Mark required parameters explicitly  
   - Provide examples and descriptions

3. **Authentication**:
   - Set `'auth' => true` for protected endpoints
   - Use `'auth' => false` only for login/public endpoints

4. **Error Handling**:
   - Use appropriate HTTP status codes
   - Return consistent error response formats

5. **Documentation**:
   - Provide clear descriptions for routes and parameters
   - Include realistic examples

## Testing

- Add tests in `tests/` directory
- Run tests with: `./runTests.php` or `vendor/bin/phpunit`
- Test both successful and error scenarios
- Mock external dependencies

## Development Workflow

1. **Plan the Feature**: Define the endpoints and data flow
2. **Create Routes**: Add route definitions following the structure above
3. **Implement Controller**: Create controller with business logic
4. **Add Tests**: Write comprehensive tests
5. **Test Manually**: Use API client to verify functionality
6. **Document**: Update API documentation

This routing system provides flexibility, modularity, and maintainability while supporting both core functionality and custom extensions.