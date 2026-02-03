---
applyTo:
  - "api/custom/**"
  - "vue/src/custom/**"
  - ".github/**"
---

# Customization Patterns

## Prime Directive: Never Edit Core

**The golden rule**: Never modify files in core directories. Always use `custom/` directories.

## CustomLoader Pattern

MintHCM uses `CustomLoader` to check for custom implementations before loading core classes.

### How It Works

**Resolution order**:
1. Check if `MintHCM\Custom\App\Controllers\EmployeesController` exists
2. If YES → Load custom version
3. If NO → Load `MintHCM\App\Controllers\EmployeesController`

### Benefits

- ✅ Core files protected from modifications
- ✅ Customizations survive system updates
- ✅ Clear separation between core and custom code
- ✅ Easy to identify what's been customized

## Directory Structure for Customizations

### Frontend Custom Directories

```
vue/src/custom/
├── components/          # Custom components
│   └── Fields/          # Custom field types
│       └── {type}/
│           ├── {type}.edit.vue
│           ├── {type}.detail.vue
│           └── {type}.list.vue
├── views/               # Custom page views
│   └── {ViewName}/
│       └── {ViewName}.vue
├── router/              # Custom routes
│   └── routes.ts
├── store/               # Custom Pinia stores
│   └── {storeName}.ts
├── composables/         # Custom composables
│   └── use{Name}.ts
├── business/            # Custom business logic
│   ├── BeanActions/
│   │   └── Actions/
│   ├── MassActions/
│   │   └── Actions/
│   └── SubpanelActions/
│       └── Actions/
└── utils/               # Custom utilities
    └── {helper}.ts
```

### Backend Custom Directories

```
api/custom/
├── app/
│   ├── Controllers/     # Custom controllers
│   │   └── {Module}Controller.php
│   ├── Repositories/    # Custom repositories
│   │   └── {Module}Repository.php
│   ├── Entities/        # Entity extensions (extend only, don't replace)
│   │   └── {Module}.php
│   ├── Routes/          # Custom routes
│   │   └── {Module}.php
│   ├── Middlewares/     # Custom middlewares
│   │   └── {Name}Middleware.php
│   └── Services/        # Custom services
│       └── {Name}Service.php
├── lib/
│   ├── MintLogic/       # Custom logic definitions
│   │   └── Modules/
│   │       └── {Module}/
│   │           └── logicdefs.php
│   └── Services/        # Custom business services
│       └── {Name}Service.php
└── constants/           # Custom constants (merged with core)
    ├── modules/
    ├── icons/
    └── {category}/
        └── {Name}.php
```

## Customization Examples

### Example 1: Extending a Controller

**Scenario**: Add custom method to EmployeesController

**api/custom/app/Controllers/EmployeesController.php**:
```php
<?php
namespace MintHCM\Custom\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use MintHCM\App\Controllers\EmployeesController as BaseController;

class EmployeesController extends BaseController
{
    /**
     * Override existing method
     */
    public function list(Request $request, Response $response): Response
    {
        // Add custom filtering
        $params = $request->getQueryParams();
        $customFilter = $params['custom_filter'] ?? null;
        
        if ($customFilter) {
            // Custom logic
        }
        
        // Call parent method
        return parent::list($request, $response);
    }
    
    /**
     * Add new method
     */
    public function exportCsv(Request $request, Response $response): Response
    {
        $employees = $this->repository->findAll();
        
        // Generate CSV
        $csv = $this->generateCsv($employees);
        
        $response->getBody()->write($csv);
        return $response
            ->withHeader('Content-Type', 'text/csv')
            ->withHeader('Content-Disposition', 'attachment; filename="employees.csv"');
    }
    
    private function generateCsv(array $employees): string
    {
        // CSV generation logic
        $output = fopen('php://temp', 'r+');
        fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email']);
        
        foreach ($employees as $emp) {
            fputcsv($output, [
                $emp->getId(),
                $emp->getFirstName(),
                $emp->getLastName(),
                $emp->getEmail(),
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}
```

**api/custom/app/Routes/Employees.php**:
```php
<?php
return [
    [
        'method' => 'GET',
        'path' => '/Employees/export/csv',
        'callable' => [\MintHCM\Custom\App\Controllers\EmployeesController::class, 'exportCsv'],
    ],
];
```

### Example 2: Custom Field Type

**Scenario**: Create a "rating" field type (1-5 stars)

**vue/src/custom/components/Fields/rating/rating.edit.vue**:
```vue
<template>
  <div class="rating-field">
    <v-rating
      :model-value="modelValue"
      @update:model-value="$emit('update:modelValue', $event)"
      :length="5"
      color="amber"
      hover
      :disabled="disabled"
    />
    <span v-if="errorMessage" class="error-text">{{ errorMessage }}</span>
  </div>
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'

defineProps<FieldProps>()
defineEmits(['update:modelValue'])
</script>

<style scoped>
.rating-field {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.error-text {
  color: rgb(var(--v-theme-error));
  font-size: 0.875rem;
}
</style>
```

**vue/src/custom/components/Fields/rating/rating.detail.vue**:
```vue
<template>
  <v-rating
    :model-value="modelValue"
    readonly
    :length="5"
    color="amber"
    size="small"
  />
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'
defineProps<FieldProps>()
</script>
```

**modules/Products/vardefs.php**:
```php
$dictionary['Product']['fields']['customer_rating'] = [
    'name' => 'customer_rating',
    'type' => 'rating',  // Matches component folder name
    'label' => 'LBL_CUSTOMER_RATING',
    'dbType' => 'int',
    'len' => 1,
];
```

### Example 3: Custom Vue View

**Scenario**: Create custom dashboard

**vue/src/custom/views/CustomDashboard/CustomDashboard.vue**:
```vue
<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1>{{ languages.label('LBL_CUSTOM_DASHBOARD') }}</h1>
      </v-col>
    </v-row>
    
    <v-row>
      <v-col cols="12" md="6">
        <StatisticsCard :data="statistics" />
      </v-col>
      <v-col cols="12" md="6">
        <RecentActivityCard :items="recentActivities" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'
import StatisticsCard from './components/StatisticsCard.vue'
import RecentActivityCard from './components/RecentActivityCard.vue'

const languages = useLanguagesStore()
const statistics = ref({})
const recentActivities = ref([])

onMounted(async () => {
  const [statsResponse, activitiesResponse] = await Promise.all([
    mintApi.get('/dashboard/statistics'),
    mintApi.get('/dashboard/recent-activities')
  ])
  
  statistics.value = statsResponse.data
  recentActivities.value = activitiesResponse.data
})
</script>
```

**vue/src/custom/router/routes.ts**:
```typescript
import { RouteRecordRaw } from 'vue-router'

export const customRoutes: RouteRecordRaw[] = [
  {
    path: '/custom-dashboard',
    name: 'custom-dashboard',
    component: () => import('@/custom/views/CustomDashboard/CustomDashboard.vue'),
    meta: {
      auth: true,
      title: 'Custom Dashboard'
    }
  }
]
```

### Example 4: Custom Pinia Store

**Scenario**: Store for managing notifications

**vue/src/custom/store/notifications.ts**:
```typescript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { mintApi } from '@/api/api'

export interface Notification {
  id: string
  title: string
  message: string
  type: 'info' | 'success' | 'warning' | 'error'
  read: boolean
  created_at: string
}

export const useNotificationsStore = defineStore('notifications', () => {
  const notifications = ref<Notification[]>([])
  const loading = ref(false)
  
  const unreadCount = computed(() => 
    notifications.value.filter(n => !n.read).length
  )
  
  const unreadNotifications = computed(() =>
    notifications.value.filter(n => !n.read)
  )
  
  async function fetchNotifications() {
    loading.value = true
    try {
      const response = await mintApi.get('/notifications')
      notifications.value = response.data
    } catch (error) {
      console.error('Failed to fetch notifications:', error)
    } finally {
      loading.value = false
    }
  }
  
  async function markAsRead(id: string) {
    try {
      await mintApi.put(`/notifications/${id}/read`)
      const notification = notifications.value.find(n => n.id === id)
      if (notification) {
        notification.read = true
      }
    } catch (error) {
      console.error('Failed to mark notification as read:', error)
    }
  }
  
  async function markAllAsRead() {
    try {
      await mintApi.put('/notifications/mark-all-read')
      notifications.value.forEach(n => n.read = true)
    } catch (error) {
      console.error('Failed to mark all as read:', error)
    }
  }
  
  function addNotification(notification: Omit<Notification, 'id' | 'read' | 'created_at'>) {
    notifications.value.unshift({
      ...notification,
      id: Date.now().toString(),
      read: false,
      created_at: new Date().toISOString()
    })
  }
  
  return {
    notifications,
    loading,
    unreadCount,
    unreadNotifications,
    fetchNotifications,
    markAsRead,
    markAllAsRead,
    addNotification
  }
})
```

### Example 5: Custom Repository

**Scenario**: Add complex query to EmployeesRepository

**api/custom/app/Repositories/EmployeesRepository.php**:
```php
<?php
namespace MintHCM\Custom\App\Repositories;

use MintHCM\App\Repositories\EmployeesRepository as BaseRepository;
use MintHCM\App\Entities\Employee;

class EmployeesRepository extends BaseRepository
{
    /**
     * Find employees by department with salary range
     */
    public function findByDepartmentAndSalaryRange(
        string $department,
        float $minSalary,
        float $maxSalary
    ): array {
        return $this->createQueryBuilder('e')
            ->where('e.department = :dept')
            ->andWhere('e.salary >= :min')
            ->andWhere('e.salary <= :max')
            ->andWhere('e.status = :status')
            ->setParameter('dept', $department)
            ->setParameter('min', $minSalary)
            ->setParameter('max', $maxSalary)
            ->setParameter('status', 'Active')
            ->orderBy('e.salary', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Get employee statistics by department
     */
    public function getStatisticsByDepartment(): array
    {
        $qb = $this->createQueryBuilder('e');
        
        return $qb
            ->select('e.department')
            ->addSelect('COUNT(e.id) as employee_count')
            ->addSelect('AVG(e.salary) as avg_salary')
            ->addSelect('MAX(e.salary) as max_salary')
            ->addSelect('MIN(e.salary) as min_salary')
            ->where('e.status = :status')
            ->setParameter('status', 'Active')
            ->groupBy('e.department')
            ->orderBy('employee_count', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
```

### Example 6: Custom Constants

**Scenario**: Add custom module icons

**api/custom/constants/icons/ModuleIcons.php**:
```php
<?php
// These will be merged with core icons
return [
    'CustomModule' => 'mdi-custom-icon',
    'AnotherModule' => 'mdi-another-icon',
];
```

**Usage in code**:
```php
$icons = include 'constants/icons/ModuleIcons.php';
// Contains both core and custom icons
```

## Best Practices

### ✅ DO

1. **Always use custom/ directories** for all customizations
2. **Extend core classes** instead of replacing them
3. **Call parent methods** when overriding (unless intentionally replacing)
4. **Document custom code** with clear comments
5. **Follow naming conventions**: Match core naming patterns
6. **Test customizations** thoroughly before deploying
7. **Version control custom/** Make sure `custom/` is in your repository
8. **Use meaningful names** for custom files and classes

### ❌ DON'T

1. **Never modify core files** - they'll be overwritten on updates
2. **Don't duplicate core code** - extend and reuse instead
3. **Don't skip validation** in custom controllers
4. **Don't hardcode values** - use constants or configs
5. **Don't ignore security** - always validate input and check permissions
6. **Don't bypass CustomLoader** - use the pattern correctly
7. **Don't leave debug code** in production
8. **Don't modify protected regions** in auto-generated entities

## Migration Guide

### Moving Existing Customizations

If you have modifications in core files:

1. **Identify modified files**:
```bash
git diff origin/main -- api/app vue/src/components vue/src/views
```

2. **For each modified file**:
   - Create equivalent in `custom/` directory
   - Extend the core class
   - Move custom logic to custom class
   - Remove modifications from core file

3. **Test thoroughly** - ensure CustomLoader picks up your classes

4. **Commit custom files** to version control

### Example Migration

**Before** (modified core file):
```php
// api/app/Controllers/EmployeesController.php - WRONG!
class EmployeesController {
    public function list($request, $response) {
        // Modified core method
    }
}
```

**After** (custom file):
```php
// api/custom/app/Controllers/EmployeesController.php - CORRECT!
namespace MintHCM\Custom\App\Controllers;

use MintHCM\App\Controllers\EmployeesController as BaseController;

class EmployeesController extends BaseController {
    public function list($request, $response) {
        // Your custom logic
        return parent::list($request, $response);
    }
}
```

---

**Next Steps**:
- [Vue Frontend Guide](04-frontend-vue.md) - Frontend customization details
- [PHP Backend Guide](08-backend-php.md) - Backend customization details
- [MintLogic System](12-mintlogic.md) - Form logic customization
