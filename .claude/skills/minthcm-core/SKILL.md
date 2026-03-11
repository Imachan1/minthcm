---
name: minthcm-core
version: 1.0.0
description: "Skill for MintHCM core product development. Use when working in the core MintHCM repository: adding modules, fields, views, API endpoints, middleware, Vue field types, MintLogic mechanisms, changing framework architecture, designing extension points, writing data migrations. Trigger: new module, new field, new field type, API endpoint, Doctrine entity, middleware, MintLogic hook, core refactor, entity generation, Quick Repair."
---

# MintHCM Core Development

Skill for developing the MintHCM product itself. MintHCM is a 3-layer HCM system: legacy SuiteCRM backend, Slim 4 REST API with Doctrine ORM, and Vue 3 frontend. This skill covers building core mechanisms, adding modules, fields, endpoints, and extension points that client deployments will use.

## Task Routing

| Task | Reference file |
|------|---------------|
| Understand architecture, request lifecycle, design patterns | [architecture.md](references/architecture.md) |
| Vardefs, entities, repositories, QueryBuilder, EntityManager | [data-layer.md](references/data-layer.md) |
| Routes, controllers, middlewares, parameter validation, testing | [api-layer.md](references/api-layer.md) |
| Vue components, field system, stores, composables, build | [vue-layer.md](references/vue-layer.md) |
| Legacy modules, metadata, beans, logic hooks, ACL | [legacy-layer.md](references/legacy-layer.md) |
| MintLogic rules, formulas, validators, migration from View Tools | [mintlogic.md](references/mintlogic.md) |
| Coding standards, namespaces, extensibility, documentation rules | [contribution-rules.md](references/contribution-rules.md) |

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

## Key Rules (always follow)

1. **Every new feature must be extensible via `custom/`** -- use `CustomLoader::getObject()` instead of direct `new`, support `custom/` overrides in all layers.
2. **Entities are auto-generated from vardefs** -- never edit code inside `// Auto-generated Section...` markers. Add custom methods outside those sections.
3. **UI text goes in language files** -- never hardcode user-facing strings. Use `$mod_strings`, `$app_strings` in legacy; `languagesStore.label()` in Vue.
4. **After vardefs changes, run Quick Repair and Rebuild** -- Admin > Repair > Quick Repair and Rebuild to regenerate entities.
5. **Keep documentation up to date** -- update `api/documentation/` and `vue/documentation/` when adding new patterns or changing existing ones.
6. **Read existing docs before implementing** -- always check `api/documentation/README.md` and `vue/README.md` for established patterns.
7. **No module prefix in core** -- core modules are NOT prefixed (e.g., `Salaries`, not `con_Salaries`). Prefixes are for project-level customizations only.
