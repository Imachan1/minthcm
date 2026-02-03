# MintHCM AI Coding Assistant Instructions

## 📚 Table of Contents

This is the main instructions file for AI coding assistants working on MintHCM. For detailed topic-specific guides, see the [instructions/](instructions/) directory:

**Coding Standards**:
- [Coding Standards & Conventions](instructions/00-coding-standards.instructions.md) - Language guidelines, PHP/TypeScript naming conventions

**Core Concepts**:
- [Project Structure & Architecture](instructions/01-project-structure.instructions.md)
- [Development Setup & Workflows](instructions/02-development-setup.instructions.md)
- [Customization Patterns](instructions/03-customization.instructions.md)

**Frontend (Vue)**:
- [Vue Frontend Guide](instructions/04-frontend-vue.instructions.md)
- [Field System](instructions/05-field-system.instructions.md)
- [State Management (Pinia)](instructions/06-state-management.instructions.md)
- [CRUD Operations (useBean)](instructions/07-crud-operations.instructions.md)

**Backend (PHP)**:
- [PHP Backend Guide](instructions/08-backend-php.instructions.md)
- [Routing & Controllers](instructions/09-routing-controllers.instructions.md)
- [Doctrine ORM](instructions/10-doctrine-orm.instructions.md)
- [Legacy Integration](instructions/11-legacy-integration.instructions.md)

**Business Logic**:
- [MintLogic System](instructions/12-mintlogic.instructions.md)
- [Validation & Security](instructions/13-validation-security.instructions.md)

**Development**:
- [Testing Guide](instructions/14-testing.instructions.md)
- [Troubleshooting](instructions/15-troubleshooting.instructions.md)
- [Performance Optimization](instructions/16-performance.instructions.md)

**Migration**:
- [Legacy to New View Migration](instructions/17-legacy-migration.instructions.md)

---

## 📖 How to Use These Instructions

### Quick Navigation by Task

**Setting up environment?** → [Development Setup](instructions/02-development-setup.instructions.md)  
**Adding a custom feature?** → [Customization Patterns](instructions/03-customization.instructions.md)  
**Creating a custom field?** → [Field System](instructions/05-field-system.instructions.md)  
**Working with records?** → [CRUD Operations](instructions/07-crud-operations.instructions.md)  
**Adding backend logic?** → [PHP Backend](instructions/08-backend-php.instructions.md) + [Routing](instructions/09-routing-controllers.instructions.md)  
**Implementing form logic?** → [MintLogic System](instructions/12-mintlogic.instructions.md)  
**Migrating legacy module?** → [Legacy Migration](instructions/17-legacy-migration.instructions.md)  
**Debugging issues?** → [Troubleshooting](instructions/15-troubleshooting.instructions.md)

### Quick Navigation by Technology

**Vue.js / Frontend**: [04](instructions/04-frontend-vue.instructions.md), [05](instructions/05-field-system.instructions.md), [06](instructions/06-state-management.instructions.md), [07](instructions/07-crud-operations.instructions.md)  
**PHP / Backend**: [08](instructions/08-backend-php.instructions.md), [09](instructions/09-routing-controllers.instructions.md), [10](instructions/10-doctrine-orm.instructions.md), [11](instructions/11-legacy-integration.instructions.md)  
**Business Logic**: [12](instructions/12-mintlogic.instructions.md), [13](instructions/13-validation-security.instructions.md)  
**Development**: [14](instructions/14-testing.instructions.md), [15](instructions/15-troubleshooting.instructions.md), [16](instructions/16-performance.instructions.md)  
**Migration**: [17](instructions/17-legacy-migration.instructions.md)

### For AI Agents

1. **First time?** Read in order: [01](instructions/01-project-structure.instructions.md) → [02](instructions/02-development-setup.instructions.md) → [03](instructions/03-customization.instructions.md)
2. **Specific task?** Use quick navigation above
3. **Need examples?** Each file contains practical code examples
4. **Stuck?** Check [Troubleshooting Guide](instructions/15-troubleshooting.instructions.md)

### File Naming Convention

Instructions use sequential numbering (00-17) organized by topic:
- **00**: Coding standards (language, naming conventions)
- **01-03**: Core concepts (architecture, setup, customization)
- **04-07**: Frontend (Vue.js, fields, state, CRUD)
- **08-11**: Backend (PHP, routing, Doctrine, legacy)
- **12-13**: Business logic (MintLogic, validation, security)
- **14-16**: Development practices (testing, troubleshooting, performance)
- **17**: Migration (legacy to new views)

---

## 🎯 Prime Directives

### 1. Never Edit Core Files - Use custom/ Directory

**❌ NEVER modify**:
- `vue/src/components/` (except core development)
- `vue/src/views/` (except core development)
- `api/app/` (except core development)
- `api/lib/` (except core development)

**✅ ALWAYS customize in**:
- `vue/src/custom/` - Mirror core structure
- `api/custom/app/` - Mirror core structure

**Example**:
```
Extend: api/app/Controllers/EmployeesController.php
Create: api/custom/app/Controllers/EmployeesController.php
Pattern: class EmployeesController extends \MintHCM\App\Controllers\EmployeesController
```

### 2. Understand the Architecture

**Two Applications**:
- **Frontend**: Vue 3 SPA (`vue/`) - TypeScript, Vite, Vuetify, Pinia
- **Backend**: PHP 8.2 REST API (`api/`) - Slim, Doctrine, Legacy Bridge

**Data Flow**:
```
User opens record
  ↓
Backend /api/init (modules, fields, ACL, translations)
  ↓
Vue stores (backend, modules, auth, languages)
  ↓
useBean composable (fetches record + MintLogic rules)
  ↓
Field.vue components (render based on type + logic)
  ↓
User edits field
  ↓
bean.updateFields() → Backend
  ↓
MintLogic re-evaluates → Frontend applies new logic
```

**Key Insight**: Backend drives ALL form behavior. Frontend is a rendering engine.

### 3. Follow CustomLoader Pattern

**CustomLoader** checks for custom namespace first:
1. Look for `MintHCM\Custom\App\Controllers\EmployeesController`
2. If exists, use it
3. If not, use `MintHCM\App\Controllers\EmployeesController`

**This allows safe extensions without modifying core.**

---

## 🌍 Coding Standards

### Language & Naming Conventions

**All code must be in English**:
- Variables, functions, classes, comments, documentation
- Commit messages and technical docs

**Respond to users in their language**:
- Polish question → Polish response
- English question → English response
- Code examples always remain in English

**PHP Naming**:
- Variables: `snake_case` → `$user_name`, `$employee_list`
- Classes: `PascalCase` → `UserManager`, `EmployeesController`
- Methods: `camelCase` → `getUserData()`, `validateInput()`
- Constants: `SCREAMING_SNAKE_CASE` → `MAX_LOGIN_ATTEMPTS`

**TypeScript/Vue Naming**:
- Variables/Functions: `camelCase` → `userName`, `getUserData()`
- Components: `PascalCase` → `EmployeeList.vue`
- Composables: `camelCase` with prefix → `useBean()`, `useLink()`

**📖 Full Details**: See [Coding Standards & Conventions](instructions/00-coding-standards.instructions.md) for complete guide with examples.

---

## 🚀 Quick Start for AI Agents

### First Time in Codebase?

1. **Read architecture**: [Project Structure](instructions/01-project-structure.instructions.md)
2. **Setup environment**: [Development Setup](instructions/02-development-setup.instructions.md)
3. **Understand customization**: [Customization Patterns](instructions/03-customization.instructions.md)

### Common Tasks

**Adding a new feature**:
- Frontend: Create in `vue/src/custom/` ([Vue Guide](instructions/04-frontend-vue.instructions.md))
- Backend: Create in `api/custom/app/` ([PHP Guide](instructions/08-backend-php.instructions.md))
- Form logic: Define in logicdefs ([MintLogic](instructions/12-mintlogic.instructions.md))

**Creating custom field type**:
1. Create components in `vue/src/custom/components/Fields/{type}/`
2. Define in backend vardefs with matching type
3. See [Field System](instructions/05-field-system.instructions.md)

**Adding custom route**:
1. Backend: `api/custom/app/Routes/{Module}.php` (array format)
2. Frontend: `vue/src/custom/router/routes.ts`
3. See [Routing](instructions/09-routing-controllers.instructions.md)

**Migrating legacy module to new view**:
1. Create `legacy/modules/{Module}/metadata/recordviewdefs.php`
2. Create `api/lib/MintLogic/Modules/{Module}/logicdefs.php`
3. Remove module from `api/constants/legacy_views.php`
4. See [Legacy Migration](instructions/17-legacy-migration.instructions.md)

**Modifying fields**:
1. Edit vardefs in `modules/{Module}/vardefs.php`
2. Run "Quick Repair and Rebuild" in Admin
3. Verify entities regenerated in `api/app/Entities/`
4. See [Doctrine ORM](instructions/10-doctrine-orm.instructions.md)

---

## 💡 Critical Patterns

### useBean Composable (Frontend CRUD)

```typescript
import { useBean } from '@/composables/useBean'

// Load existing record
const bean = useBean('Employees', '123')
await bean.init()

// Update fields (NEVER modify attributes directly)
bean.updateFields({
  first_name: 'John',
  last_name: 'Doe',
})

// Check validation before saving
if (bean.isValid.value) {
  await bean.save()
}

// Access logic-driven field states
const isFieldHidden = bean.logic.hiddenFields.value.has('salary')
const isFieldRequired = bean.logic.requiredFields.value.has('email')
```

**Always**:
- Call `bean.init()` before using
- Use `bean.updateFields()` for modifications
- Check `bean.isValid` before saving
- Respect `bean.logic` for dynamic behavior

### MintLogic (Backend Form Logic)

```php
// modules/Employees/logicdefs.php
$logicdefs['Employees'] = [
    [
        'hook' => 'CHANGE',  // INIT, CHANGE, or ALL
        'trigger' => 'employment_status',
        'condition' => "equals(field('employment_status'), 'Terminated')",
        'actions' => [
            [
                'type' => 'REQUIRED',  // VISIBLE, REQUIRED, READONLY, UPDATE, VALIDATION, OPTIONS
                'target' => 'termination_date',
                'value' => true,
            ],
            [
                'type' => 'VISIBLE',
                'target' => 'termination_reason',
                'value' => true,
            ],
        ],
    ],
];
```

**Formulas**: `equals()`, `notEmpty()`, `greaterThan()`, `and()`, `or()`, `not()`, `concat()`, `matches()`

**Field references**: `field('name')`, `$new.name`, `$old.name`

### Field System (Dynamic Rendering)

```vue
<Field 
  :defs="{ type: 'string', name: 'email', required: true }"
  :view="'edit'"
  v-model="bean.attributes.value.email"
  :label="'Email'"
  :required="bean.logic.requiredFields.value.has('email')"
  :disabled="bean.logic.readonlyFields.value.has('email')"
  :isDirty="bean.dirtyFields.value.has('email')"
  :errorMessage="bean.errorMessages.value.email"
/>
```

**Field.vue** loads `{type}.{view}.vue` based on type and view mode.

### Entity Generation (Critical Workflow)

**After modifying vardefs**:
1. Run "Quick Repair and Rebuild" in Admin → Repair
2. Entities auto-regenerate in `api/app/Entities/`
3. **NEVER edit protected regions** (`BEGIN/END PROTECTED REGION`)
4. Add custom methods outside protected blocks

---

## ✅ DO / ❌ DON'T

### Frontend (Vue)

**✅ DO**:
- Call `bean.init()` before using
- Use `bean.updateFields()` for modifications
- Check `bean.isValid` before saving
- Import with `@` alias
- Put customizations in `vue/src/custom/`
- Use TypeScript interfaces
- Emit `update:modelValue` in custom fields
- Lazy load heavy components
- Debounce search inputs

**❌ DON'T**:
- Modify `bean.syncAttributes` directly (read-only)
- Update `bean.attributes.value` directly
- Edit core components
- Hardcode labels (use `languages.label()`)
- Skip validation checks

### Backend (PHP)

**✅ DO**:
- Use Doctrine for new features
- Put customizations in `api/custom/app/`
- Extend core classes, don't modify
- Use repositories for business logic
- Return PSR-7 responses
- Validate input in controllers
- Run "Quick Repair" after vardef changes
- Write tests in `api/tests/`
- Use LegacyConnector for legacy code

**❌ DON'T**:
- Edit protected regions in entities
- Modify core files
- Skip authentication (`auth => false` only for public endpoints)
- Flush EntityManager in loops
- Hardcode credentials (use configs)
- Use legacy beans for new features

---

## 🔒 Security & Authentication

**Frontend**:
- JWT token in localStorage
- Axios interceptor attaches to all requests
- Redirect to `/login` on 401

**Backend**:
- Routes require `auth => true` by default
- Set `auth => false` only for public endpoints (login, health)
- ACL checked per-module via RouteAccessMiddleware

**CLIENT_SECRET** generation:
```bash
./MintCLI oauth2:repairFrontend
# Copy secret to vue/.env
```

---

## 🧪 Testing

**Frontend**:
```bash
cd vue
npm run test:unit      # Unit tests
npm run test:e2e       # E2E tests
```

**Backend**:
```bash
cd api
./vendor/bin/phpunit   # All tests
php runTests.php       # Alternative
```

---

## 🐛 Quick Troubleshooting

**Frontend**:
- PROXY_URL errors → Check `vue/.env`, restart dev server
- Type errors → Install Volar, disable Vetur, restart TS server
- Hot reload broken → Hard refresh (Ctrl+Shift+R), restart dev server
- Build fails → Fix TS errors, fix ESLint, clear cache

**Backend**:
- Entity not found → Run "Quick Repair", clear Doctrine cache
- Route not found → Check file path/namespace, restart server
- DI error → Check namespaces, run `composer dump-autoload`
- Legacy fails → Wrap in LegacyConnector closure

See [Troubleshooting Guide](instructions/15-troubleshooting.instructions.md) for detailed solutions.

---

## 📖 Constants vs. Configs

**Constants** (`api/constants/`):
- ✅ Version controlled
- ✅ Extensible (merge from `custom/constants/`)
- ❌ Cannot change at runtime

**Configs** (`api/configs/`):
- ✅ Runtime modifiable
- ✅ Environment-specific
- ❌ NOT version controlled
- ❌ NOT extensible

---

## 🎯 Key Principles for AI Agents

1. **Always** follow these instructions
2. **Never** modify core files without understanding context
3. **Always** use `custom/` directory for extensions
4. **Never** bypass `useBean`/`updateFields`/`save` workflow
5. **Always** check validation before saving
6. **Never** edit protected regions in entities
7. **Always** run "Quick Repair" after vardef changes
8. **Never** hardcode values (use stores, translations, configs)
9. **Always** write tests for new features
10. **Never** skip security checks

---

## 📚 Documentation Locations

- **Frontend docs**: `vue/documentation/*.md` (8 guides including recordviewdefs) - For developers/users
- **Backend docs**: `api/documentation/*.md` (14 guides including MintLogic migration) - For developers/users
- **AI instructions**: `.github/instructions/*.md` (18 instruction files: 00-17) - For AI agents only

**IMPORTANT**: Documentation files (`api/documentation/`, `vue/documentation/`) are for REAL USERS (developers), not AI agents. Never reference AI instructions (`.github/instructions/`, `.github/prompts/`) from documentation files. Documentation should only cross-reference other documentation files.

---

**Remember**: Backend defines everything (modules, fields, ACL, logic). Frontend consumes and renders. Never bypass established patterns. Always use `custom/` directories for extensions.

For detailed information on any topic, refer to the specific instruction files in the [instructions/](instructions/) directory.
