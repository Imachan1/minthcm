---
applyTo:
    - "api/**/Entities/**"
    - "api/**/Repositories/**"
    - "modules/**/vardefs.php"
    - "legacy/**/vardefs.php"
    - "legacy/custom/**/Ext/Vardefs/**"
---

# Doctrine ORM

Doctrine entities are auto-generated from vardefs.

## Entity Generation Workflow

1. Edit `modules/{Module}/vardefs.php`
2. Run "Quick Repair and Rebuild" in Admin → Repair
3. Entities auto-generate in `api/app/Entities/{Module}.php`
4. Add custom methods outside protected regions

## Vardef Example

```php
// modules/Employees/vardefs.php
$dictionary['Employee'] = [
    'table' => 'employees',
    'fields' => [
        'first_name' => [
            'name' => 'first_name',
            'type' => 'string',
            'len' => 100,
            'required' => true,
        ],
        'email' => [
            'name' => 'email',
            'type' => 'email',
            'len' => 255,
        ],
    ],
];
```

## Generated Entity

```php
// api/app/Entities/Employee.php
namespace MintHCM\App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MintHCM\App\Repositories\EmployeesRepository")
 * @ORM\Table(name="employees")
 */
class Employee {
    // BEGIN PROTECTED REGION [Employee fields] - DO NOT EDIT
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $firstName;
    // END PROTECTED REGION
    
    // Custom methods go here (outside protected regions)
    public function getFullName(): string {
        return $this->firstName . ' ' . $this->lastName;
    }
}
```

## Working with Entities

### Create
```php
$employee = new \MintHCM\App\Entities\Employee();
$employee->setFirstName('John');
$employee->setEmail('john@example.com');

$entityManager->persist($employee);
$entityManager->flush();
```

### Read
```php
$repository = $entityManager->getRepository('Employees');
$employee = $repository->find($id);
```

### Update
```php
$employee = $repository->find($id);
$employee->setFirstName('Jane');
$entityManager->flush();
```

### Delete
```php
$employee = $repository->find($id);
$entityManager->remove($employee);
$entityManager->flush();
```

## QueryBuilder

```php
$qb = $repository->createQueryBuilder('e');
$results = $qb
    ->where('e.status = :status')
    ->andWhere('e.salary > :salary')
    ->setParameter('status', 'Active')
    ->setParameter('salary', 50000)
    ->orderBy('e.firstName', 'ASC')
    ->getQuery()
    ->getResult();
```

## Critical Rules

**Never**:
- Edit protected regions in entities
- Modify entities directly without regenerating
- Skip "Quick Repair" after vardef changes

**Always**:
- Add custom methods outside protected blocks
- Use repositories for business logic
- Flush EntityManager after modifications

**Full Documentation**: `api/documentation/05-working-with-entities.md`

---

**Related**: [PHP Backend](08-backend-php.md), [Routing & Controllers](09-routing-controllers.md), [Legacy Integration](11-legacy-integration.md)
