---
applyTo:
  - "vue/**"
---

# Vue Frontend Guide

## Overview

The MintHCM frontend is a Vue 3 Single Page Application (SPA) built with TypeScript, Vite, Vuetify, and Pinia.

## Key Technologies

- **Vue 3**: Progressive JavaScript framework with Composition API
- **TypeScript**: Type-safe development
- **Vite**: Fast build tool and dev server
- **Vuetify**: Material Design component library
- **Pinia**: State management
- **Vue Router**: Client-side routing
- **Axios**: HTTP client for API communication

## Project Structure

```
vue/src/
├── main.ts              # Application entry point
├── App.vue              # Root component
├── router/              # Vue Router configuration
│   └── index.ts
├── store/               # Pinia stores
│   ├── backend.ts       # Backend initialization
│   ├── auth.ts          # Authentication
│   ├── modules.ts       # Module definitions
│   └── languages.ts     # Translations
├── components/          # Reusable components
│   ├── Fields/          # Dynamic field system
│   └── ...
├── views/               # Page components
│   ├── ListView/
│   ├── DetailView/
│   └── EditView/
├── composables/         # Composition functions
│   ├── useBean.ts       # CRUD operations
│   └── useLink.ts       # Relationships
├── business/            # Business logic
│   ├── BeanActions/
│   ├── MassActions/
│   └── SubpanelActions/
├── api/                 # HTTP client
│   └── api.ts
├── layouts/             # Layout templates
└── custom/              # YOUR CUSTOMIZATIONS
```

## Development Workflow

### Starting Dev Server

```bash
cd vue
npm install
npm run dev
```

Access at `http://localhost:5173`

### Building for Production

```bash
# Standard build
npm run build

# Build and copy to repo root
npm run build:repo
```

## Key Concepts

### Composition API

All components use Vue 3 Composition API:

```vue
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const count = ref(0)
const doubleCount = computed(() => count.value * 2)

onMounted(() => {
  console.log('Component mounted')
})
</script>

<template>
  <div>
    <p>Count: {{ count }}</p>
    <p>Double: {{ doubleCount }}</p>
    <button @click="count++">Increment</button>
  </div>
</template>
```

### TypeScript Integration

Use TypeScript for type safety:

```typescript
interface Employee {
  id: string
  first_name: string
  last_name: string
  email: string
}

const employees = ref<Employee[]>([])

function addEmployee(employee: Employee) {
  employees.value.push(employee)
}
```

### Vuetify Components

Use Material Design components:

```vue
<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="6">
        <v-text-field
          v-model="name"
          label="Name"
          variant="outlined"
        />
      </v-col>
    </v-row>
    
    <v-btn color="primary" @click="save">
      Save
    </v-btn>
  </v-container>
</template>
```

## Routing

Routes use hash mode for PHP compatibility:

```typescript
// router/index.ts
import { createRouter, createWebHashHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    component: () => import('@/views/HomePage.vue')
  },
  {
    path: '/modules/:module/:view/:id?',
    component: () => import('@/views/ModuleView.vue'),
    meta: { auth: true }
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
```

**Route examples**:
- `/#/` - Home page
- `/#/modules/Employees/list` - Employee list
- `/#/modules/Employees/detail/123` - Employee detail
- `/#/modules/Employees/edit/123` - Edit employee

## API Communication

Use `mintApi` for backend calls:

```typescript
import { mintApi } from '@/api/api'

// GET request
const response = await mintApi.get('/Employees')
const employees = response.data

// POST request
await mintApi.post('/Employees', {
  first_name: 'John',
  last_name: 'Doe'
})

// PUT request
await mintApi.put('/Employees/123', {
  status: 'Active'
})

// DELETE request
await mintApi.delete('/Employees/123')
```

## Common Patterns

### Fetching Data

```vue
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { mintApi } from '@/api/api'

const loading = ref(false)
const employees = ref([])

onMounted(async () => {
  loading.value = true
  try {
    const response = await mintApi.get('/Employees')
    employees.value = response.data
  } catch (error) {
    console.error('Failed to fetch employees:', error)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <v-progress-circular v-if="loading" indeterminate />
    <v-list v-else>
      <v-list-item
        v-for="emp in employees"
        :key="emp.id"
        :title="emp.name"
      />
    </v-list>
  </div>
</template>
```

### Form Handling

```vue
<script setup lang="ts">
import { reactive } from 'vue'
import { mintApi } from '@/api/api'

const form = reactive({
  first_name: '',
  last_name: '',
  email: ''
})

const errors = reactive({})

async function submit() {
  try {
    await mintApi.post('/Employees', form)
    // Success
  } catch (error) {
    if (error.response?.data?.errors) {
      Object.assign(errors, error.response.data.errors)
    }
  }
}
</script>

<template>
  <v-form @submit.prevent="submit">
    <v-text-field
      v-model="form.first_name"
      label="First Name"
      :error-messages="errors.first_name"
    />
    <v-text-field
      v-model="form.last_name"
      label="Last Name"
      :error-messages="errors.last_name"
    />
    <v-text-field
      v-model="form.email"
      label="Email"
      :error-messages="errors.email"
    />
    <v-btn type="submit">Submit</v-btn>
  </v-form>
</template>
```

## Best Practices

### ✅ DO

- Use Composition API with `<script setup>`
- Import with `@` alias: `import { useBean } from '@/composables/useBean'`
- Define TypeScript interfaces for data structures
- Use Vuetify components for UI consistency
- Handle loading and error states
- Use async/await for API calls
- Emit events for parent communication
- Use computed for derived state

### ❌ DON'T

- Mix Options API and Composition API
- Mutate props directly
- Forget error handling in API calls
- Use `any` type excessively
- Hardcode strings (use translations)
- Modify reactive objects incorrectly
- Skip input validation

---

**Related Guides**:
- [Field System](11-field-system.md) - Dynamic field rendering
- [State Management](12-state-management.md) - Pinia stores
- [CRUD Operations](13-crud-operations.md) - useBean composable
- [Customization](03-customization.md) - Extending frontend

**Full Documentation**: `vue/documentation/*.md` (7 comprehensive guides)
