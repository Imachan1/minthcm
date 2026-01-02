---
applyTo:
	- "vue/src/store/**"
	- "vue/src/custom/store/**"
	- "vue/src/main.ts"
	- "vue/src/router/**"
---

# State Management (Pinia)

**Version**: 2.0  
**Last Updated**: 2026-01-02

MintHCM uses Pinia for global state management.

## Key Stores

### backend Store
Bootstraps application via `/api/init`:
```typescript
import { useBackendStore } from '@/store/backend'

const backend = useBackendStore()
await backend.init() // Call once on app start
```

Returns: modules, fields, ACL, translations, user info.

### auth Store
Manages authentication:
```typescript
import { useAuthStore } from '@/store/auth'

const auth = useAuthStore()
await auth.login({ username, password })
auth.logout()
console.log(auth.user) // Current user info
```

### modules Store
Provides module metadata:
```typescript
import { useModulesStore } from '@/store/modules'

const modules = useModulesStore()
const fields = modules.getModuleFields('Employees')
const acl = modules.getModuleACL('Employees')
```

### languages Store
Handles translations:
```typescript
import { useLanguagesStore } from '@/store/languages'

const languages = useLanguagesStore()
const label = languages.label('LBL_FIRST_NAME', 'Employees')
const appString = languages.appString('LBL_SAVE')
```

## Initialization Order

```typescript
// main.ts
import { createPinia } from 'pinia'
import { useBackendStore } from '@/store/backend'

const app = createApp(App)
app.use(createPinia())

const backend = useBackendStore()
await backend.init() // Initializes all stores

app.mount('#app')
```

**Full Documentation**: `vue/documentation/*.md`

---

**Related**: [Vue Frontend](04-frontend-vue.md), [CRUD Operations](07-crud-operations.md)
