---
applyTo:
  - "api/tests/**"
  - "tests/**"
  - "vue/**/tests/**"
  - "vue/**/e2e/**"
  - "api/phpunit.xml"
  - "vue/package.json"
---

# Testing Guide

**Version**: 2.0  
**Last Updated**: 2026-01-02

Testing patterns for frontend and backend.

## Frontend Testing

### Unit Tests (Vitest)

```bash
cd vue
npm run test:unit
```

**Example Test**:
```typescript
// tests/unit/components/MyComponent.spec.ts
import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import MyComponent from '@/components/MyComponent.vue'

describe('MyComponent', () => {
  it('renders properly', () => {
    const wrapper = mount(MyComponent, {
      props: { msg: 'Hello' }
    })
    expect(wrapper.text()).toContain('Hello')
  })
})
```

### E2E Tests (Playwright/Cypress)

```bash
npm run test:e2e
```

**Example**:
```typescript
// e2e/employees.spec.ts
import { test, expect } from '@playwright/test'

test('create employee', async ({ page }) => {
  await page.goto('/employees/create')
  await page.fill('#first_name', 'John')
  await page.fill('#last_name', 'Doe')
  await page.click('button[type=submit]')
  
  await expect(page.locator('.success-message')).toBeVisible()
})
```

## Backend Testing

### PHPUnit

```bash
cd api
./vendor/bin/phpunit
# or
php runTests.php
```

### Test Structure

```
api/tests/
├── Unit/           # Unit tests
├── Integration/    # Integration tests
└── Functional/     # API tests
```

### Example Unit Test

```php
// api/tests/Unit/EmployeeRepositoryTest.php
namespace MintHCM\Tests\Unit;

use PHPUnit\Framework\TestCase;

class EmployeeRepositoryTest extends TestCase {
    public function testFindActiveEmployees() {
        $repository = $this->getMockRepository();
        $employees = $repository->findActiveEmployees();
        
        $this->assertIsArray($employees);
        $this->assertGreaterThan(0, count($employees));
    }
}
```

### Example Functional Test

```php
// api/tests/Functional/EmployeesApiTest.php
namespace MintHCM\Tests\Functional;

use MintHCM\Tests\ApiTestCase;

class EmployeesApiTest extends ApiTestCase {
    public function testGetEmployee() {
        $response = $this->request('GET', '/api/employees/1');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('id', $response->getData());
    }
    
    public function testCreateEmployee() {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ];
        
        $response = $this->request('POST', '/api/employees', $data);
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $response->getData());
    }
}
```

## Test Best Practices

**Do**:
- Write tests for new features
- Test edge cases
- Use mocks for external dependencies
- Keep tests isolated
- Run tests before committing

**Don't**:
- Test implementation details
- Write brittle tests
- Skip cleanup
- Hardcode test data

## Coverage

```bash
# Frontend
npm run test:unit -- --coverage

# Backend
./vendor/bin/phpunit --coverage-html coverage
```

**Full Documentation**: `api/documentation/12-testing.md`, `vue/documentation/testing.md`

---

**Related**: [PHP Backend](08-backend-php.md), [Vue Frontend](04-frontend-vue.md), [Troubleshooting](15-troubleshooting.md)
