---
applyTo:
    - "**/*.{php,inc,ts,tsx,js,jsx,vue}"
---

# Coding Standards & Conventions

This document defines coding standards and naming conventions for all MintHCM development.

## 🌍 Language Guidelines

### Code and Documentation Language

**✅ ALWAYS use English for**:
- All code (variables, functions, classes, methods)
- Code comments and documentation
- Commit messages
- Technical documentation
- Instruction files

**Example**:
```php
// ✅ CORRECT
$user_name = 'John Doe';
class UserManager {}
public function getUserData() {}

// ❌ WRONG
$nazwa_uzytkownika = 'Jan Kowalski';
class ZarzadcaUzytkownikow {}
public function pobierzDaneUzytkownika() {}
```

### User Communication

**✅ Respond in user's language**:
- If user asks question in Polish → respond in Polish
- If user asks question in Spanish → respond in Spanish
- If user asks question in English → respond in English

**Code remains in English regardless of communication language.**

---

## 📝 PHP Naming Conventions

### Variable Names - snake_case

**✅ ALWAYS use `snake_case` for variables**:
```php
$user_name = 'John Doe';
$current_date = date('Y-m-d');
$employee_list = [];
$is_active = true;
$max_retry_count = 3;
```

**❌ DON'T use**:
```php
$userName = 'John Doe';      // camelCase - wrong for variables
$CurrentDate = date('Y-m-d'); // PascalCase - wrong for variables
$EMPLOYEE_LIST = [];          // SCREAMING_SNAKE_CASE - wrong for variables
```

### Class Names - PascalCase

**✅ ALWAYS use `PascalCase` for class names**:
```php
class UserManager {}
class DatabaseConnection {}
class EmployeesController {}
class OrderRepository {}
class PaymentService {}
```

**❌ DON'T use**:
```php
class user_manager {}        // snake_case - wrong for classes
class databaseConnection {}  // camelCase - wrong for classes
class EMPLOYEES_CONTROLLER {} // SCREAMING_SNAKE_CASE - wrong for classes
```

### Method Names - camelCase

**✅ ALWAYS use `camelCase` for methods**:
```php
public function getUserData() {}
public function validateInput() {}
public function processPayment() {}
private function calculateTotal() {}
protected function formatResponse() {}
```

**❌ DON'T use**:
```php
public function get_user_data() {}    // snake_case - wrong for methods
public function GetUserData() {}      // PascalCase - wrong for methods
public function VALIDATE_INPUT() {}   // SCREAMING_SNAKE_CASE - wrong for methods
```

### Class Constants - SCREAMING_SNAKE_CASE

**✅ ALWAYS use `SCREAMING_SNAKE_CASE` for class constants**:
```php
class UserManager {
    const MAX_LOGIN_ATTEMPTS = 5;
    const DEFAULT_TIMEOUT = 30;
    const SESSION_LIFETIME = 3600;
    const API_VERSION = '2.0';
}
```

**❌ DON'T use**:
```php
const maxLoginAttempts = 5;      // camelCase - wrong for constants
const MaxLoginAttempts = 5;      // PascalCase - wrong for constants
const max_login_attempts = 5;    // snake_case - wrong for constants
```

### Complete PHP Example

```php
<?php
namespace MintHCM\App\Controllers;

/**
 * Employees controller - handles employee-related requests
 */
class EmployeesController {
    // Constants in SCREAMING_SNAKE_CASE
    const MAX_RESULTS_PER_PAGE = 100;
    const DEFAULT_SORT_ORDER = 'ASC';
    const CACHE_LIFETIME = 3600;
    
    // Properties in snake_case
    private $entity_manager;
    private $is_initialized = false;
    private $cache_enabled = true;
    
    /**
     * Constructor
     * @param $entity_manager EntityManager instance
     */
    public function __construct($entity_manager) {
        $this->entity_manager = $entity_manager;
    }
    
    /**
     * Get list of users - method in camelCase
     * @param $request PSR-7 request
     * @param $response PSR-7 response
     * @param $args Route arguments
     * @return Response
     */
    public function getUserList($request, $response, $args) {
        // Local variables in snake_case
        $page_number = $args['page'] ?? 1;
        $results_per_page = self::MAX_RESULTS_PER_PAGE;
        $sort_order = self::DEFAULT_SORT_ORDER;
        
        // Method calls in camelCase
        $employee_repository = $this->entity_manager->getRepository('Employees');
        $employee_list = $employee_repository->findAll();
        
        return $this->formatResponse($response, $employee_list);
    }
    
    /**
     * Format response - private method in camelCase
     * @param $response Response object
     * @param $data Data to format
     * @return Response
     */
    private function formatResponse($response, $data) {
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    /**
     * Validate employee data - protected method in camelCase
     * @param $employee_data Employee data array
     * @return bool
     */
    protected function validateEmployeeData($employee_data) {
        $required_fields = ['first_name', 'last_name', 'email'];
        
        foreach ($required_fields as $field_name) {
            if (empty($employee_data[$field_name])) {
                return false;
            }
        }
        
        return true;
    }
}
```

---

## 🎨 Vue/TypeScript Naming Conventions

### Variables and Functions - camelCase

**✅ ALWAYS use `camelCase` for variables and functions**:
```typescript
const userName = 'John Doe'
const currentDate = new Date()
const employeeList: Employee[] = []

function getUserData() {}
function validateInput() {}
```

### Components - PascalCase

**✅ ALWAYS use `PascalCase` for Vue components**:
```typescript
// Component files
EmployeeList.vue
UserProfile.vue
DataTable.vue

// Component registration
import EmployeeList from '@/components/EmployeeList.vue'
```

### Composables - camelCase with 'use' prefix

**✅ ALWAYS prefix composables with 'use'**:
```typescript
// Files: useBean.ts, useLink.ts
export function useBean(module: string, id?: string) {}
export function useLink(module: string) {}
```

### Types and Interfaces - PascalCase

**✅ ALWAYS use `PascalCase` for types and interfaces**:
```typescript
interface Employee {
  id: string
  firstName: string
  lastName: string
}

type UserRole = 'admin' | 'user' | 'guest'
```

### Constants - SCREAMING_SNAKE_CASE or camelCase

**✅ For true constants, use `SCREAMING_SNAKE_CASE`**:
```typescript
const MAX_LOGIN_ATTEMPTS = 5
const API_BASE_URL = 'http://localhost:8080'
const DEFAULT_PAGE_SIZE = 20
```

**✅ For configuration objects, use `camelCase`**:
```typescript
const routeConfig = {
  path: '/employees',
  component: EmployeeList
}
```

---

## 📁 File Naming Conventions

### PHP Files

**✅ Match class name (PascalCase.php)**:
```
EmployeesController.php
UserManager.php
DatabaseConnection.php
OrderRepository.php
```

### Vue Files

**✅ PascalCase for components**:
```
EmployeeList.vue
UserProfile.vue
DataTable.vue
```

**✅ camelCase for composables**:
```
useBean.ts
useLink.ts
useValidation.ts
```

### Module/Config Files

**✅ kebab-case for non-class files**:
```
field-system.md
state-management.md
routing-controllers.md
```

---

## 💬 Comments and Documentation

### PHP DocBlocks

**✅ ALWAYS document public methods**:
```php
/**
 * Get employee by ID
 * 
 * @param string $employee_id Employee ID
 * @return Employee|null Employee object or null if not found
 * @throws NotFoundException When employee doesn't exist
 */
public function getEmployeeById($employee_id) {
    // Implementation
}
```

### TypeScript JSDoc

**✅ ALWAYS document exported functions**:
```typescript
/**
 * Fetch employee data from API
 * @param id - Employee ID
 * @returns Promise resolving to Employee object
 * @throws Error if employee not found
 */
export async function getEmployee(id: string): Promise<Employee> {
    // Implementation
}
```

### Inline Comments

**✅ Use inline comments for complex logic**:
```php
// Calculate total with tax (15% rate)
$total_with_tax = $subtotal * 1.15;

// Check if user has admin privileges before allowing deletion
if ($user->hasRole('admin')) {
    $this->deleteRecord($id);
}
```

**❌ Don't state the obvious**:
```php
// ❌ BAD: Comment adds no value
$i = 0; // Set i to 0

// ✅ GOOD: Comment explains WHY
$retry_count = 0; // Start retry counter for connection attempts
```

---

## 🔍 Code Review Checklist

When reviewing code or writing new code, ensure:

### Naming Conventions
- [ ] PHP variables use `snake_case`
- [ ] PHP classes use `PascalCase`
- [ ] PHP methods use `camelCase`
- [ ] PHP constants use `SCREAMING_SNAKE_CASE`
- [ ] TypeScript follows camelCase/PascalCase appropriately
- [ ] All code written in English (no foreign language names)

### Code Quality
- [ ] No hardcoded values (use constants/configs)
- [ ] Proper error handling
- [ ] Input validation present
- [ ] Security checks in place
- [ ] Documentation present for public APIs

### MintHCM Specific
- [ ] Customizations in `custom/` directory only
- [ ] No modifications to core files
- [ ] CustomLoader pattern followed
- [ ] Vardefs updated if schema changed
- [ ] Quick Repair run after vardef changes

---

## 🚫 Common Mistakes to Avoid

### Mixing Naming Conventions

**❌ DON'T mix conventions**:
```php
class user_manager {  // Wrong: class should be PascalCase
    private $userName;  // Wrong: property should be snake_case
    
    public function get_user_data() {}  // Wrong: method should be camelCase
}
```

**✅ DO use consistent conventions**:
```php
class UserManager {
    private $user_name;
    
    public function getUserData() {}
}
```

### Non-English Names

**❌ DON'T use non-English names**:
```php
$uzytkownik = 'Jan';      // Polish
$nombre_usuario = 'Juan'; // Spanish
$nom_utilisateur = 'Jean'; // French
```

**✅ DO use English names**:
```php
$user_name = 'John';
$employee_id = '123';
$order_total = 99.99;
```

### Inconsistent Casing

**❌ DON'T be inconsistent**:
```php
$user_name = 'John';
$userData = [];       // Mixing snake_case and camelCase
$UserID = '123';      // Wrong casing
```

**✅ DO be consistent**:
```php
$user_name = 'John';
$user_data = [];
$user_id = '123';
```

---

**Related**: [Project Structure](01-project-structure.md), [Customization Patterns](03-customization.md)

**For detailed coding examples, see topic-specific instruction files.**
