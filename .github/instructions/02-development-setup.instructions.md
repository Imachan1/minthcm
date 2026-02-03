---
applyTo:
    - ".github/**"
    - "README.md"
    - "docker/**"
    - "scripts/**"
    - "vue/package.json"
    - "vue/vite.config.*"
    - "vue/.env*"
    - "api/composer.json"
    - "api/phpunit.xml"
    - "MintCLI"
---

# Development Setup & Workflows

## Prerequisites

### Required Software

- **Node.js**: 21.x or higher
- **PHP**: 8.2 or higher
- **Composer**: 2.x
- **MySQL/MariaDB**: 5.7+ / 10.3+
- **Git**: Latest version

### Recommended Tools

- **VS Code** with extensions:
  - Volar (Vue Language Features)
  - PHP Intelephense
  - ESLint
  - Prettier
- **Postman** or similar for API testing
- **MySQL Workbench** or TablePlus for database management

## Frontend Setup

### Initial Setup

```bash
cd vue
npm install
cp .env.example .env
```

### Environment Configuration

Edit `vue/.env`:

```bash
# Backend API URL (must match your backend instance)
PROXY_URL=http://localhost:8080/your-instance-name

# OAuth2 client secret (generate with MintCLI)
CLIENT_SECRET=your_client_secret_here

# Optional: Development server port
VITE_PORT=5173

# Optional: Enable source maps in production
VITE_SOURCE_MAP=false
```

### Generate CLIENT_SECRET

From repository root:

```bash
./MintCLI oauth2:repairFrontend
```

Copy the generated secret to `vue/.env`.

### Development Server

```bash
cd vue

# Standard development server (localhost:5173)
npm run dev

# Expose to network (for mobile testing or Docker)
npm run dev -- --host 0.0.0.0

# Specify custom port
npm run dev -- --port 3000
```

**Features**:
- Hot Module Replacement (HMR) - instant updates without refresh
- TypeScript type checking
- ESLint linting
- Proxy to backend API (configured in vite.config.ts)

### Building for Production

```bash
cd vue

# Standard build (outputs to vue/dist/)
npm run build

# Build and copy to repository root
npm run build:repo
```

**build:repo** does:
1. Builds the SPA to `vue/dist/`
2. Removes old assets from root `assets/` directory
3. Copies new build assets to root `assets/`
4. Removes root `index.html` (served by PHP backend)

Use `build:repo` when packaging the frontend with the PHP application.

### Code Quality

```bash
# Lint JavaScript/TypeScript/Vue files
npm run lint

# Auto-fix linting issues
npm run lint -- --fix

# Type check without emitting files
npx vue-tsc --noEmit

# Format code with Prettier
npm run format
```

## Backend Setup

### Initial Setup

```bash
cd api
composer install
```

### Environment Configuration

Backend configuration files in `api/configs/` (NOT version controlled):

**api/configs/database.php**:
```php
<?php
return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'minthcm',
    'username' => 'root',
    'password' => 'your_password',
    'charset' => 'utf8mb4',
];
```

**api/configs/oauth2.php**:
```php
<?php
return [
    'client_id' => 'minthcm-frontend',
    'client_secret' => 'your_client_secret',  // Same as vue/.env CLIENT_SECRET
    'access_token_lifetime' => 3600,
    'refresh_token_lifetime' => 86400,
];
```

### Development Server

**Option 1: PHP Built-in Server**:
```bash
cd api
php -S localhost:8080
```

**Option 2: Apache/Nginx**:
Configure virtual host pointing to repository root with `index.php` as entry point.

**Option 3: Docker**:
```bash
cd docker
docker-compose up
```

### Testing

```bash
cd api

# Run all tests
./vendor/bin/phpunit

# Alternative test runner
php runTests.php

# Run specific test
./vendor/bin/phpunit --filter testMethodName

# Run test suite
./vendor/bin/phpunit --testsuite Unit

# With coverage
./vendor/bin/phpunit --coverage-html coverage/
```

### Code Quality

```bash
# Check code style (if configured)
./vendor/bin/phpcs

# Fix code style
./vendor/bin/phpcbf

# Static analysis (if configured)
./vendor/bin/phpstan analyze
```

## Common Workflows

### Adding a New Module

1. **Define module** in `modules/{Module}/vardefs.php`:
```php
$dictionary['{Module}'] = [
    'table' => '{module}_table',
    'fields' => [
        'id' => [
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ],
        'name' => [
            'name' => 'name',
            'type' => 'string',
            'len' => 255,
            'required' => true,
        ],
        // ... more fields
    ],
];
```

2. **Run Quick Repair** in Admin → Repair → "Quick Repair and Rebuild"
   - Generates `api/app/Entities/{Module}.php`
   - Creates database table if needed

3. **Add routes** in `api/app/Routes/{Module}.php`:
```php
return [
    [
        'method' => 'GET',
        'path' => '/{Module}',
        'callable' => [\MintHCM\App\Controllers\{Module}Controller::class, 'list'],
    ],
    // ... more routes
];
```

4. **Create controller** in `api/app/Controllers/{Module}Controller.php`:
```php
namespace MintHCM\App\Controllers;

class {Module}Controller
{
    public function list($request, $response) {
        // Implementation
    }
}
```

5. **Frontend automatically works** if using standard views and field types

### Adding a Custom Field Type

1. **Create field components** in `vue/src/custom/components/Fields/{type}/`:

```vue
<!-- {type}.edit.vue -->
<template>
  <v-text-field
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    :label="label"
    :required="required"
    :disabled="disabled"
  />
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'
defineProps<FieldProps>()
defineEmits(['update:modelValue'])
</script>
```

```vue
<!-- {type}.detail.vue -->
<template>
  <div>{{ modelValue }}</div>
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'
defineProps<FieldProps>()
</script>
```

2. **Define in vardefs**:
```php
$dictionary['Product']['fields']['custom_field'] = [
    'name' => 'custom_field',
    'type' => '{type}',  // Match component folder name
    'label' => 'LBL_CUSTOM_FIELD',
];
```

3. **Run Quick Repair**

### Adding Form Logic

Create `modules/{Module}/logicdefs.php`:

```php
$logicdefs['{Module}'] = [
    [
        'hook' => 'CHANGE',
        'trigger' => 'status',
        'condition' => "equals(field('status'), 'Completed')",
        'actions' => [
            [
                'type' => 'REQUIRED',
                'target' => 'completion_date',
                'value' => true,
            ],
            [
                'type' => 'READONLY',
                'target' => 'status',
                'value' => true,
            ],
        ],
    ],
];
```

See [MintLogic Guide](30-mintlogic.md) for comprehensive examples.

### Extending Core Functionality

**Backend**:

1. Create `api/custom/app/Controllers/{Module}Controller.php`:
```php
namespace MintHCM\Custom\App\Controllers;

use MintHCM\App\Controllers\{Module}Controller as Base;

class {Module}Controller extends Base
{
    public function customMethod($request, $response) {
        // Your custom logic
    }
}
```

2. Add route in `api/custom/app/Routes/{Module}.php`:
```php
return [
    [
        'method' => 'POST',
        'path' => '/{Module}/custom-action',
        'callable' => [\MintHCM\Custom\App\Controllers\{Module}Controller::class, 'customMethod'],
    ],
];
```

**Frontend**:

1. Create `vue/src/custom/views/CustomView.vue`
2. Add route in `vue/src/custom/router/routes.ts`:
```typescript
export const customRoutes = [
  {
    path: '/custom-view',
    component: () => import('@/custom/views/CustomView.vue'),
    meta: { auth: true }
  }
]
```

### Database Migrations

**After changing vardefs**:

1. Navigate to Admin → Repair
2. Click "Quick Repair and Rebuild"
3. Review SQL statements
4. Execute SQL (or manually run in database)
5. Clear caches:
```bash
rm -rf api/var/cache/doctrine/*
```

### Debugging

**Frontend**:

```typescript
// Console logging
console.log('Debug:', variable)

// Vue DevTools
// Install browser extension, inspect components/stores

// Network inspector
// Check API calls in browser DevTools → Network tab
```

**Backend**:

```php
// Error logging
error_log('Debug: ' . print_r($variable, true));

// Xdebug
// Configure in php.ini, use IDE breakpoints

// API response debugging
return $response->withJson([
    'debug' => $debugData,
    'result' => $result
]);
```

### Performance Profiling

**Frontend**:

```bash
# Analyze bundle size
npm run build -- --analyze

# Lighthouse audit
# Run in Chrome DevTools → Lighthouse tab
```

**Backend**:

```php
// Time execution
$start = microtime(true);
// ... code ...
$duration = microtime(true) - $start;
error_log("Execution time: {$duration}s");

// Query logging
// Enable in Doctrine configuration
```

## Environment-Specific Configuration

### Development

```bash
# Frontend
VITE_SOURCE_MAP=true
VITE_DEBUG=true

# Backend
# Enable error display
display_errors = On
error_reporting = E_ALL
```

### Staging

```bash
# Frontend
VITE_SOURCE_MAP=false
VITE_DEBUG=false

# Backend
# Log errors, don't display
display_errors = Off
log_errors = On
```

### Production

```bash
# Frontend
VITE_SOURCE_MAP=false
VITE_DEBUG=false

# Backend
# Strict error handling
display_errors = Off
log_errors = On
error_reporting = E_ALL & ~E_DEPRECATED
```

## Troubleshooting Setup Issues

**npm install fails**:
```bash
# Clear cache and retry
rm -rf node_modules package-lock.json
npm cache clean --force
npm install
```

**composer install fails**:
```bash
# Update composer
composer self-update

# Clear cache
composer clear-cache

# Retry
composer install
```

**Permission issues (Linux/Mac)**:
```bash
# Fix ownership
sudo chown -R $USER:$USER .

# Fix permissions
chmod -R 755 api/var
chmod -R 755 legacy/cache
```

**Port already in use**:
```bash
# Find process using port
# Windows:
netstat -ano | findstr :5173
taskkill /PID <pid> /F

# Linux/Mac:
lsof -i :5173
kill -9 <pid>
```

---

**Next Steps**:
- [Customization Patterns](03-customization.md) - Learn safe extension methods
- [Vue Frontend Guide](04-frontend-vue.md) - Frontend development
- [PHP Backend Guide](08-backend-php.md) - Backend development
