---
applyTo:
    - "legacy/**"
    - "api/utils/**"
    - "api/**/Controllers/**"
---

# Legacy Integration

MintHCM includes legacy SuiteCRM code for backward compatibility.

## LegacyConnector

Wraps legacy code execution:

```php
use MintHCM\Lib\LegacyConnector;

$result = LegacyConnector::getInstance()->doAction(function () {
    // Legacy code runs here
    $bean = BeanFactory::getBean('Employees', $id);
    return $bean->first_name;
});
```

## When to Use Legacy

**Use legacy** for:
- Existing modules without Doctrine entities
- Complex legacy business logic
- Legacy hooks/logic hooks
- Email templates

**Use Doctrine** for:
- New features
- Simple CRUD operations
- Modern API endpoints

## Legacy Bean Example

```php
LegacyConnector::getInstance()->doAction(function () use ($id) {
    global $current_user;
    
    $bean = BeanFactory::getBean('Employees', $id);
    $bean->first_name = 'John';
    $bean->save();
    
    return $bean->id;
});
```

## Mixing Legacy and Modern

```php
namespace MintHCM\App\Controllers;

use MintHCM\Lib\LegacyConnector;

class EmployeesController {
    public function getRecord($request, $response, $args) {
        $id = $args['id'];
        
        // Modern: Fetch via Doctrine
        $repository = $this->entityManager->getRepository('Employees');
        $entity = $repository->find($id);
        
        // Legacy: Call legacy business logic
        $legacyData = LegacyConnector::getInstance()->doAction(function () use ($id) {
            $bean = \BeanFactory::getBean('Employees', $id);
            return $bean->getCustomCalculation();
        });
        
        // Combine results
        $data = [
            'id' => $entity->getId(),
            'first_name' => $entity->getFirstName(),
            'legacy_calc' => $legacyData,
        ];
        
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

## Legacy File Locations

```
legacy/
├── include/          # Core legacy classes
├── modules/          # Legacy module code
├── custom/           # Legacy customizations
└── data/             # Legacy data classes
```

## Migration Strategy

1. Start with Doctrine for new features
2. Use LegacyConnector when legacy logic is required
3. Gradually refactor legacy code to Doctrine
4. Keep legacy wrapped in LegacyConnector closures

**Full Documentation**: `api/documentation/11-legacy-integration.md`

---

**Related**: [PHP Backend](08-backend-php.md), [Doctrine ORM](10-doctrine-orm.md), [Routing & Controllers](09-routing-controllers.md)
