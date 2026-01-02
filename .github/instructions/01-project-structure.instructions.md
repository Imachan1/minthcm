---
applyTo:
   - ".github/**"
   - "README.md"
   - "index.php"
---

# Project Structure & Architecture

**Version**: 2.0  
**Last Updated**: 2026-01-02

## Overview

MintHCM is a dual-application system: a Vue 3 SPA frontend and a PHP 8.2 REST API backend with legacy SuiteCRM integration.

## Directory Structure

```
MintHCM/
├── vue/                          # Frontend application (Vue 3 SPA)
│   ├── src/
│   │   ├── main.ts              # Application entry point
│   │   ├── router/              # Vue Router configuration (hash mode)
│   │   ├── store/               # Pinia stores (backend, auth, modules, languages)
│   │   ├── components/
│   │   │   └── Fields/          # Dynamic field system (~30 types)
│   │   │       ├── Field.vue    # Router component
│   │   │       ├── string/      # String field components
│   │   │       ├── enum/        # Dropdown field components
│   │   │       └── ...
│   │   ├── views/               # Page components (List, Detail, Edit)
│   │   ├── composables/         # Reusable composition functions
│   │   │   ├── useBean.ts       # CRUD operations
│   │   │   └── useLink.ts       # Relationships
│   │   ├── business/            # Business logic
│   │   │   ├── BeanActions/     # Single record actions
│   │   │   ├── MassActions/     # Bulk actions
│   │   │   └── SubpanelActions/ # Related records actions
│   │   ├── layouts/             # Page layout templates
│   │   ├── api/                 # Axios HTTP client
│   │   ├── custom/              # YOUR CUSTOMIZATIONS (safe from updates)
│   │   └── utils/               # Helper functions
│   ├── documentation/           # Frontend documentation (7 guides)
│   ├── public/                  # Static assets
│   ├── package.json
│   ├── vite.config.ts
│   └── .env                     # Environment variables (PROXY_URL, CLIENT_SECRET)
│
├── api/                          # Backend application (PHP 8.2 REST API)
│   ├── index.php                # API entry point
│   ├── lib/
│   │   ├── ApiManager.php       # Application bootstrap (DI, routes, services)
│   │   ├── MintLogic/           # Dynamic form logic engine
│   │   │   └── Modules/         # Module-specific logic definitions
│   │   └── CustomLoader.php    # Loads custom/ namespace classes first
│   ├── app/
│   │   ├── Controllers/         # HTTP request handlers
│   │   ├── Repositories/        # Business logic + Doctrine queries
│   │   ├── Entities/            # Doctrine entities (auto-generated from vardefs)
│   │   ├── Routes/              # Route definitions (array format)
│   │   │   ├── routes/          # Global routes
│   │   │   └── modules/         # Module-specific routes
│   │   ├── Middlewares/         # Request/response middleware (Auth, ACL, JSON parser)
│   │   └── Services/            # Business services
│   ├── utils/
│   │   ├── LegacyConnector.php  # Bridge to legacy SuiteCRM
│   │   └── BeanFactory.php      # Legacy bean factory
│   ├── constants/               # Extensible constants (version controlled)
│   │   ├── modules/             # Module definitions
│   │   ├── icons/               # Icon mappings
│   │   └── ...
│   ├── configs/                 # Runtime configuration (NOT version controlled)
│   │   ├── database.php         # Database credentials
│   │   └── ...
│   ├── custom/                  # YOUR CUSTOMIZATIONS (safe from updates)
│   │   ├── app/                 # Mirror core app/ structure
│   │   ├── lib/                 # Custom libraries
│   │   └── constants/           # Custom constants (merged with core)
│   ├── documentation/           # Backend documentation (14 guides)
│   ├── tests/                   # PHPUnit tests
│   │   ├── Unit/
│   │   └── Integration/
│   ├── var/                     # Runtime data
│   │   ├── logs/                # Application logs
│   │   └── cache/               # Doctrine cache
│   ├── vendor/                  # Composer dependencies
│   ├── composer.json
│   └── phpunit.xml
│
├── legacy/                       # SuiteCRM/SugarCRM legacy codebase
│   ├── include/                 # Legacy includes
│   ├── modules/                 # Legacy modules
│   │   └── {Module}/
│   │       ├── vardefs.php      # Field definitions (source of truth)
│   │       └── ...
│   └── custom/                  # Legacy customizations
│       └── modules/
│           └── {Module}/
│               └── Ext/
│                   ├── Vardefs/ # Custom field definitions
│                   └── Logicdefs/ # Custom form logic
│
├── modules/                      # Shared module definitions
│   └── {Module}/
│       ├── vardefs.php          # Field definitions
│       └── logicdefs.php        # Form logic definitions
│
├── MintCLI                       # Command-line tool
├── docker/                       # Docker configuration
├── tests/                        # Integration tests
├── scripts/                      # Deployment/maintenance scripts
└── .github/
    ├── copilot-instructions.md  # Main AI instructions (this system)
    ├── instructions/            # Detailed topic-specific guides
    └── prompts/                 # AI agent prompts
```

## Architecture Principles

### 1. Two-Application Design

**Frontend (Vue SPA)**:
- **Purpose**: User interface, rendering, client-side routing
- **Stack**: Vue 3, TypeScript, Vite, Vuetify, Pinia
- **Communication**: HTTP REST API calls via Axios
- **Philosophy**: Generic, metadata-driven components

**Backend (PHP API)**:
- **Purpose**: Business logic, data access, authentication
- **Stack**: Slim Framework, Doctrine ORM, PHP-DI, PSR-7
- **Communication**: JSON responses following REST principles
- **Philosophy**: Clean architecture with legacy bridge

### 2. Metadata-Driven Frontend

The frontend doesn't know about specific modules. Instead:
- Backend provides module definitions via `/api/init`
- Field types drive component selection dynamically
- Same `ListView` component works for all modules
- Form behavior controlled by backend MintLogic

**Example**: Adding a new module requires NO frontend code changes if using standard field types.

### 3. CustomLoader Pattern

The `CustomLoader` allows safe extensions without modifying core:

**Resolution order**:
1. Check `MintHCM\Custom\App\Controllers\EmployeesController`
2. If exists → use custom version
3. If not → use `MintHCM\App\Controllers\EmployeesController`

**Benefits**:
- Core files protected from modifications
- Customizations survive updates
- Clear separation of concerns

### 4. Entity Auto-Generation

Entities are the single source of truth from vardefs:

**Flow**:
```
Edit vardefs → Quick Repair & Rebuild → Entities regenerated → ORM updated
```

**Critical**: Never edit protected regions in entities. They regenerate on every repair.

### 5. Legacy Integration Bridge

**LegacyConnector** allows access to SuiteCRM code when needed:
- Switches working directory to legacy root
- Loads legacy includes and globals
- Triggers legacy hooks
- Reverts to API context after execution

**Use sparingly**: Prefer Doctrine for new code. Use legacy only for backward compatibility.

## Data Flow

### Initialization Flow

```
1. User loads application
   ↓
2. Vue app bootstraps (main.ts)
   ↓
3. Router checks authentication
   ↓
4. If authenticated → backend.init() called
   ↓
5. API /init returns:
   - Module definitions
   - Field metadata
   - ACL permissions
   - Translations
   ↓
6. Stores populated:
   - backend store (module defs)
   - modules store (field defs)
   - auth store (user)
   - languages store (translations)
   ↓
7. Application ready
```

### Record Edit Flow

```
1. User navigates to edit view
   ↓
2. useBean('Module', 'id') initialized
   ↓
3. bean.init() called
   ↓
4. API GET /Module/{id} returns:
   - Record data
   - MintLogic rules (visibility, required, readonly, etc.)
   ↓
5. bean.attributes populated
6. bean.logic populated
   ↓
7. Field components render based on:
   - Field type (defs.type)
   - View mode ('edit')
   - Logic rules (bean.logic)
   ↓
8. User changes field
   ↓
9. bean.updateFields({ field: value }) called
   ↓
10. If logic trigger field → API re-evaluates logic
    ↓
11. Updated logic applied to form
    ↓
12. User saves
    ↓
13. bean.save() called
    ↓
14. API PUT /Module/{id} with validation
    ↓
15. Success → bean.syncAttributes updated
16. Failure → bean.errorMessages populated
```

## Communication Patterns

### Frontend → Backend

**Authentication**:
```
POST /login
Body: { username, password }
Response: { access_token, refresh_token }
→ Store in localStorage
→ Attach to all subsequent requests via Axios interceptor
```

**CRUD Operations**:
```
GET    /Employees        → List records
POST   /Employees        → Create record
GET    /Employees/{id}   → Retrieve record
PUT    /Employees/{id}   → Update record
DELETE /Employees/{id}   → Delete record
```

**Special Endpoints**:
```
POST   /api/init         → Bootstrap application
GET    /Employees/{id}/logic → Evaluate MintLogic
POST   /Employees/{id}/relationships/{name} → Manage relationships
```

### Response Formats

**Success Response**:
```json
{
  "id": "123",
  "first_name": "John",
  "last_name": "Doe",
  "logic": {
    "visible": ["field1", "field2"],
    "required": ["email"],
    "readonly": ["created_date"],
    "updates": {},
    "validation": {},
    "options": {}
  }
}
```

**Error Response**:
```json
{
  "error": "Validation failed",
  "errors": {
    "email": "Email is required",
    "phone": "Invalid phone format"
  }
}
```

## Extension Points

### Frontend Extension Points

1. **Custom Fields**: `vue/src/custom/components/Fields/{type}/`
2. **Custom Views**: `vue/src/custom/views/{Name}/`
3. **Custom Routes**: `vue/src/custom/router/routes.ts`
4. **Custom Stores**: `vue/src/custom/store/{name}.ts`
5. **Custom Actions**: `vue/src/custom/business/BeanActions/Actions/`
6. **Custom Composables**: `vue/src/custom/composables/`

### Backend Extension Points

1. **Custom Controllers**: `api/custom/app/Controllers/`
2. **Custom Repositories**: `api/custom/app/Repositories/`
3. **Custom Routes**: `api/custom/app/Routes/`
4. **Custom Services**: `api/custom/lib/Services/`
5. **Custom Middlewares**: `api/custom/app/Middlewares/`
6. **Custom Entities**: `api/custom/app/Entities/` (extend only)
7. **Custom Constants**: `api/custom/constants/` (merged with core)
8. **Custom Logic**: `custom/modules/{Module}/Ext/Logicdefs/`

## Key Files

### Must-Know Frontend Files

- [vue/src/main.ts](vue/src/main.ts) - Application entry point
- [vue/src/router/index.ts](vue/src/router/index.ts) - Route definitions
- [vue/src/store/backend.ts](vue/src/store/backend.ts) - Bootstrap store
- [vue/src/composables/useBean.ts](vue/src/composables/useBean.ts) - CRUD operations
- [vue/src/components/Fields/Field.vue](vue/src/components/Fields/Field.vue) - Field router
- [vue/vite.config.ts](vue/vite.config.ts) - Build configuration

### Must-Know Backend Files

- [api/index.php](api/index.php) - API entry point
- [api/lib/ApiManager.php](api/lib/ApiManager.php) - Bootstrap
- [api/lib/CustomLoader.php](api/lib/CustomLoader.php) - Custom namespace loader
- [api/lib/MintLogic](api/lib/MintLogic) - Form logic engine
- [api/utils/LegacyConnector.php](api/utils/LegacyConnector.php) - Legacy bridge
- [api/composer.json](api/composer.json) - Dependencies

---

**Next Steps**: 
- [Development Setup](02-development-setup.md) - Set up your environment
- [Customization Patterns](03-customization.md) - Learn safe extension patterns
