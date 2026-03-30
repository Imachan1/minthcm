# Contribution Rules -- Core Developer Reference

## Extensibility Mandate

Every feature in MintHCM core must be extensible by client deployments via `custom/` directories. This is the foundational design principle.

### CustomLoader -- Always Use It

```php
// CORRECT: extensible
$controller = CustomLoader::getObject(MyController::class);

// WRONG: not extensible
$controller = new MyController();
```

`CustomLoader::getObject()` checks if `MintHCM\Custom\Api\Controllers\MyController` exists and extends the core class. If yes, instantiates the custom version.

### Extension Points to Maintain

| Mechanism | What it extends |
|-----------|----------------|
| `CustomLoader::getObject()` | Any PHP class (controllers, services, managers) |
| `ConstantsLoader` | Constants -- base file + `custom/constants/{name}/*.php` merged alphabetically |
| `custom/` directories | Routes, controllers, entities, repositories, middlewares, MintLogic |
| `legacy/custom/modules/` | Vardefs, metadata, language, logic hooks |
| `vue/src/custom/` | Components, fields, views, stores, routes |

## Namespace Conventions

| Layer | Core namespace | Custom namespace |
|-------|---------------|-----------------|
| API Controllers | `MintHCM\Api\Controllers` | `MintHCM\Custom\Api\Controllers` |
| API Entities | `MintHCM\Api\Entities` | `MintHCM\Custom\Api\Entities` |
| API Repositories | `MintHCM\Api\Repositories` | `MintHCM\Custom\Api\Repositories` |
| API Middlewares | `MintHCM\Api\Middlewares` | `MintHCM\Custom\Api\Middlewares` |
| MintLogic Validators | `MintHCM\Lib\MintLogic\Validators` | `MintHCM\Custom\Lib\MintLogic\Validators` |

Custom classes **must extend** the core class to work with CustomLoader:
```php
namespace MintHCM\Custom\Api\Controllers;
use MintHCM\Api\Controllers\ModuleController as BaseController;

class ModuleController extends BaseController { ... }
```

## Entity Auto-Generation

Never edit inside auto-generated section markers:
```php
// Auto-generated SectionProperties section start
// ... DO NOT EDIT HERE ...
// Auto-generated SectionProperties section end

// SAFE: add custom properties/methods here
```

Sections: `SectionUse`, `SectionRepository`, `SectionProperties`, `SectionMethods`.

After vardefs change: Admin > Repair > Quick Repair and Rebuild.

## Route Naming

- Dot notation: `module.action` (e.g., `employee.list`, `auth.login`)
- RESTful conventions:

| HTTP Method | Action | Route name |
|-------------|--------|------------|
| GET | List | `module.list` |
| GET | Detail | `module.detail` |
| POST | Create | `module.create` |
| PUT/PATCH | Update | `module.update` |
| DELETE | Delete | `module.delete` |

- Module routes auto-namespaced: `detail` becomes `Employees___detail`

## UI Text

**Never hardcode user-facing strings.**

Legacy PHP:
```php
$mod_strings['LBL_NEW_FEATURE'] = 'New Feature';
// Usage: $mod_strings['LBL_NEW_FEATURE']
```

Vue:
```typescript
const languages = useLanguagesStore()
languages.label('LBL_NEW_FEATURE')
```

Dropdown options in `$app_list_strings`:
```php
$app_list_strings['feature_status_dom'] = [
    '' => '',
    'active' => 'Active',
    'inactive' => 'Inactive',
];
```

## Build and Deploy Steps

After **vardefs** changes:
1. Admin > Repair > Quick Repair and Rebuild
2. Click "Execute" if SQL changes are shown

After **Vue** changes:
```bash
cd vue
npm run build:repo    # Builds and copies dist to ../assets
```

After **API** changes:
- No build step required (PHP is interpreted)
- Clear Doctrine cache if entity changes: `rm -rf api/cache/doctrine/*`

## Documentation Rules

When adding new patterns or changing existing ones, update:
- `api/documentation/` -- for API layer changes
- `vue/documentation/` -- for frontend changes
- Module-level comments and PHPDoc for complex logic

## Technology Requirements

- PHP 8.2
- MySQL 8.0 / MariaDB 10.5+
- ElasticSearch 7.10+ (7.x only)
- Node ~21
- Vue 3 + TypeScript (strict mode)

## ConstantsLoader Pattern

```php
// Base: api/constants/module_icons.php
return ['Employees' => 'people', 'Tasks' => 'task'];

// Custom: api/custom/constants/module_icons/01-hrm.php
return ['Benefits' => 'card_giftcard'];

// Custom: api/custom/constants/module_icons/02-override.php
return ['Employees' => 'person'];  // Overrides base

// Result: ['Employees' => 'person', 'Tasks' => 'task', 'Benefits' => 'card_giftcard']
```

Files in `custom/constants/{name}/` are merged alphabetically; later files override earlier ones.

## Common Mistakes

1. **Using `new` instead of `CustomLoader::getObject()`** -- blocks client extensibility. Every service/controller/manager that clients might override must use CustomLoader.
2. **Editing core files** -- all layers have `custom/` directories. Core edits are lost on upgrade and block client customization.
3. **Hardcoded strings in UI** -- breaks internationalization and client-specific labeling.
4. **Not running Quick Repair after vardefs changes** -- Doctrine entities and database schema get out of sync.
5. **Forgetting `npm run build:repo`** -- Vue changes are invisible in production until assets are rebuilt and copied.
