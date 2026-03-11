# Vue Frontend Extensions

## Custom Field Types

Create `vue/src/custom/components/Fields/{type}/` with three view files:

```
vue/src/custom/components/Fields/rating/
  rating.detail.vue
  rating.edit.vue
  rating.list.vue
```

### Edit view example

```vue
<!-- vue/src/custom/components/Fields/rating/rating.edit.vue -->
<template>
    <div class="rating-edit">
        <v-rating
            :model-value="modelValue"
            @update:model-value="$emit('update:modelValue', $event)"
            color="amber"
            hover
            :length="5"
            :size="32"
        />
        <div v-if="state === 'error'" class="error-msg">
            {{ errorMessage }}
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    modelValue?: number
    label?: string
    defs?: any
    state?: 'normal' | 'required' | 'error'
    errorMessage?: string
}

const props = defineProps<Props>()
defineEmits(['update:modelValue'])
</script>
```

### Detail view example

```vue
<!-- vue/src/custom/components/Fields/rating/rating.detail.vue -->
<template>
    <v-rating
        :model-value="modelValue"
        readonly
        color="amber"
        density="compact"
    />
</template>

<script setup lang="ts">
defineProps<{ modelValue?: number; defs?: any }>()
</script>
```

### List view example

```vue
<!-- vue/src/custom/components/Fields/rating/rating.list.vue -->
<template>
    <v-rating :model-value="modelValue" readonly color="amber" density="compact" size="small" />
</template>

<script setup lang="ts">
defineProps<{ modelValue?: number }>()
</script>
```

Field naming convention: `{type}.{view}.vue`. The `Field.vue` component dynamically imports based on `defs.type` and view mode.

## Custom Vue Routes

Create `vue/src/custom/router/routes.ts`:

```typescript
import { RouteRecordRaw } from 'vue-router'

const customRoutes: RouteRecordRaw[] = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import('@/custom/views/Dashboard/Dashboard.vue'),
        meta: { auth: true },
    },
    {
        path: '/reports/:id?',
        name: 'reports',
        component: () => import('@/custom/views/Reports/Reports.vue'),
        meta: { auth: true },
    },
]

export default customRoutes
```

## Custom Pinia Stores

Create `vue/src/custom/store/{storeName}.ts`:

```typescript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { mintApi } from '@/api/api'

export const useReportsStore = defineStore('reports', () => {
    const reports = ref<any[]>([])
    const loading = ref(false)

    const pendingReports = computed(() =>
        reports.value.filter(r => r.status === 'pending')
    )

    async function fetchReports() {
        loading.value = true
        try {
            const response = await mintApi.get('/reports')
            reports.value = response.data.data
        } finally {
            loading.value = false
        }
    }

    async function generateReport(type: string) {
        const response = await mintApi.post('/reports/generate', { type })
        reports.value.push(response.data.data)
        return response.data.data
    }

    return { reports, loading, pendingReports, fetchReports, generateReport }
})
```

Usage in component:

```vue
<script setup lang="ts">
import { useReportsStore } from '@/custom/store/reports'
import { onMounted } from 'vue'

const store = useReportsStore()
onMounted(() => store.fetchReports())
</script>
```

## Custom Views/Pages

Create `vue/src/custom/views/{ViewName}/{ViewName}.vue`:

```vue
<!-- vue/src/custom/views/Dashboard/Dashboard.vue -->
<template>
    <div class="con-dashboard">
        <h1>{{ title }}</h1>
        <v-card class="mt-4">
            <v-card-text>
                <v-data-table :items="store.reports" :headers="headers" :loading="store.loading" />
            </v-card-text>
        </v-card>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useReportsStore } from '@/custom/store/reports'

const store = useReportsStore()
const title = ref('Dashboard')
const headers = ref([
    { title: 'Name', key: 'name' },
    { title: 'Status', key: 'status' },
    { title: 'Date', key: 'date_entered' },
])

onMounted(() => store.fetchReports())
</script>
```

## Custom Drawers

Create `vue/src/custom/drawers/{name}.drawer.ts`:

```typescript
export default {
    name: 'details',
    component: () => import('@/custom/components/DetailsDrawer.vue'),
    props: { module: String, recordId: String },
}
```

## Custom BeanActions

Create `vue/src/custom/business/BeanActions/Actions/{ActionName}.ts`:

```typescript
import { BeanAction } from '@/business/BeanActions/BeanAction'
import { mintApi } from '@/api/api'

export class GenerateReport extends BeanAction {
    name = 'generate_report'
    label = 'LBL_GENERATE_REPORT'
    icon = 'mdi-file-chart'

    async execute(module: string, id: string) {
        try {
            await mintApi.post(`${module}/${id}/generate-report`)
            this.showNotification({ type: 'success', message: 'Report generated' })
            await this.refresh()
        } catch (error) {
            this.showNotification({ type: 'error', message: 'Generation failed' })
        }
    }

    isAvailable(module: string, record: any): boolean {
        return ['con_Projects'].includes(module)
    }
}
```

## Custom API Wrappers

Create `vue/src/custom/api/{feature}.api.ts`:

```typescript
import { mintApi } from '@/api/api'

export interface Report {
    id: string
    name: string
    type: string
    status: string
}

export const reportsApi = {
    async list(): Promise<Report[]> {
        const res = await mintApi.get<{ data: Report[] }>('/reports')
        return res.data.data
    },
    async generate(type: string): Promise<Report> {
        const res = await mintApi.post<{ data: Report }>('/reports/generate', { type })
        return res.data.data
    },
}
```

## Custom Styles

Create `vue/src/custom/styles/custom.scss`:

```scss
// Override variables
$con-primary: #1a73e8;

// Custom utility classes
.con-card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

// Module-specific styles
.con-dashboard {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}
```

Import in main: `import '@/custom/styles/custom.scss'`

## Import Pattern

The `@` alias maps to `vue/src/`. Always use it:

```typescript
import { useStore } from '@/custom/store/store'
import Widget from '@/custom/components/Widget.vue'
import { api } from '@/custom/api/api'
```

## Build After Changes

After any Vue changes:

```bash
cd vue
npm run build:repo    # Builds and copies dist to ../assets/
```

For development with hot reload:

```bash
cd vue
npx vite --host 0.0.0.0    # Requires PROXY_URL in vue/.env
```

## Common Mistakes

1. **Forgetting `npm run build:repo`** after changes -- production won't see your updates.
2. **Using relative imports** instead of `@/` alias -- breaks when files move.
3. **Not emitting `update:modelValue`** in custom field edit components -- v-model won't work.
4. **Missing `.value` on refs** in script setup -- common Vue 3 reactivity pitfall.
5. **Not typing props with TypeScript** -- loses type safety and IDE support.
