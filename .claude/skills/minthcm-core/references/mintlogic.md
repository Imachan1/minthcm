# MintLogic -- Core Developer Reference

## Overview

MintLogic defines dynamic form behavior (visibility, required, readonly, updates, validation, options) server-side. No frontend code changes needed.

## File Locations

```
api/lib/MintLogic/Modules/{ModuleName}/*logicdefs.php     # Core
api/custom/lib/MintLogic/{ModuleName}/*logicdefs.php       # Custom (merged via array_merge_recursive)
```

Valid filenames: `logicdefs.php`, `validation.logicdefs.php`, `visibility.logicdefs.php`, etc.

## File Structure

```php
<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'bean' => [
        'validation' => [
            // Bean-level validators (run before save)
            DelegationValidator::class,
        ],
    ],
    'rules' => [
        'rule_name' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],    // When to run
            'triggerFields' => ['status', 'type'],    // Which field changes trigger this
            'trigger' => Formula::equals('$status', 'active'),  // Condition to apply
            'logic' => [
                'visible' => ['field_name' => true],
                'readonly' => ['field_name' => true],
                'required' => ['field_name' => true],
                'update' => ['field_name' => 'value'],
                'validation' => ['field_name' => ValidatorClass::class],
                'options' => ['field_name' => function($bean) { return [...]; }],
            ],
        ],
    ],
];
```

## Hooks

| Hook | When | Use for |
|------|------|---------|
| `Hook::INIT` | Form load (fetch record) | Default values, initial visibility |
| `Hook::CHANGE` | Field value changes | Reactive behavior |
| `Hook::ALL` | Both init and change | Most rules use `[Hook::ALL, Hook::CHANGE]` |

## Formula Helpers

```php
// Comparisons
Formula::equals('$field', 'value')
Formula::equals('$field1', '$field2')
Formula::notEquals('$field', 'value')
Formula::inArray('$status', ['active', 'planned'])
Formula::notInArray('$status', ['closed', 'cancelled'])

// Empty checks
Formula::empty('$field')
Formula::notEmpty('$field')

// Logical operators
Formula::and(Formula::equals('$a', '1'), Formula::notEmpty('$b'))
Formula::or(Formula::equals('$type', 'x'), Formula::equals('$type', 'y'))
Formula::not(Formula::equals('$field', 'value'))

// Validation with error message
Formula::validate(Formula::notEmpty('$field'), 'ERR_FIELD_REQUIRED')
```

### Field References in Formulas

- `$field_name` -- current value
- `$new.field_name` -- new value (after change)
- `$old.field_name` -- old value (before change)

## Logic Types

### visible -- Show/Hide fields

**Important: visibility rules need BOTH show AND hide rules.**

```php
'show_transport' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['has_transport'],
    'trigger' => Formula::equals('$has_transport', '1'),
    'logic' => ['visible' => ['transport_type' => true]],
],
'hide_transport' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['has_transport'],
    'trigger' => Formula::notEquals('$has_transport', '1'),
    'logic' => ['visible' => ['transport_type' => false]],
],
```

### required -- Conditional required

```php
'rejection_reason_required' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['status'],
    'trigger' => Formula::equals('$status', 'rejected'),
    'logic' => ['required' => ['rejection_reason' => true]],
],
'rejection_reason_not_required' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['status'],
    'trigger' => Formula::notEquals('$status', 'rejected'),
    'logic' => ['required' => ['rejection_reason' => false]],
],
```

### readonly -- Lock fields

```php
'init_readonly' => [
    'hooks' => [Hook::INIT],
    'logic' => ['readonly' => [
        'total_amount' => true,
        'exchange_rate' => true,
    ]],
],
```

### update -- Calculate field values

```php
// Static
'logic' => ['update' => ['full_name' => 'calculated']],

// Dynamic (function must always return array)
'copy_from_related' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['delegation_locale_name'],
    'logic' => [
        'update' => function ($bean) {
            $locale = \BeanFactory::getBean('DelegationsLocale', $bean->delegation_locale_id);
            if ($locale) {
                return ['currency_id' => $locale->currency_id];
            }
            return [];  // Must always return array
        },
    ],
],
```

### validation -- Field-level validators

```php
'logic' => ['validation' => ['name' => IsUnique::class]],
```

### options -- Dynamic enum options

```php
'logic' => ['options' => [
    'sub_type' => function ($bean) {
        if ($bean->type === 'internal') {
            return ['dept_a' => 'Department A', 'dept_b' => 'Department B'];
        }
        return ['ext_a' => 'External A', 'ext_b' => 'External B'];
    },
]],
```

## Bean-Level Validation

```php
return [
    'bean' => [
        'validation' => [
            DelegationValidator::class,
        ],
    ],
];
```

Validator class:
```php
<?php
namespace MintHCM\Lib\MintLogic\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class DelegationValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if ($bean->date_from > $bean->date_to) {
            throw new ValidationException('ERR_INVALID_DATE_RANGE');
        }
    }
}
```

## API Integration

- `getInitial()` -- called when fetching a record, evaluates `Hook::INIT` and `Hook::ALL` rules
- `getChanged()` -- called when fields change, evaluates `Hook::CHANGE` and `Hook::ALL` rules for trigger fields
- `validateBean()` -- called before save, runs bean-level and field-level validators

API response includes `logic` section:
```json
{
  "data": { "id": "123", "status": "active" },
  "logic": {
    "rules": [{
      "key": "rule_name",
      "trigger": true,
      "logic": { "visible": {"field": true}, "required": {"field": true} }
    }]
  }
}
```

## Migration from View Tools

| View Tool | MintLogic | Notes |
|-----------|-----------|-------|
| `vt_dependency` | `visible` | Needs BOTH show AND hide rules |
| `vt_calculated` | `update` | Use function with BeanFactory |
| `vt_required` | `required` | Needs BOTH true AND false rules |
| `vt_readonly` | `readonly` | Single rule often sufficient |

Example migration:
```php
// Old: 'vt_dependency' => "inArray('other', $type)"
// New:
'field_show' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['type'],
    'trigger' => Formula::equals('$type', 'other'),
    'logic' => ['visible' => ['field_name' => true]],
],
'field_hide' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['type'],
    'trigger' => Formula::notEquals('$type', 'other'),
    'logic' => ['visible' => ['field_name' => false]],
],
```

## Common Mistakes

1. **Missing hide rule for visibility** -- only defining `visible => true` without a corresponding `visible => false` rule. Field won't toggle properly.
2. **Update function not returning array** -- `update` functions must always return an array, even `return []` when nothing changes.
3. **Using `Hook::INIT` alone for reactive rules** -- fields that should react to changes need `Hook::CHANGE` (typically `[Hook::ALL, Hook::CHANGE]`).
4. **Forgetting `triggerFields`** -- without it, the rule runs on every field change, causing performance issues.
5. **Direct DB queries in formulas** -- formulas don't support database access. Use `update` functions with `BeanFactory::getBean()` or custom validators.
