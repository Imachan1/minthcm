# Data Layer -- Core Developer Reference

## Vardefs (Field Definitions)

Every module's schema is defined in `legacy/modules/{Module}/vardefs.php`:

```php
// legacy/modules/Employees/vardefs.php
$dictionary['Employee']['fields']['first_name'] = [
    'name' => 'first_name',
    'type' => 'varchar',
    'len' => 255,
    'label' => 'LBL_FIRST_NAME',
    'required' => true,
];
$dictionary['Employee']['fields']['hire_date'] = [
    'name' => 'hire_date',
    'type' => 'date',
    'label' => 'LBL_HIRE_DATE',
];
$dictionary['Employee']['fields']['salary'] = [
    'name' => 'salary',
    'type' => 'decimal',
    'len' => '26,6',
    'label' => 'LBL_SALARY',
];
```

Custom fields go in `legacy/custom/modules/{Module}/Ext/Vardefs/`.

## Vardefs to Doctrine Entity (Auto-Generation)

**Quick Repair and Rebuild** (Admin > Repair) scans all vardefs and generates `api/app/Entities/{Module}.php`.

### Column Type Mapping

| Vardefs type | Doctrine type | PHP type |
|-------------|---------------|----------|
| varchar/char | string | string |
| text | text | string |
| int | integer | int |
| bool | boolean | bool |
| date | date | \DateTime |
| datetime/datetimecombo | datetime | \DateTime |
| decimal/currency | decimal | string |
| enum/multienum | string | string |
| relate | string | string (stores ID) |

### Generated Entity Structure

```php
<?php
namespace MintHCM\Api\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

// Auto-generated SectionUse section start
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
// Auto-generated SectionUse section end

// SAFE: custom imports here

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\EmployeesRepository")
 * @ORM\Table(name="employees")
 */
class Employees
{
    // Auto-generated SectionProperties section start
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @ORM\Column(type="string", length="36")
     */
    public $id;

    /** @ORM\Column(type="string", length="255") */
    public $first_name;

    /** @ORM\Column(type="datetime") */
    public $date_entered;
    // Auto-generated SectionProperties section end

    // SAFE: custom properties here

    // Auto-generated SectionMethods section start
    public function __construct() { }
    public function getIdentifier(): string { return $this->id; }
    // Auto-generated SectionMethods section end

    // SAFE: custom methods here
    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
```

### Protected Sections

| Section | Contains | Editable? |
|---------|----------|-----------|
| `SectionUse` | Auto-generated imports | NO -- add custom imports outside |
| `SectionRepository` | @ORM\Entity, @ORM\Table annotations | NO |
| `SectionProperties` | @ORM\Column property declarations | NO -- add properties outside or via custom/ |
| `SectionMethods` | Constructor, getIdentifier() | NO -- add methods outside |

**Everything between `// Auto-generated Section... start` and `// ...end` is overwritten on Quick Repair.**

## Repositories

```php
<?php
namespace MintHCM\Api\Repositories;

use Doctrine\ORM\EntityRepository;

class EmployeesRepository extends EntityRepository
{
    public function findActiveEmployees(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.status = :status')
            ->setParameter('status', 'Active')
            ->orderBy('e.last_name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
```

### QueryBuilder Patterns

```php
// Filtering
$qb->where('e.status = :status')->setParameter('status', 'Active');
$qb->andWhere('e.is_admin = :admin')->setParameter('admin', true);
$qb->where('e.name LIKE :name')->setParameter('name', '%John%');
$qb->where($qb->expr()->in('e.id', [1, 2, 3]));

// Joining
$qb->join('e.department', 'd')->where('d.name = :dept');
$qb->leftJoin('e.documents', 'doc');

// Ordering & Pagination
$qb->orderBy('e.last_name', 'ASC')->addOrderBy('e.first_name', 'ASC');
$qb->setFirstResult($offset)->setMaxResults($limit);

// Grouping & Aggregation
$qb->select('e.status, COUNT(e.id) as cnt')->groupBy('e.status');
$qb->select('SUM(e.salary)')->getQuery()->getSingleScalarResult();

// Executing
$qb->getQuery()->getResult();           // Array of entities
$qb->getQuery()->getOneOrNullResult();  // Single or null
$qb->getQuery()->getArrayResult();      // Array of arrays
$qb->getQuery()->getSingleScalarResult(); // Single value
```

## EntityManager

```php
// Get EntityManager
global $mint_app;
$em = $mint_app->getContainer()->get(\Doctrine\ORM\EntityManager::class);

// Create
$entity = new Employees();
$entity->first_name = 'John';
$entity->date_entered = new \DateTime();
$em->persist($entity);
$em->flush();

// Update (no persist needed for tracked entities)
$entity = $repository->find($id);
$entity->first_name = 'Jane';
$em->flush();

// Delete
$em->remove($entity);
$em->flush();

// Transaction
$em->beginTransaction();
try {
    $em->persist($entity);
    $em->flush();
    $em->commit();
} catch (\Exception $e) {
    $em->rollback();
    throw $e;
}
```

## Relationships (Annotations)

```php
// One-to-Many
/** @ORM\OneToMany(targetEntity="Document", mappedBy="employee") */
public $documents;

// Many-to-One
/** @ORM\ManyToOne(targetEntity="Employee", inversedBy="documents")
 *  @ORM\JoinColumn(name="employee_id", referencedColumnName="id") */
public $employee;

// Many-to-Many
/** @ORM\ManyToMany(targetEntity="Role", inversedBy="employees")
 *  @ORM\JoinTable(name="employee_roles",
 *      joinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")},
 *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
 *  ) */
public $roles;
```

Initialize collections in constructor:
```php
public function __construct() {
    $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
}
```

## Extending via custom/

```php
// custom/app/Entities/Employees.php
namespace MintHCM\Custom\Api\Entities;

use MintHCM\Api\Entities\Employees as BaseEmployees;
use Doctrine\ORM\Mapping as ORM;

class Employees extends BaseEmployees
{
    /** @ORM\Column(type="string", length=100, nullable=true) */
    public $custom_field;
}
```

```php
// custom/app/Repositories/EmployeesRepository.php
namespace MintHCM\Custom\Api\Repositories;

use MintHCM\Api\Repositories\EmployeesRepository as BaseRepository;

class EmployeesRepository extends BaseRepository
{
    public function findEligibleForBonus(): array { ... }
}
```

## Common Mistakes

1. **Editing inside auto-generated sections** -- code will be overwritten on next Quick Repair. Add custom code outside the section markers.
2. **Forgetting Quick Repair after vardefs change** -- entity will be out of sync with database. Always run Admin > Repair > Quick Repair and Rebuild.
3. **Flushing in a loop** -- `$em->flush()` inside `foreach` is slow. Batch: persist in loop, flush once (or every N items with `$em->clear()`).
4. **Missing `setParameter()`** -- building queries with string concatenation (`"e.id = $id"`) is vulnerable to SQL injection. Always use parameterized queries.
5. **N+1 queries** -- accessing lazy-loaded relationships in a loop triggers separate queries. Use `$qb->select('e, d')->join('e.department', 'd')` to eager-load.
