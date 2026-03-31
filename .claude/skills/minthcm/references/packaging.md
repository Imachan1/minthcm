# Packaging, Build, and Deployment

## Environment Requirements

| Component | Version |
|-----------|---------|
| PHP | 8.2 |
| MySQL | 8.0 / MariaDB 10.5+ |
| ElasticSearch | 7.9 |
| Node.js | ~21 |

## Vue Frontend Build

### Production build (deploy)

```bash
cd vue
npm install                 # Install dependencies (first time or after package.json changes)
npm run build:repo          # Build and copy dist to ../assets/
```

`build:repo` does two things:
1. Runs Vite production build
2. Copies the `dist/` output to `../assets/` (the path served by the legacy PHP app)

### Development server (local dev)

```bash
cd vue
npx vite --host 0.0.0.0    # Hot-reload dev server on all interfaces
```

Requires `PROXY_URL` in `vue/.env` pointing to the backend instance:

```
PROXY_URL=http://localhost:8080
```

## Quick Repair and Rebuild

**When to run**: after any vardefs, metadata, or language file changes.

**How**: Admin -> Repair -> Quick Repair and Rebuild

**What it does**:
- Regenerates Doctrine ORM entities from vardefs
- Updates database schema (adds new columns/tables)
- Rebuilds language files cache
- Rebuilds extension framework cache
- Regenerates metadata cache

**After Quick Repair**: if it shows SQL statements, click "Execute" to apply schema changes.

## Doctrine Entity Regeneration

Entities in `api/app/Entities/` are auto-generated from vardefs during Quick Repair. **Never edit these files manually.**

To force entity regeneration:

```bash
rm -rf api/cache/doctrine/*
```

Then run Quick Repair from Admin panel.

## Cache Clearing

### API cache (Doctrine + routes)

```bash
rm -rf api/cache/doctrine/*
rm -rf api/cache/routes/*
```

### Legacy cache

```bash
rm -rf legacy/cache/modules/*
rm -rf legacy/cache/themes/*
rm -rf legacy/cache/smarty/*
```

### Full cache clear

```bash
rm -rf api/cache/doctrine/* api/cache/routes/*
rm -rf legacy/cache/modules/* legacy/cache/themes/* legacy/cache/smarty/*
```

Then run Quick Repair from Admin panel to rebuild.

## Docker (Demo/Testing)

```bash
cd docker
docker compose up -d        # Start containers
docker compose logs -f      # Follow logs
docker compose down         # Stop containers
```

Docker setup is for demo and testing purposes only, not production.

## Deployment Checklist

### After vardefs/metadata changes

1. Upload changed files to server
2. Admin -> Repair -> Quick Repair and Rebuild
3. Execute any displayed SQL statements
4. Test affected modules

### After Vue frontend changes

1. Upload changed Vue source files
2. `cd vue && npm install && npm run build:repo`
3. Clear browser cache or hard-refresh
4. Test affected views

### After API changes (routes, controllers, services)

1. Upload changed files to `api/custom/`
2. Clear API cache: `rm -rf api/cache/doctrine/* api/cache/routes/*`
3. Test affected endpoints

### Full deployment

1. Upload all changed files
2. `cd vue && npm install && npm run build:repo`
3. Admin -> Repair -> Quick Repair and Rebuild
4. Execute SQL if shown
5. Clear API cache: `rm -rf api/cache/doctrine/* api/cache/routes/*`
6. Clear legacy cache: `rm -rf legacy/cache/modules/* legacy/cache/themes/*`
7. Test all affected modules and views

## Version Control

Keep `custom/` directories in version control:

```
# Directories to track:
legacy/custom/modules/          # Custom vardefs, hooks, metadata
legacy/custom/Extension/        # Custom dropdowns, labels, includes
api/custom/                     # Custom API code
vue/src/custom/                 # Custom Vue code
```

For new modules (created with MODULE_PREFIX):

```
legacy/modules/{con_Module}/    # Full module directory
```

### .gitignore considerations

```gitignore
# Ignore generated files
api/cache/
legacy/cache/
vue/dist/
vue/node_modules/
assets/

# Track custom code
!legacy/custom/
!api/custom/
!vue/src/custom/
```

## Troubleshooting

### Fields not appearing after vardefs change

1. Run Quick Repair and Rebuild
2. Check for SQL statements and execute them
3. Verify field names match exactly in vardefs and metadata

### Vue changes not visible

1. Run `npm run build:repo` in `vue/`
2. Hard-refresh browser (Ctrl+Shift+R)
3. Check browser console for errors

### API routes returning 404

1. Clear route cache: `rm -rf api/cache/routes/*`
2. Verify `$routes` array syntax in route file
3. Check controller class exists and namespace is correct
4. Run `php -l` on route file to check syntax

### Doctrine errors

1. Clear Doctrine cache: `rm -rf api/cache/doctrine/*`
2. Run Quick Repair to regenerate entities
3. Check vardefs for invalid field definitions

## Common Mistakes

1. **Deploying without Quick Repair** -- new fields/tables won't exist in DB.
2. **Deploying without `build:repo`** -- Vue changes invisible in production.
3. **Not clearing API cache after route changes** -- stale route definitions served.
4. **Running `npm run build` instead of `npm run build:repo`** -- builds but doesn't copy to assets/.
5. **Editing files in `api/cache/` or `legacy/cache/`** -- changes lost on next cache rebuild.
