---
applyTo:
    - "modules/**/logicdefs.php"
    - "legacy/**/Logicdefs/**"
    - "api/lib/MintLogic/**"
---

# MintLogic System

**Version**: 2.0  
**Last Updated**: 2026-01-02

MintLogic defines dynamic form behavior based on field values.

## Overview

Backend defines logic rules in `logicdefs.php`. Frontend applies rules automatically.

**Data Flow**:
```
User changes field
  ↓
bean.updateFields()
  ↓
Backend evaluates logicdefs
  ↓
Returns new logic state
  ↓
Frontend applies to form
```

## Logicdefs Structure

**Location**: `modules/{Module}/logicdefs.php`

```php
$logicdefs['Employees'] = [
    [
        'hook' => 'CHANGE',  // INIT, CHANGE, or ALL
        'trigger' => 'employment_status',  // Field that triggers rule
        'condition' => "equals(field('employment_status'), 'Terminated')",
        'actions' => [
            [
                'type' => 'REQUIRED',  // Action type
                'target' => 'termination_date',  // Target field
                'value' => true,  // New value
            ],
            [
                'type' => 'VISIBLE',
                'target' => 'termination_reason',
                'value' => true,
            ],
        ],
    ],
];
```

## Hooks

- `INIT` - Evaluate on record load only
- `CHANGE` - Evaluate on field change only
- `ALL` - Evaluate on both load and change

## Action Types

| Type | Description | Value |
|------|-------------|-------|
| `VISIBLE` | Show/hide field | `true`/`false` |
| `REQUIRED` | Make field required | `true`/`false` |
| `READONLY` | Make field readonly | `true`/`false` |
| `UPDATE` | Update field value | Any value |
| `VALIDATION` | Add validation rule | Validation object |
| `OPTIONS` | Update enum options | Array of options |

## Formulas

### Field Access
- `field('name')` - Current field value
- `$new.name` - New value in CHANGE context
- `$old.name` - Old value in CHANGE context

### Comparison
- `equals(a, b)` - a == b
- `notEquals(a, b)` - a != b
- `greaterThan(a, b)` - a > b
- `lessThan(a, b)` - a < b
- `notEmpty(a)` - a is not empty

### Logic
- `and(a, b, ...)` - All true
- `or(a, b, ...)` - Any true
- `not(a)` - Negate

### String
- `concat(a, b, ...)` - Join strings
- `matches(field, pattern)` - Regex match

## Complete Examples

### Example 1: Conditional Required Fields

```php
[
    'hook' => 'CHANGE',
    'trigger' => 'employment_status',
    'condition' => "equals(field('employment_status'), 'Terminated')",
    'actions' => [
        ['type' => 'REQUIRED', 'target' => 'termination_date', 'value' => true],
        ['type' => 'VISIBLE', 'target' => 'termination_reason', 'value' => true],
    ],
]
```

### Example 2: Auto-Calculate Field

```php
[
    'hook' => 'CHANGE',
    'trigger' => 'salary',
    'condition' => "notEmpty(field('salary'))",
    'actions' => [
        [
            'type' => 'UPDATE',
            'target' => 'annual_salary',
            'value' => "multiply(field('salary'), 12)",
        ],
    ],
]
```

### Example 3: Complex Conditions

```php
[
    'hook' => 'CHANGE',
    'trigger' => 'department',
    'condition' => "or(
        equals(field('department'), 'IT'),
        equals(field('department'), 'Engineering')
    )",
    'actions' => [
        ['type' => 'VISIBLE', 'target' => 'tech_stack', 'value' => true],
        ['type' => 'REQUIRED', 'target' => 'tech_stack', 'value' => true],
    ],
]
```

### Example 4: Dynamic Options

```php
[
    'hook' => 'CHANGE',
    'trigger' => 'country',
    'condition' => "equals(field('country'), 'USA')",
    'actions' => [
        [
            'type' => 'OPTIONS',
            'target' => 'state',
            'value' => [
                'CA' => 'California',
                'NY' => 'New York',
                'TX' => 'Texas',
            ],
        ],
    ],
]
```

## Frontend Integration

```typescript
// Fields automatically respect logic
const bean = useBean('Employees', '123')
await bean.init()

// Check logic state
const isFieldHidden = bean.logic.hiddenFields.value.has('salary')
const isFieldRequired = bean.logic.requiredFields.value.has('email')
const isFieldReadonly = bean.logic.readonlyFields.value.has('employee_id')
```

## Best Practices

**Do**:
- Keep conditions simple
- Use multiple small rules over complex ones
- Test edge cases

**Don't**:
- Create circular dependencies
- Rely on external state
- Hardcode values (use constants)

**Full Documentation**: `api/documentation/07-mintlogic.md`

---

**Related**: [CRUD Operations](07-crud-operations.md), [Field System](05-field-system.md), [Validation & Security](13-validation-security.md)
