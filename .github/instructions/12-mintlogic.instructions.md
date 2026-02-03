---
applyTo:
    - "modules/**/logicdefs.php"
    - "legacy/**/Logicdefs/**"
    - "api/lib/MintLogic/**"
---

# MintLogic System

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

## Modern MintLogic Format (v2)

MintHCM uses a newer format with Formula class and Hook constants:

```php
<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'rules' => [
        'rule_name' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['field_name'],
            'trigger' => Formula::equals('$field_name', 'value'),
            'logic' => [
                'visible' => ['target_field' => true],
            ],
        ],
    ],
];
```

### Formula Functions

**Comparison**:
- `Formula::equals($a, $b)` - Check equality
- `Formula::notEquals($a, $b)` - Check inequality
- `Formula::greaterThan($a, $b)` - Greater than
- `Formula::lessThan($a, $b)` - Less than
- `Formula::empty($a)` - Check if empty
- `Formula::notEmpty($a)` - Check if not empty

**Arrays**:
- `Formula::inArray($field, $array)` - Check if field value in array
- `Formula::notInArray($field, $array)` - Check if field value not in array

**Logic**:
- `Formula::and($a, $b, ...)` - All conditions true
- `Formula::or($a, $b, ...)` - Any condition true
- `Formula::not($a)` - Negate condition

**CRITICAL**: `Formula::inArray('$field', ['val1', 'val2'])` - Field first, array second!

## Visibility Pattern (Show/Hide Fields)

**CRITICAL**: Visibility rules require BOTH show and hide rules!

```php
'field_show' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['type'],
    'trigger' => Formula::equals('$type', 'other'),
    'logic' => [
        'visible' => [
            'other_field' => true,
        ],
    ],
],
'field_hide' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['type'],
    'trigger' => Formula::notEquals('$type', 'other'),
    'logic' => [
        'visible' => [
            'other_field' => false,
        ],
    ],
],
```

**Why both rules?**
- Show rule: Makes field visible when condition is true
- Hide rule: Hides field when condition is false
- Without both, field may stay in wrong state

## Common Errors

### Error 1: Formula::inArray() Arguments

**Wrong**:
```php
Formula::inArray('value', '$field')  // ❌ Reversed!
```

**Correct**:
```php
Formula::inArray('$field', ['value'])  // ✅ Field first, array second
```

### Error 2: Missing Hide Rule

**Wrong**:
```php
'show_field' => [
    'trigger' => Formula::equals('$type', 'other'),
    'logic' => ['visible' => ['field' => true]],
],
// Field stays visible even when type changes!
```

**Correct**:
```php
'show_field' => [
    'trigger' => Formula::equals('$type', 'other'),
    'logic' => ['visible' => ['field' => true]],
],
'hide_field' => [  // ✅ Add opposite rule
    'trigger' => Formula::notEquals('$type', 'other'),
    'logic' => ['visible' => ['field' => false]],
],
```

### Error 3: Type Mismatch

**Wrong**:
```php
Formula::inArray('other', '$type')  // ❌ String instead of array
```

**Error**: `array_map(): Argument #2 ($array) must be of type array, string given`

**Correct**:
```php
Formula::equals('$type', 'other')  // ✅ For single value
// OR
Formula::inArray('$type', ['other'])  // ✅ For array
```

## Best Practices

**Do**:
- ✅ Always create BOTH show and hide rules for visibility
- ✅ Use `Formula::equals()` for single value checks
- ✅ Use `Formula::inArray()` for multiple value checks
- ✅ Keep conditions simple
- ✅ Use multiple small rules over complex ones
- ✅ Test edge cases
- ✅ Use `Hook::ALL, Hook::CHANGE` as standard pattern

**Don't**:
- ❌ Create only show OR hide rule (need both!)
- ❌ Reverse arguments in Formula::inArray()
- ❌ Create circular dependencies
- ❌ Rely on external state
- ❌ Hardcode values (use constants)
- ❌ Use `Hook::INIT, Hook::CHANGE` (use `Hook::ALL`)

**Full Documentation**: `api/documentation/07-mintlogic.md`

---

**Related**: 
- [CRUD Operations](07-crud-operations.md) - useBean composable
- [Field System](05-field-system.md) - Dynamic field rendering
- [Validation & Security](13-validation-security.md) - Input validation
- [Legacy Migration](17-legacy-migration.instructions.md) - Migrating View Tools to MintLogic
