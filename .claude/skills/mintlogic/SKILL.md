---
name: mintlogic
description: "Use this skill when adding or editing MintLogic rules for a MintHCM module. Triggers on: 'add mintlogic', 'dodaj mintlogic', 'logicdefs', 'form logic', 'show/hide field', 'required when', 'calculate field', 'dynamic field'. Do NOT use for legacy View Tools (vt_dependency etc.) — use /migrate-module for that."
---

# Add MintLogic Rules to MintHCM Module

Add dynamic form behavior rules (visibility, required, readonly, calculated fields) to a module using the MintLogic v2 system.

## Input

$ARGUMENTS

Parse the input for:
- **Module name** (required) — e.g., `Employees`, `Transportations`
- **Description of logic** (optional) — what fields should react to what

If module name is missing, ask the user before proceeding.

## Step 1: Find Existing Logicdefs

Check both possible locations for the module:
1. `api/lib/MintLogic/Modules/{Module}/logicdefs.php` — new location
2. `modules/{Module}/logicdefs.php` — legacy location

Read the file if it exists. If neither exists, create at: `api/lib/MintLogic/Modules/{Module}/logicdefs.php`

## Step 2: Understand the Required Logic

If the user hasn't described the logic, ask:
- Which field(s) trigger the rule?
- What happens when the condition is met? (show/hide, required, readonly, calculate)
- What's the condition? (equals value X, not empty, in set of values, etc.)

Also read relevant vardefs to confirm field names:
- `modules/{Module}/vardefs.php` or `legacy/modules/{Module}/vardefs.php`

## Step 3: Write Rules Using Modern v2 Format

**Always use the v2 format with Formula and Hook classes:**

```php
<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'rules' => [
        // Rules go here, keyed by descriptive name
    ],
];
```

### Rule Structure

```php
'rule_name' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],  // Standard pattern
    'triggerFields' => ['trigger_field'],   // Fields that cause re-evaluation
    'trigger' => Formula::equals('$trigger_field', 'value'),  // Condition
    'logic' => [
        'visible' => ['target_field' => true],   // Action
    ],
],
```

### Hooks

- `[Hook::ALL, Hook::CHANGE]` — standard, evaluates on load AND on field change
- `[Hook::ALL]` — global rule without change trigger (e.g., always readonly)
- `[Hook::INIT, Hook::CHANGE]` — only when record loads or specific field changes

### Logic Types

| Key | Effect | Value |
|-----|--------|-------|
| `visible` | Show/hide field | `['field' => true/false]` |
| `required` | Make field required | `['field' => true/false]` |
| `readonly` | Make field readonly | `['field' => true/false]` |
| `update` | Update field value | `['field' => 'value']` or closure |

### Formula Functions

**Comparison:**
- `Formula::equals('$field', 'value')` — single value check
- `Formula::notEquals('$field', 'value')` — negation
- `Formula::inArray('$field', ['val1', 'val2'])` — multiple values (field FIRST, array SECOND)
- `Formula::notInArray('$field', ['val1', 'val2'])` — exclusion
- `Formula::empty('$field')` — is empty
- `Formula::notEmpty('$field')` — is not empty
- `Formula::greaterThan('$field', 'value')` — greater than
- `Formula::lessThan('$field', 'value')` — less than

**Logic:**
- `Formula::and($a, $b, ...)` — all must be true
- `Formula::or($a, $b, ...)` — any must be true
- `Formula::not($a)` — negate

## Step 4: Apply Critical Patterns

### VISIBILITY — Always Create BOTH Show and Hide Rules

**WRONG — field may stay in wrong state:**
```php
'show_field' => [
    'trigger' => Formula::equals('$type', 'other'),
    'logic' => ['visible' => ['other_field' => true]],
],
// Missing hide rule! ❌
```

**CORRECT:**
```php
'other_field_show' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['type'],
    'trigger' => Formula::equals('$type', 'other'),
    'logic' => ['visible' => ['other_field' => true]],
],
'other_field_hide' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['type'],
    'trigger' => Formula::notEquals('$type', 'other'),
    'logic' => ['visible' => ['other_field' => false]],
],
```

Same pattern applies to `required` — create both true and false rules.

### GLOBAL READONLY — No Trigger Needed

```php
'field_always_readonly' => [
    'hooks' => [Hook::ALL],
    'logic' => [
        'readonly' => ['assigned_user_name' => true],
    ],
],
```

### UPDATE — Use Closure for Calculated Fields

```php
'calculate_annual_salary' => [
    'hooks' => [Hook::ALL, Hook::CHANGE],
    'triggerFields' => ['salary'],
    'logic' => [
        'update' => function ($bean) {
            // Store field value in local variable first
            $salary = $bean->salary;
            if (!empty($salary)) {
                return ['annual_salary' => $salary * 12];  // Always return array
            }
            return [];  // Always return array, even empty
        },
    ],
],
```

### UPDATE — Fetching from Related Record

```php
'copy_from_related' => [
    'hooks' => [Hook::INIT, Hook::CHANGE],
    'triggerFields' => ['delegation_id'],
    'logic' => [
        'update' => function ($bean) {
            $delegation_id = $bean->delegation_id;  // Store first
            if (!empty($delegation_id)) {
                // Use full namespace for BeanFactory
                $delegation = \MintHCM\Data\BeanFactory::getBean('Delegations', $delegation_id);
                if ($delegation && !empty($delegation->assigned_user_id)) {
                    return [
                        'assigned_user_id' => $delegation->assigned_user_id,
                        'assigned_user_name' => $delegation->assigned_user_name,
                    ];
                }
            }
            return [];
        },
    ],
],
```

## Critical Errors to Avoid

| Error | Wrong | Correct |
|-------|-------|---------|
| inArray argument order | `Formula::inArray('value', '$field')` | `Formula::inArray('$field', ['value'])` |
| Missing hide rule | Only show rule | Both show AND hide |
| BeanFactory namespace | `\BeanFactory::getBean(...)` | `\MintHCM\Data\BeanFactory::getBean(...)` |
| Update return type | `return $value;` | `return ['field' => $value];` |
| Missing else return | no return in else | `return [];` |
| Hook pattern | `Hook::INIT, Hook::CHANGE` | `Hook::ALL, Hook::CHANGE` |

## Step 5: Remind About Quick Repair

After writing logicdefs.php, remind the user:
> Run **Admin → Repair → Quick Repair and Rebuild** to apply the new logic rules.

## Complete Example: Conditional Fields

Module `Transportations` — show `other_transportation` only when `type` = `'other'`:

```php
<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'rules' => [
        'other_transportation_show' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'other'),
            'logic' => [
                'visible' => ['other_transportation' => true],
                'required' => ['other_transportation' => true],
            ],
        ],
        'other_transportation_hide' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::notEquals('$type', 'other'),
            'logic' => [
                'visible' => ['other_transportation' => false],
                'required' => ['other_transportation' => false],
            ],
        ],
    ],
];
```
