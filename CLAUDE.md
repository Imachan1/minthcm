# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Primary Instructions

**Always read `.github/copilot-instructions.md` before starting any work.** It is the authoritative source for coding conventions, architecture patterns, do/don't rules, and security guidance. It links to 18 detailed sub-files in `.github/instructions/` covering every topic (routing, MintLogic, field system, legacy migration, etc.).

---

## Project Overview

MintHCM is an open-source Human Capital Management (HCM) system built on top of SuiteCRM/SugarCRM CE. It is a three-layer application:

1. **`legacy/`** — PHP backend forked from SuiteCRM (SugarCRM-based). Handles most business logic, all 180+ modules, and serves traditional PHP views via Apache/Slim v3.
2. **`api/`** — Modern REST API layer (PHP 8, Slim v4, Doctrine ORM, PHP-DI). Provides a clean interface for the Vue frontend.
3. **`vue/`** — Single-page application frontend (Vue 3, TypeScript, Vite, Vuetify, Pinia).

The root `index.php` and static assets (`assets/`, `favicon.ico`, etc.) are served from the repo root. Built Vue output is deployed to the repo root via `build:repo`.

## Development Commands

### Vue Frontend (`vue/`)

Requires Node.js v21.x.

```bash
cd vue
npm install
cp .env.example .env          # Set PROXY_URL=http://localhost/<instance>
npm run dev                   # Dev server with HMR (proxies /api and /legacy to backend)
npm run build                 # Production build to dist/
npm run build:repo            # Build and copy dist/* to repo root (replaces ../assets)
npm run lint                  # ESLint
```

### API Layer (`api/`)

```bash
cd api
composer install
./vendor/bin/phpunit                              # Run all API tests
./vendor/bin/phpunit tests/AuthTest.php           # Run specific test file
./vendor/bin/phpunit --filter testLogin tests/AuthTest.php  # Run specific test method
php runTests.php                                  # Alternative test runner
```

### Legacy Layer (`legacy/`)

```bash
cd legacy
composer install
./vendor/bin/phpcs --standard=phpcs.xml <file>   # PHP_CodeSniffer (PSR2 standard)
./vendor/bin/phpunit                              # Run legacy PHPUnit tests
```

### Behat Integration Tests (`tests/`)

```bash
cd tests/Behat
composer install
# See mint_config.yml for configuration
```

### Docker (for local full-stack)

```bash
cd docker
cp .env.example .env   # adjust ports and credentials
docker compose up -d
docker compose logs -f
```

Access at `http://localhost` (default admin/minthcm).

## Architecture

### Request Flow

All traffic enters through `index.php` at the repo root, which routes to:
- `/api/` → Slim v4 app (`api/index.php`)
- `/legacy/` → SugarCRM-based PHP app (`legacy/index.php`)
- `/` → Vue SPA (`index.html`)

### API Layer (`api/`)

- **Framework**: Slim v4 with PHP-DI for dependency injection
- **ORM**: Doctrine ORM for database access
- **Auth**: OAuth2 via `league/oauth2-server`
- **Namespaces**: `MintHCM\Api\` → `app/`, `MintHCM\Modules\` → `modules/`, `MintHCM\Lib\` → `lib/`
- **Routes**: Defined as PHP arrays in `app/Routes/routes/` and per-module in `app/Routes/modules/`. The `RouteManager` auto-discovers them.
- **Legacy integration**: `utils/LegacyConnector.php` bridges API code to legacy beans by `chdir`-ing into `legacy/` and instantiating SugarCRM classes.
- **Customization**: Mirror the `app/` structure under `custom/app/` (same for `modules/`, `lib/`) — custom code is auto-discovered and takes precedence.
- **MintLogic**: Dynamic form logic system. Definitions in `api/lib/MintLogic/Modules/{Module}/*logicdefs.php`. Controls field visibility, required state, read-only state, and validation without touching frontend code.

### Legacy Layer (`legacy/`)

Standard SuiteCRM architecture: modules under `legacy/modules/{ModuleName}/`, beans extend `SugarBean`, metadata in `metadata/`, language strings in `language/`. Customizations go in `legacy/custom/`.

The new Vue-based record views use `legacy/modules/{Module}/metadata/recordviewdefs.php` (replaces the legacy `detailviewdefs.php`/`editviewdefs.php`). The `legacy_views` key in the API init response tells the frontend which modules still use the legacy iframe-based views vs. the new Vue views.

### Vue Frontend (`vue/src/`)

- **State**: Pinia stores in `store/`. Key stores: `auth`, `backend` (init data, `legacy_views` config), `modules` (module defs/metadata), `languages`.
- **Routing**: Hash history router. Before each navigation, checks installation state, auth, wizard redirect, and whether to use Vue or legacy view for the module.
- **Views**: `ListView`, `RecordView` (detail/edit), `LegacyView` (iframe wrapping legacy PHP pages), `DashboardView`, `UnifiedSearchView`.
- **Field System**: The `<Field>` component dynamically loads `{type}.{detail|edit|list}.vue` sub-components. Field types live in `components/Fields/{type}/`.
- **Bean composables**: `useBean` for single record CRUD; `useLink` for relationships.
- **API client**: `src/api/api.ts` — axios instance with `baseURL: 'api/'` and interceptors for token refresh.
- **Customization**: Extend safely via `src/custom/` (mirrors `src/` structure). Custom field types, routes, stores, and views go here.

### Module Development Pattern

A new HCM module typically spans all three layers:
- `legacy/modules/{Module}/` — bean, metadata (vardefs, listviewdefs, recordviewdefs), language files
- `api/modules/{Module}/` — Doctrine entity, repository, routes, controllers
- `api/lib/MintLogic/Modules/{Module}/logicdefs.php` — form logic/validation
- Vue views are generic (ListView/RecordView) driven by metadata from the API

## Key Rules

### Critical (violations are hard to reverse)

1. **Never edit core files — always use `custom/`**
   - Vue: `vue/src/custom/` mirrors `vue/src/`
   - API: `api/custom/app/` mirrors `api/app/`
   - Extend core classes, never modify them: `class MyController extends \MintHCM\Api\Controllers\EmployeesController`

2. **Never edit protected regions in Doctrine entities**
   Blocks marked `BEGIN/END PROTECTED REGION` in `api/app/Entities/` are auto-regenerated. Add custom methods outside those blocks.

3. **Run Quick Repair and Rebuild after every vardef change**
   Admin → Repair → Quick Repair and Rebuild. This regenerates `api/app/Entities/` to match vardefs.

### Important (easy to overlook)

4. **`useBean` workflow — never modify `attributes` directly**
   ```typescript
   // ✅ correct
   bean.updateFields({ first_name: 'Jan' })
   // ❌ wrong
   bean.attributes.value.first_name = 'Jan'
   ```

5. **Backend drives everything** — modules, fields, ACL, form logic (MintLogic). Frontend is a rendering engine only.

6. **CustomLoader precedence** — always checks `MintHCM\Custom\...` namespace before core. Customizations are auto-discovered.

### Conventions

7. **Language**: all code in English; respond to user in their language (Polish → Polish, English → English)

8. **PHP naming**: `$snake_case` variables, `PascalCase` classes, `camelCase()` methods, `SCREAMING_SNAKE_CASE` constants

9. **TypeScript/Vue naming**: `camelCase` variables/functions, `PascalCase` components, `useXxx()` composables

## Key Configuration

- **Vue dev proxy**: Configure `PROXY_URL` in `vue/.env` to point at a running MintHCM backend.
- **Vue env variable**: `CLIENT_SECRET` is injected via `vite.config.mts` from `.env` at build time.
- **API config**: `api/configs/mint/config.php` (autoloaded by Composer).
- **Legacy config**: `legacy/config.php` (generated during install, not committed).
- **PHP standard**: PSR2 (see `legacy/phpcs.xml`), PHP 8.2 target platform for both `api/` and `legacy/`.
