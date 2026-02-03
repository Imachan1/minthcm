---
applyTo:
  - "api/**/*.php"
  - "vue/src/api/**"
  - "vue/src/composables/**"
  - "api/app/Middlewares/**"
---

# Validation & Security

Security and validation patterns for MintHCM.

## Frontend Validation

### Bean Validation

```typescript
const bean = useBean('Employees')
bean.updateFields({
  email: 'invalid-email',  // Will fail validation
})

if (!bean.isValid.value) {
  console.log(bean.errorMessages.value)
  // { email: 'Invalid email format' }
}
```

### Manual Validation

```typescript
import { validateEmail } from '@/utils/validation'

const isValidEmail = validateEmail('test@example.com')
```

## Backend Validation

### Controller Validation

```php
use Respect\Validation\Validator as v;
use MintHCM\Exceptions\ValidationException;

class EmployeesController {
    public function createRecord($request, $response, $args) {
        $data = json_decode($request->getBody()->getContents(), true);
        
        // Validate required fields
        $validator = v::key('first_name', v::stringType()->notEmpty())
            ->key('email', v::email());
        
        try {
            $validator->assert($data);
        } catch (\Exception $e) {
            throw new ValidationException($e->getMessage());
        }
        
        // Process...
    }
}
```

### Entity Validation

```php
// In Repository
public function validateEmployee(Employee $employee): void {
    if (empty($employee->getFirstName())) {
        throw new ValidationException('First name is required');
    }
    
    if (!filter_var($employee->getEmail(), FILTER_VALIDATE_EMAIL)) {
        throw new ValidationException('Invalid email format');
    }
}
```

## Authentication

### JWT Token (Frontend)

```typescript
// Stored in localStorage
const token = localStorage.getItem('access_token')

// Axios interceptor attaches automatically
axios.interceptors.request.use(config => {
  config.headers.Authorization = `Bearer ${token}`
  return config
})

// Redirect on 401
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      router.push('/login')
    }
    return Promise.reject(error)
  }
)
```

### Route Authentication (Backend)

```php
// Require authentication (default)
[
    'method' => 'GET',
    'path' => '/employees/{id}',
    'class' => EmployeesController::class,
    'function' => 'getRecord',
    'auth' => true,  // JWT required
]

// Public endpoint
[
    'method' => 'POST',
    'path' => '/login',
    'class' => AuthController::class,
    'function' => 'login',
    'auth' => false,  // No authentication
]
```

## Access Control (ACL)

### Backend ACL Check

```php
// RouteAccessMiddleware checks automatically
// Per-module ACL defined in modules/{Module}/module.php
```

### Frontend ACL Check

```typescript
import { useModulesStore } from '@/store/modules'

const modules = useModulesStore()
const acl = modules.getModuleACL('Employees')

if (acl.edit) {
  // User can edit employees
}
```

## Security Best Practices

**Do**:
- Validate ALL user input
- Use parameterized queries
- Check ACL in controllers
- Sanitize output
- Use HTTPS
- Rotate JWT secrets

**Don't**:
- Trust client-side validation alone
- Hardcode credentials
- Skip authentication (`auth => false` only for public endpoints)
- Expose sensitive data in responses
- Use plain text passwords

## CLIENT_SECRET Generation

```bash
./MintCLI oauth2:repairFrontend
# Copy generated secret to vue/.env
```

## Input Sanitization

```php
// Backend
$cleanInput = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

// Frontend (Vue escapes automatically in templates)
<div>{{ userInput }}</div>  <!-- Auto-escaped -->
<div v-html="sanitizedHtml"></div>  <!-- Use with caution -->
```

**Full Documentation**: `api/documentation/08-validation.md`, `api/documentation/09-authentication.md`

---

**Related**: [Routing & Controllers](09-routing-controllers.md), [MintLogic](12-mintlogic.md), [PHP Backend](08-backend-php.md)
