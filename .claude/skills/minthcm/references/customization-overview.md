# Customization Overview

## Three custom/ Directories

| Layer | Custom Path | What Goes Here |
|-------|------------|----------------|
| Legacy (PHP backend) | `legacy/custom/` | Vardefs, logic hooks, metadata, language files, schedulers |
| API (REST) | `api/custom/` | Routes, controllers, entities, repositories, services, constants, MintLogic |
| Vue (frontend) | `vue/src/custom/` | Field types, routes, stores, views, drawers, styles, API wrappers |

## What Goes Where

### Legacy custom/ structure
```
legacy/custom/
  modules/{Module}/
    Ext/Vardefs/{Module}.{ProjectName}.php  # New fields
    logic_hooks.php                         # Logic hooks registration
    {HookClass}.php                         # Hook implementation
  Extension/application/Ext/Language/
    {lang}.{ProjectName}.php                # Dropdown lists
  modules/{Module}/metadata/
    recordviewdefs.php                      # Vue record view (new modules only)
    listviewdefs.php                        # List view columns
    eslistviewdefs.php                      # ES list view columns
    subpaneldefs.php                        # Subpanel config
```

### API custom/ structure
```
api/custom/
  app/
    ApiManager.php                          # Extended API manager (middlewares)
    Controllers/{Controller}.php            # Custom controllers
    Entities/{Entity}.php                   # Extended entities
    Repositories/{Repository}.php           # Extended repositories
    Routes/
      routes/{feature}.php                  # Global routes
      modules/{Module}/{feature}.php        # Module routes (auto-prefixed)
    Middlewares/{Middleware}.php             # Custom middlewares
  lib/
    MintLogic/{ModuleName}/*logicdefs.php   # MintLogic rules
    Services/{Service}.php                  # Business services
  constants/{constant_name}/filename.php    # Constants extensions
  modules/{Module}/api/routes/              # Module-owned routes
```

### Vue custom/ structure
```
vue/src/custom/
  components/Fields/{type}/                 # Custom field types
    {type}.detail.vue
    {type}.edit.vue
    {type}.list.vue
  router/routes.ts                          # Custom routes
  store/{storeName}.ts                      # Custom Pinia stores
  views/{ViewName}/                         # Custom pages
  drawers/{drawerName}.drawer.ts            # Custom drawers
  business/BeanActions/Actions/             # Custom bean actions
  api/{feature}.api.ts                      # Custom API wrappers
  styles/custom.scss                        # Custom styles
```

## CustomLoader Pattern (API)

Create a class in `api/custom/app/` with `MintHCM\Custom\Api\...` namespace extending a core class. The system auto-loads it via `CustomLoader::getObject()`.

```php
// api/custom/app/Controllers/ModuleController.php
namespace MintHCM\Custom\Api\Controllers;

use MintHCM\Api\Controllers\ModuleController as Base;

class ModuleController extends Base
{
    public function list($request, $response)
    {
        // Custom logic before
        $response = parent::list($request, $response);
        // Custom logic after
        return $response;
    }
}
```

## ConstantsLoader Pattern (API)

Add files to `api/custom/constants/{constant_name}/` to extend constants. Files return arrays that are merged with core.

```php
// api/custom/constants/module_icons/project_modules.php
<?php
return [
    'con_BankAccounts' => 'account_balance',
    'con_Invoices' => 'receipt',
];
```

Available constants: `module_icons`, `quick_create`, `legacy_views`, `module_group`, and others.

## Extension Framework Summary

| Mechanism | Use When |
|-----------|----------|
| Ext/Vardefs/ | Adding fields to existing modules |
| logic_hooks.php | Server-side events (before_save, after_save, etc.) |
| MintLogic logicdefs | Dynamic form behavior (visibility, required, readonly, validation) |
| CustomLoader | Overriding/extending core API classes |
| ConstantsLoader | Adding module icons, quick create entries, legacy view flags |
| custom/app/Routes/ | New API endpoints |
| vue/src/custom/ | Frontend extensions (fields, views, stores) |

## Project Organization

- **MODULE_PREFIX** (`minthcm-project.yaml`): used for all new module names (e.g., `con_BankAccounts`). The prefix appears ONLY in module directory/file names (`legacy/modules/con_BankAccounts/con_BankAccounts.php`), NOT in PHP/TS class names — namespaces already define project ownership.
- **PROJECT_NAME** (`minthcm-project.yaml`): used for directories/namespaces where path is not forced by framework:
  - `api/custom/{ProjectName}/Services/`
  - `api/custom/{ProjectName}/Helpers/`
  - Namespace: `MintHCM\Custom\{ProjectName}\Services\...`
- **Framework-mandated paths** (cannot use PROJECT_NAME): vardefs in `Ext/Vardefs/`, metadata in `metadata/`, routes in `Routes/routes/`, constants in `constants/`

## Extension File Naming Convention

When creating files in Extension directories (`legacy/custom/Extension/modules/**/Ext/**/*` or `legacy/custom/Extension/application/Ext/**/*`), use:

```
{ModuleName}.{ProjectName}.php
```

**Rules**:
- One file per module per project — do NOT create new files for each user story
- The file aggregates all changes for that module within the project
- Same convention applies to application-level extensions

**Examples**:
```
legacy/custom/Extension/modules/Contacts/Ext/Vardefs/Contacts.ConVista.php
legacy/custom/Extension/modules/Contacts/Ext/Language/en_us.Contacts.ConVista.php
legacy/custom/Extension/modules/Schedulers/Ext/ScheduledTasks/Schedulers.ConVista.php
legacy/custom/Extension/application/Ext/Language/en_us.ConVista.php
legacy/custom/Extension/application/Ext/Include/ConVista.php
```

## Common Mistakes

1. **Editing core files directly** -- always use `custom/` directories. Core files get overwritten on upgrade.
2. **Editing Doctrine entities manually** -- entities are auto-generated from vardefs during Quick Repair. Changes will be lost.
3. **Forgetting Quick Repair after vardefs changes** -- new fields won't appear in the database or API until you run Admin -> Repair -> Quick Repair and Rebuild.
4. **Forgetting `npm run build:repo` after Vue changes** -- frontend changes won't be visible in production until built.
5. **Using wrong namespace in custom API classes** -- must be `MintHCM\Custom\Api\...` not `MintHCM\Api\...`.
