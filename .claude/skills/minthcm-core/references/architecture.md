# MintHCM Architecture -- Core Developer Reference

## Three-Layer Architecture

```
┌─────────────────────────────────────────────────┐
│  Vue 3 Frontend (vue/src/)                      │
│  Vite + Vuetify 3 + Pinia + TypeScript          │
├─────────────────────────────────────────────────┤
│  Slim 4 REST API (api/)                         │
│  Doctrine ORM + PHP-DI + PSR-7                  │
├─────────────────────────────────────────────────┤
│  Legacy SuiteCRM (legacy/)                      │
│  Modules + Beans + Metadata + Logic Hooks       │
└─────────────────────────────────────────────────┘
│                 MySQL 8.0                       │
└─────────────────────────────────────────────────┘
```

- **Root `index.php`** serves legacy views (iframed by Vue's `LegacyView`).
- **`api/index.php`** bootstraps Slim 4 but first loads legacy's `entryPoint.php` to initialize Sugar globals (`$current_user`, `$db`, `$sugar_config`, etc.).
- **Vue** runs as SPA, communicates with API via Axios. Whether a module uses Vue or legacy view is controlled by `backend.initData.legacy_views[module]`.

## Request Lifecycle (API)

```
HTTP Request → api/index.php
  → require legacy/entryPoint.php (Sugar globals init)
  → Composer autoloader
  → DoctrineContainerBuilder → PHP-DI container + EntityManager
  → ApiManager::getInstance() (Singleton, via CustomLoader)
  → addBeforeRouteMiddlewares() registers middleware stack
  → RouteManager::getInstance() discovers and registers routes
  → Slim App runs:
      JsonBodyParserMiddleware → AuthMiddleware → RouteAccessMiddleware → ParamsMiddleware
      → Router matches route → Controller action
      → Response (JSON via MintResponse::withJson())
```

Middleware execution is LIFO -- last added runs first on request, last on response.

## Key Design Patterns

### CustomLoader (Extensibility)
```php
// utils/CustomLoader.php
$controller = CustomLoader::getObject(MyController::class);
// Checks if MintHCM\Custom\Api\Controllers\MyController exists and extends core
// If yes, instantiates custom version; otherwise core version
```
**Rule:** Always use `CustomLoader::getObject()` instead of `new` for classes that clients may need to extend.

### LegacyConnector (Legacy Bridge)
```php
$legacy = new LegacyConnector('SugarBean', 'modules/Employees/Employee.php');
$legacy->retrieve($id);  // Handles chdir to legacy/ and back to api/
```

### Singleton (ApiManager, RouteManager)
```php
$api = ApiManager::getInstance();  // Uses CustomLoader internally
$routes = RouteManager::getInstance();
```

### RouteManager (Auto-Discovery)
Scans these locations for `$routes` arrays:
```
app/Routes/routes/              # Core global routes
custom/app/Routes/routes/       # Custom global routes
app/Routes/modules/{Module}/    # Core module routes
custom/app/Routes/modules/{Module}/
modules/{Module}/api/routes/    # Module-owned routes
custom/modules/{Module}/api/routes/
```
Module routes get auto-prefixed with `/{ModuleName}`.

### DI Container
```php
// app/Containers/Doctrine/DoctrineContainerBuilder.php
// Builds PHP-DI container with Doctrine EntityManager
// Controllers receive dependencies via constructor injection
class MyController {
    public function __construct(MyRepository $repo) { ... }
}
```

## Extension Points (all 3 layers)

| Layer | Core | Custom override |
|-------|------|----------------|
| Legacy | `legacy/modules/{Module}/` | `legacy/custom/modules/{Module}/` |
| API classes | `api/app/Controllers/`, `Entities/`, `Repositories/` | `api/custom/app/Controllers/`, etc. |
| API routes | `api/app/Routes/routes/` | `api/custom/app/Routes/routes/` |
| API constants | `api/constants/{name}.php` | `api/custom/constants/{name}/*.php` |
| API MintLogic | `api/lib/MintLogic/Modules/{Module}/` | `api/custom/lib/MintLogic/{Module}/` |
| Vue | `vue/src/components/`, `views/`, `store/` | `vue/src/custom/` |

## ConstantsLoader
```php
// Base: constants/module_icons.php returns array
// Custom: custom/constants/module_icons/*.php files return arrays
// Merged alphabetically, custom overrides base
$icons = ConstantsLoader::getConstants('module_icons');
```

## Common Mistakes

1. **Using `new MyClass()` instead of `CustomLoader::getObject()`** -- breaks extensibility for client deployments.
2. **Forgetting legacy bootstrap in API** -- `api/index.php` must `require` legacy's `entryPoint.php` before Sugar globals (`$current_user`, `$db`) are available.
3. **Wrong middleware order** -- middlewares are LIFO. Adding auth middleware after params middleware means auth runs before params validation.
4. **Not checking `backend.initData.legacy_views[module]`** -- determines whether Vue or legacy iframe renders a module's views.
5. **Editing core files instead of custom/** -- changes lost on upgrade, breaks client customization path.
