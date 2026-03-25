# Vue Frontend -- Core Developer Reference

## Tech Stack

Vue 3 + TypeScript + Vite + Vuetify 3 + Pinia + Vue Router + Axios.
`@` alias maps to `vue/src/`.

## Directory Structure

```
vue/src/
├── api/              # Typed API client wrappers (Axios)
├── components/
│   └── Fields/       # Field type components (one dir per type)
│       ├── Field.vue           # Main router component
│       ├── Field.config.ts     # Type mappings and config
│       ├── Field.model.ts      # TypeScript interfaces
│       ├── useField.ts         # Composable for field logic
│       └── {type}/
│           ├── {type}.detail.vue
│           ├── {type}.edit.vue
│           └── {type}.list.vue
├── views/            # ListView, DetailView, EditView, RecordView, LegacyView, DashboardView
├── store/            # Pinia stores (backend, auth, modules, languages, preferences, url)
├── composables/      # useBean, useLink, useACL
├── business/         # BeanActions, Logic
├── router/           # Vue Router config
├── custom/           # Customizations (safe from updates)
│   ├── components/Fields/   # Custom field types
│   ├── router/              # Custom routes
│   ├── store/               # Custom stores
│   └── views/               # Custom views
└── utils/            # Helpers
```

## Field System

Fields auto-resolve by type + view. `<Field>` component loads `{type}.{view}.vue` dynamically.

### Creating a New Field Type

1. Create component files:
```
src/components/Fields/rating/
├── rating.detail.vue
├── rating.edit.vue
└── rating.list.vue
```

2. Edit view (with v-model):
```vue
<template>
    <v-rating
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
        color="amber"
        hover
        :length="5"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'
const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])
</script>
```

3. Detail view (read-only):
```vue
<template>
    <v-rating :model-value="modelValue" readonly color="amber" density="compact" />
</template>

<script setup lang="ts">
const props = defineProps<Pick<FieldProps, 'modelValue' | 'defs'>>()
</script>
```

4. Register in `Field.config.ts` typeMap if backend type differs:
```typescript
typeMap: {
    'stars': 'rating',  // backend 'stars' → component 'rating'
}
```

### FieldProps Interface
```typescript
interface FieldProps {
    view: 'edit' | 'detail' | 'list'
    defs: FieldVardef        // { name, type, label, required, options, ... }
    label: string
    modelValue?: any
    data?: any               // Full record data
    options?: any
    state?: 'normal' | 'error' | 'required'
    required?: boolean
    error?: boolean
    errorMessage?: string
    disabled?: boolean
    isDirty?: boolean
}
```

## Views

Generic views work for any module -- no per-module view files needed:
- `ListView` -- table with columns from `listviewdefs.php`/`eslistviewdefs.php`
- `DetailView` / `EditView` -- form from `recordviewdefs.php`
- `RecordView` -- combined detail+edit with panels
- `LegacyView` -- iframe embedding legacy PHP page

## Pinia Stores

Composition API style:

```typescript
// store/myStore.ts
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useMyStore = defineStore('my', () => {
    const items = ref<Item[]>([])
    const count = computed(() => items.value.length)

    async function fetchItems() {
        const response = await api.get('/items')
        items.value = response.data
    }

    return { items, count, fetchItems }
})
```

Core stores: `backend` (initData, legacy_views), `auth` (user, token), `modules` (metadata), `languages` (labels, translations), `preferences` (user prefs).

## Composables

### useBean -- Record CRUD
```typescript
const bean = useBean('Employees', recordId)
await bean.init()                    // Fetch or initialize
bean.attributes.value.first_name    // Current values
bean.updateFields({ status: 'Active' })  // Update + mark dirty
await bean.save()                   // Save to server
bean.restore()                      // Revert changes
bean.isChanged.value                // Has unsaved changes
bean.isValid.value                  // Passes validation
bean.logic.hiddenFields.value       // MintLogic hidden fields
bean.logic.requiredFields.value     // MintLogic required fields
```

### useLink -- Relationships
```typescript
const link = bean.loadRelationship('contacts')
await link.fetchRelatedRecords()
link.beans.value                    // Map<id, record>
link.add('contact-id', { role: 'Manager' })
link.remove('contact-id')
// Changes saved automatically with bean.save()
```

### useACL -- Permissions
```typescript
const acl = useACL()
acl.hasAccess('Employees', 'edit')
```

## BeanActions

Actions configured in `recordviewdefs.php`, rendered by `MintPanelRecordDetails`:

```php
// legacy/modules/Meetings/metadata/recordviewdefs.php
'actions' => [
    'Edit',
    'Delete',
    'Audit',
    ['name' => 'Duplicate', 'skipFields' => ['date_start', 'date_end']],
],
```

Frontend loads action class from `business/BeanActions/`, checks `isAvailable()`, renders menu item. On click calls `execute()`.

## Build

```bash
cd vue
npm install
npx vite --host 0.0.0.0     # Dev server (requires PROXY_URL in vue/.env)
npm run build                # Production build
npm run build:repo           # Build + copy dist to ../assets
```

After Vue changes to core: `npm run build:repo` to update deployed assets.

## Common Mistakes

1. **Hardcoding labels** -- use `languagesStore.label('LBL_KEY')`, never raw strings in templates.
2. **Modifying core field components** -- create custom type in `src/custom/components/Fields/` or add typeMap entry.
3. **Direct `attributes.value.field = x`** -- use `bean.updateFields({field: x})` to trigger dirty tracking and MintLogic re-evaluation.
4. **Forgetting `npm run build:repo`** -- Vue changes don't appear in legacy-served pages until assets are rebuilt and copied.
5. **Not handling all 3 views** -- every field type needs `detail.vue`, `edit.vue`, and `list.vue` or the field won't render in that context.
