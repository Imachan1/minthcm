---
name: minthcm-project
version: 1.0.0
description: "Skill for MintHCM client deployments. Use for customization: adding fields, modules, relationships, logic hooks, schedulers, custom API routes, custom Vue components, configuring views (recordviewdefs, listviewdefs, subpaneldefs), imports, integrations. PRIMARY RULE: Avoid modifying core files - always prefer custom/. Modify core only when there is no other way. Trigger: custom field, custom module, customization, deployment, implementation, recordviewdefs, listviewdefs, subpaneldefs, logic hook, scheduler, dropdown, Extension framework."
---

# MintHCM Project Customization Skill

This skill guides developers implementing MintHCM for specific clients. All customizations go through the `custom/` directories to keep the core codebase untouched and upgradeable.

## Project Configuration

Every project should have a `minthcm-project.yaml` config file in `.claude/` (copy from `.claude/minthcm-project.yaml.example`):

```yaml
PROJECT_NAME: "ConVista"    # Used as filename segment in Extension Framework (e.g., Contacts.ConVista.php)
MODULE_PREFIX: "con_"       # Prefix for new custom modules (e.g., con_BankAccounts)
```

- **MODULE_PREFIX**: every new module gets this prefix (e.g., `con_BankAccounts`, `con_Invoices`)
- **PROJECT_NAME**: used as the filename segment in the SuiteCRM Extension Framework (`legacy/custom/Extension`). Example: `Contacts.ConVista.php`.

## Core Mode vs Project Mode

**Before starting any work**, check whether `.claude/minthcm-project.yaml` exists and has non-empty `PROJECT_NAME` and `MODULE_PREFIX`:

| Condition | Mode | Consequences |
|-----------|------|--------------|
| `minthcm-project.yaml` missing OR `PROJECT_NAME`/`MODULE_PREFIX` empty | **Core mode** | Use `minthcm-core` skill instead — no module prefix, English labels only |
| `minthcm-project.yaml` present with filled values | **Project mode** | Use this skill — apply `MODULE_PREFIX`, create both EN and other languages labels |

If you detect **core mode**, stop and inform the user that `minthcm-core` skill applies, and that modules should be created without a prefix and with English-only language files.

## Routing Table

| Task | Reference File |
|------|---------------|
| Understanding customization philosophy, custom/ directories, extension patterns | [customization-overview.md](references/customization-overview.md) |
| Adding fields, dropdowns, relationships, creating modules | [data-model.md](references/data-model.md) |
| Logic hooks, MintLogic, validators, schedulers | [business-logic.md](references/business-logic.md) |
| Custom API routes, controllers, services, middlewares, constants | [api-extensions.md](references/api-extensions.md) |
| Custom Vue components, field types, routes, stores, styles | [vue-extensions.md](references/vue-extensions.md) |
| recordviewdefs, listviewdefs, eslistviewdefs, subpaneldefs, BeanActions | [views-and-layouts.md](references/views-and-layouts.md) |
| Build, deploy, Quick Repair, caching, environment | [packaging.md](references/packaging.md) |

## Skill Selection Decision Tree

Use this quick check before starting implementation:

1. Is this change client-specific (one deployment only), and can it live in `custom/`?
  - **Yes** -> use **`minthcm-project`**.
  - **No** -> go to step 2.
2. Does this change add or modify platform capability used by many deployments (core mechanism, extension point, shared API behavior, framework architecture)?
  - **Yes** -> use **`minthcm-core`**.
  - **No** -> go to step 3.
3. Is there no safe customization path in `custom/`, so core must be changed to enable extensibility?
  - **Yes** -> use **`minthcm-core`** (and design it to be extensible for future project customizations).
  - **No** -> use **`minthcm-project`**.

Default rule: start with `minthcm-project`; move to `minthcm-core` only when platform-level change is required.

## Key Rules

1. **AVOID modifying core files** -- always prefer `custom/`. Modify core only when there is absolutely no other way.
2. **Three custom/ locations**:
   - `legacy/custom/` -- PHP backend customizations (vardefs, logic hooks, metadata, language files)
   - `api/custom/` -- REST API extensions (routes, controllers, entities, services, constants, MintLogic)
   - `vue/src/custom/` -- Vue frontend extensions (field types, routes, stores, views, styles)
3. **Custom API namespace**: `MintHCM\Custom\Api\...` — ALL files in `api/custom/` use this namespace, without exception (controllers, entities, repositories, middlewares, services, etc.)
4. **After vardefs changes**: Admin -> Repair -> Quick Repair and Rebuild (regenerates Doctrine entities)
5. **After Vue changes**: `cd vue && npm run build:repo` (builds and copies dist to `../assets/`)
6. **Doctrine entities are auto-generated** from vardefs -- never edit entity files directly
7. **Use MODULE_PREFIX** for all new custom modules — prefix goes ONLY in module directory/file names (`legacy/modules/con_BankAccounts/con_BankAccounts.php`), NOT in class names (namespaces handle project scoping)
8. **Use PROJECT_NAME** in the SuiteCRM Extension Framework file names (`legacy/custom/Extension`). Each extension file is named `{ModuleName}.{ProjectName}.php` — one file per module per project, aggregating all changes for that scope. Example: `legacy/custom/Extension/modules/Contacts/Ext/Vardefs/Contacts.ConVista.php`. Same pattern for `legacy/custom/Extension/application/Ext/...`
9. **Language files in Extension Framework require a language code prefix**: `{lang}.{ModuleName}.{ProjectName}.php`. Example: `en_us.Employees.ConVista.php`, `pl_PL.Employees.ConVista.php`. A file without the prefix (e.g., `Employees.ConVista.php`) will **not** be loaded by Sugar's language merge.
10. **New project modules go in `legacy/modules/`**, not `legacy/custom/modules/`. The `legacy/custom/modules/` directory is only for overriding/extending existing core modules. Register new modules via `legacy/custom/Extension/application/Ext/Include/{ProjectName}.php`.
