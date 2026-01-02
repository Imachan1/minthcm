---
applyTo:
    - "api/**"
    - "vue/**"
    - "docker/**"
    - "scripts/**"
---

# Troubleshooting Guide

**Version**: 2.0  
**Last Updated**: 2026-01-02

Common issues and solutions.

## Frontend Issues

### PROXY_URL Connection Errors

**Problem**: Frontend can't reach backend

**Solution**:
```bash
# Check vue/.env
VITE_PROXY_URL=http://localhost:8080

# Restart dev server
cd vue
npm run dev
```

### TypeScript Errors

**Problem**: Type checking fails

**Solution**:
1. Install Volar extension
2. Disable Vetur extension
3. Restart VS Code
4. Run `npm run type-check`

### Hot Reload Not Working

**Problem**: Changes don't reflect

**Solution**:
1. Hard refresh browser (Ctrl+Shift+R)
2. Clear browser cache
3. Restart dev server
4. Check for console errors

### Build Fails

**Problem**: `npm run build` fails

**Solution**:
1. Fix TypeScript errors: `npm run type-check`
2. Fix ESLint errors: `npm run lint`
3. Clear node_modules: `rm -rf node_modules && npm install`
4. Clear Vite cache: `rm -rf .vite`

## Backend Issues

### Entity Not Found

**Problem**: Doctrine can't find entity

**Solution**:
1. Run "Quick Repair and Rebuild" in Admin → Repair
2. Clear Doctrine cache: `rm -rf api/var/cache/doctrine`
3. Check namespace in entity file
4. Check vardefs in `modules/{Module}/vardefs.php`

### Route Not Found

**Problem**: 404 on API endpoint

**Solution**:
1. Check route file exists: `api/app/Routes/{Module}.php`
2. Verify path and method match
3. Check controller namespace
4. Restart PHP server

### Dependency Injection Error

**Problem**: Container can't resolve class

**Solution**:
1. Check class namespace
2. Verify constructor dependencies exist
3. Run `composer dump-autoload`
4. Check PHP-DI definitions in `api/configs/container.php`

### Legacy Code Fails

**Problem**: Error in legacy code

**Solution**:
```php
// Wrap in LegacyConnector
use MintHCM\Lib\LegacyConnector;

$result = LegacyConnector::getInstance()->doAction(function () {
    // Legacy code here
    $bean = BeanFactory::getBean('Employees', $id);
    return $bean;
});
```

## Database Issues

### Migration Fails

**Problem**: Database schema out of sync

**Solution**:
1. Run "Quick Repair and Rebuild"
2. Check SQL output
3. Execute SQL manually if needed
4. Verify table exists in database

### Query Performance

**Problem**: Slow queries

**Solution**:
1. Add indexes in vardefs
2. Use eager loading in Doctrine
3. Optimize QueryBuilder queries
4. Check EXPLAIN output

## Authentication Issues

### JWT Token Invalid

**Problem**: 401 Unauthorized

**Solution**:
1. Clear localStorage
2. Login again
3. Check CLIENT_SECRET in `vue/.env`
4. Regenerate secret: `./MintCLI oauth2:repairFrontend`

### Session Expires

**Problem**: Frequent logouts

**Solution**:
1. Check token expiration in backend
2. Implement token refresh
3. Verify system clock is synchronized

## Debugging Tools

### Frontend

```bash
# Vue DevTools (browser extension)
# Console logs
console.log(bean.attributes.value)

# Network tab (check API calls)
```

### Backend

```php
// Debug logging
error_log(print_r($data, true));

// Xdebug
// Configure in php.ini

// SQL logging (Doctrine)
// Enable in api/configs/database.php
```

### Common Commands

```bash
# Clear all caches
cd api
rm -rf var/cache/*

# Rebuild entities
# Admin → Repair → Quick Repair and Rebuild

# Reset permissions
chmod -R 755 api
chmod -R 777 api/var

# Check PHP errors
tail -f api/var/logs/error.log
```

## Still Stuck?

1. Check documentation in `vue/documentation/` and `api/documentation/`
2. Review existing code for similar patterns
3. Check git history for related changes
4. Search codebase for error messages

**Full Documentation**: `api/documentation/*.md`, `vue/documentation/*.md`

---

**Related**: [Development Setup](02-development-setup.md), [Testing](14-testing.md), [Performance](16-performance.md)
