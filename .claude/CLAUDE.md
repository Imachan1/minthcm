# Project: MintHCM

## System
- Type: MintHCM (open-source HCM based on SuiteCRM/SugarCRM Community Edition)
- PHP: 8.2
- Database: MySQL 8.0 / MariaDB 10.5+
- ElasticSearch: 7.9
- Node: ~21

## Project Context

MintHCM is a Human Capital Management system with a three-layer architecture:

- **`legacy/`** — SuiteCRM-based PHP backend (the "engine")
- **`api/`** — Modern PHP REST API (Slim 4 + Doctrine ORM + PHP-DI)
- **`vue/`** — Vue 3 frontend (Vite + Vuetify 3 + Pinia + TypeScript)

The root `index.php` serves legacy views. The `api/index.php` bootstraps the Slim 4 app but first loads legacy's `entryPoint.php` to initialize Sugar's global state.

Both layers have detailed technical documentation — **read it before implementing** patterns like routing, controllers, entities, field types, or customizations.

- **API**: [api/documentation/README.md](api/documentation/README.md) — routing, Doctrine ORM, controllers, legacy integration, extending via `custom/`, MintLogic, and more
- **Frontend**: [vue/README.md](vue/README.md) — field system, working with beans, customization via `vue/src/custom/`, architecture

**Keep the documentation up to date** when adding new patterns or changing how existing ones work.

## Skills

Skills are managed via `skills-manifest.yaml`.
- Check for updates: `.claude/skills/skills-sync/scripts/check-updates.sh`
- Pull updates: `.claude/skills/skills-sync/scripts/sync.sh --pull`
- Push changes: `.claude/skills/skills-sync/scripts/sync.sh --push <skill-name>`

## Architecture

### Legacy Layer (`legacy/`)
Based on SuiteCRM. Module structure:
- `legacy/modules/{ModuleName}/` — module code
  - `vardefs.php` — field definitions (the schema)
  - `metadata/recordviewdefs.php` — Vue record view layout
  - `metadata/listviewdefs.php` / `eslistviewdefs.php` — list view columns
  - `metadata/detailviewdefs.php` / `editviewdefs.php` — legacy views
  - `metadata/subpaneldefs.php` — subpanel configuration
- `legacy/custom/modules/` — customizations (override core module files here)

Key HCM modules: `Employees`, `Recruitments`, `Candidatures`, `WorkSchedules`, `Positions`, `Competencies`, `Trainings`, `Evaluations`, `Benefits`, `Delegations`, `Spots`, `Skills`.

### API Layer (`api/`)
Slim 4 REST API with Doctrine ORM and PHP-DI dependency injection.

- `api/app/Controllers/` — request handlers
- `api/app/Entities/` — Doctrine ORM entities (one per SugarCRM module)
- `api/app/Routes/` — route definitions
- `api/custom/` — customization layer (same structure, loaded after core)

### Vue Frontend (`vue/src/`)
- `views/` — page-level views: `ListView`, `DetailView`, `EditView`, `RecordView`, `LegacyView`, `DashboardView`, `UnifiedSearchView`
- `components/Fields/` — field type components (one directory per type: `varchar`, `relate`, `enum`, `date`, `file`, etc.)
- `store/` — Pinia stores (`auth`, `backend`, `modules`, `languages`, `url`, etc.)
- `api/` — typed API client wrappers
- `@` alias maps to `vue/src/`

The `LegacyView` embeds legacy PHP-rendered pages in an iframe. Whether a module uses a native Vue view or legacy view is controlled by `backend.initData.legacy_views[module]`.

## Git Workflow

### Branching strategy

| Branch type | Base branch | Naming |
|---|---|---|
| Feature (US/Epic/Spike) | `master` | `feature/{ISSUE_ID}` |
| Hotfix | `master` | `hotfix/{ISSUE_ID}` |
| Release | `master` | `release/{version}` |

`develop` — integration branch for the current release cycle; **not** a base for feature work.

`master` — always reflects production-ready code.

---

## Development Commands

### Frontend (Vue)
```bash
cd vue
npm install
npx vite --host 0.0.0.0     # Dev server (requires PROXY_URL in vue/.env)
npm run build               # Build for production
npm run build:repo          # Build and copy dist to ../assets (updates deployed assets)
```

### Docker (demo/testing only)
```bash
cd docker
docker compose up -d
docker compose logs -f
```
