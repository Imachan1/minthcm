---
applyTo:
  - "api/**"
  - "vue/**"
---

# Performance Optimization

Performance best practices for MintHCM.

## Frontend Optimization

### Code Splitting

```typescript
// Lazy load routes
const routes = [
  {
    path: '/employees',
    component: () => import('@/views/Employees/ListView.vue')
  }
]

// Lazy load components
const HeavyComponent = defineAsyncComponent(() =>
  import('@/components/HeavyComponent.vue')
)
```

### Component Performance

```vue
<script setup lang="ts">
import { computed, watchEffect } from 'vue'

// Use computed for derived state
const fullName = computed(() => `${firstName.value} ${lastName.value}`)

// Debounce expensive operations
import { debounce } from 'lodash-es'
const debouncedSearch = debounce((query) => {
  // Expensive search
}, 300)
</script>

<template>
  <!-- Use v-show for frequent toggles -->
  <div v-show="isVisible">Content</div>
  
  <!-- Use v-if for rare toggles -->
  <div v-if="shouldRender">Expensive</div>
  
  <!-- Use key for list performance -->
  <div v-for="item in items" :key="item.id">{{ item.name }}</div>
</template>
```

### API Optimization

```typescript
// Batch requests
const [employees, departments] = await Promise.all([
  api.get('/employees'),
  api.get('/departments')
])

// Cache responses
const cache = new Map()
function getCachedData(key: string) {
  if (cache.has(key)) return cache.get(key)
  const data = fetchData(key)
  cache.set(key, data)
  return data
}

// Use pagination
const response = await api.get('/employees', {
  params: { offset: 0, limit: 20 }
})
```

### Bundle Optimization

```typescript
// vite.config.ts
export default defineConfig({
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          'vue-vendor': ['vue', 'vue-router', 'pinia'],
          'ui-vendor': ['vuetify']
        }
      }
    }
  }
})
```

## Backend Optimization

### Query Optimization

```php
// Bad: N+1 query problem
$employees = $repository->findAll();
foreach ($employees as $employee) {
    echo $employee->getDepartment()->getName(); // Extra query per employee
}

// Good: Eager loading
$employees = $repository->createQueryBuilder('e')
    ->leftJoin('e.department', 'd')
    ->addSelect('d')
    ->getQuery()
    ->getResult();

foreach ($employees as $employee) {
    echo $employee->getDepartment()->getName(); // No extra query
}
```

### Repository Optimization

```php
// Use indexes
$dictionary['Employee']['fields']['email']['index'] = true;

// Fetch only needed fields
$qb = $repository->createQueryBuilder('e')
    ->select('e.id, e.firstName, e.lastName')
    ->where('e.status = :status')
    ->setParameter('status', 'Active');

// Use query result cache
$query = $qb->getQuery();
$query->useResultCache(true, 3600); // Cache for 1 hour
$results = $query->getResult();
```

### Batch Operations

```php
// Bad: Flush in loop
foreach ($employees as $employee) {
    $employee->setStatus('Active');
    $entityManager->flush(); // Slow
}

// Good: Batch flush
foreach ($employees as $employee) {
    $employee->setStatus('Active');
}
$entityManager->flush(); // Once
```

### Response Caching

```php
// Cache expensive operations
$cache = $container->get('cache');
$key = 'employees_list_' . md5(json_encode($filters));

if ($cache->has($key)) {
    $data = $cache->get($key);
} else {
    $data = $repository->getExpensiveData($filters);
    $cache->set($key, $data, 3600); // 1 hour
}
```

## Database Optimization

### Indexes

```php
// Add indexes in vardefs
$dictionary['Employee']['fields']['email']['index'] = true;
$dictionary['Employee']['indices'] = [
    'idx_status_department' => [
        'type' => 'index',
        'fields' => ['status', 'department_id']
    ]
];
```

### Query Analysis

```sql
-- Check query performance
EXPLAIN SELECT * FROM employees WHERE status = 'Active';

-- Add missing indexes
CREATE INDEX idx_status ON employees(status);
```

## Profiling

### Frontend Profiling

```typescript
// Performance API
const start = performance.now()
// ... expensive operation
const end = performance.now()
console.log(`Operation took ${end - start}ms`)

// Vue DevTools Performance tab
// Chrome DevTools Lighthouse
```

### Backend Profiling

```php
// Xdebug profiling
// php.ini:
// xdebug.mode=profile
// xdebug.output_dir=/tmp/xdebug

// Manual profiling
$start = microtime(true);
// ... operation
$end = microtime(true);
error_log("Operation took " . ($end - $start) . " seconds");
```

## Best Practices

**Frontend**:
- Lazy load heavy components
- Use virtual scrolling for long lists
- Debounce user input
- Optimize images
- Use CDN for assets
- Enable gzip compression

**Backend**:
- Use eager loading
- Add database indexes
- Cache expensive queries
- Use pagination
- Optimize SQL queries
- Profile slow endpoints

**Database**:
- Add indexes on foreign keys
- Avoid SELECT *
- Use LIMIT for large tables
- Optimize JOIN queries
- Regular ANALYZE TABLE

**Full Documentation**: `vue/documentation/performance.md`, `api/documentation/performance.md`

---

**Related**: [Development Setup](02-development-setup.md), [Doctrine ORM](10-doctrine-orm.md), [Troubleshooting](15-troubleshooting.md)
