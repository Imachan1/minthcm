---
applyTo:
    - "legacy/**/metadata/**"
    - "api/constants/legacy_views.php"
    - "api/lib/MintLogic/Modules/**/logicdefs.php"
---

# Legacy to New View Migration Guide

**Version**: 1.0  
**Last Updated**: 2026-01-13

This guide explains how to migrate legacy modules from old views (detailviewdefs.php, editviewdefs.php) to new Vue-based views (recordviewdefs.php).

## Migration Overview

**Legacy View System**:
- DetailView/EditView defined in `legacy/modules/{Module}/metadata/detailviewdefs.php` and `editviewdefs.php`
- View Tools (vt_dependency, vt_calculated, etc.) in vardefs.php
- Uses legacy SuiteCRM rendering

**New View System**:
- Single recordviewdefs.php defines all views
- MintLogic system replaces View Tools
- Vue components render dynamically
- Backend drives all form behavior

## Migration Checklist

- [ ] Review existing legacy view definitions (detailviewdefs.php, editviewdefs.php)
- [ ] Identify all View Tools rules in vardefs.php (vt_*)
- [ ] Create recordviewdefs.php in `legacy/modules/{Module}/metadata/`
- [ ] Create logicdefs.php in `api/lib/MintLogic/Modules/{Module}/`
- [ ] Remove module from `api/constants/legacy_views.php`
- [ ] Test module in new view
- [ ] Run Quick Repair and Rebuild

## Step-by-Step Migration Process

### Step 1: Analyze Legacy Views

Read existing view definitions to understand field layout:

```php
// legacy/modules/Transportations/metadata/detailviewdefs.php
$viewdefs['Transportations']['DetailView'] = [
    'panels' => [
        'default' => [
            ['from_city', 'to_city'],
            ['type', 'other_transportation'],
            ['trans_date', 'delegation_name'],
            ['description'],
        ],
    ],
];
```

### Step 2: Identify View Tools in Vardefs

Look for fields starting with `vt_` in vardefs:

```php
// legacy/modules/Transportations/vardefs.php
'other_transportation' => [
    'name' => 'other_transportation',
    'type' => 'varchar',
    'vt_dependency' => "inArray('other', $type)",  // View Tool
],
'assigned_user_id' => [
    'name' => 'assigned_user_id',
    'vt_calculated' => 'related(@assigned_user_id, #delegations)',  // View Tool
],
```

**Common View Tools**:
- `vt_dependency` - Field visibility based on condition
- `vt_calculated` - Auto-calculate field value
- `vt_required` - Dynamic required status
- `vt_readonly` - Dynamic readonly status

### Step 3: Create recordviewdefs.php

Create new view definition file:

**Location**: `legacy/modules/{Module}/metadata/recordviewdefs.php`

```php
<?php

$viewdefs['Transportations'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['from_city', 'to_city'],
                            ['type', 'other_transportation'],
                            ['trans_date', 'delegation_name'],
                            ['description'],
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_SUBPANELS',
        ],
    ],
];
```

**Available Components**:
- `MintPanelRecordDetails` - Standard form fields panel
- `MintPanelSubpanels` - Related records panel
- `MintPanelFiles` - File attachments panel
- `MintPanelScheduler` - Scheduler/calendar panel
- `MintPanelChecklist` - Checklist panel
- `MintPanelPositionCard` - Position card panel
- `MintPanelRecordPanel` - Generic record panel

**Field Layout**:
- Each array in `fields` is a row
- Each element in row is a column
- Empty string creates empty column: `['field1', '']`
- Single field spans full width: `['description']`

### Step 4: Migrate View Tools to MintLogic

Create MintLogic rules to replace View Tools.

**Location**: `api/lib/MintLogic/Modules/{Module}/logicdefs.php`

**See [MintLogic System](12-mintlogic.instructions.md) for complete formula reference and examples.**

#### Common Migration Patterns

**vt_dependency → MintLogic Visibility** (requires BOTH show and hide rules):
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
            'logic' => ['visible' => ['other_transportation' => true]],
        ],
        'other_transportation_hide' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::notEquals('$type', 'other'),
            'logic' => ['visible' => ['other_transportation' => false]],
        ],
    ],
];
```

**vt_calculated → MintLogic Update**:
```php
'assigned_user_calculated' => [
    'hooks' => [Hook::INIT, Hook::CHANGE],
    'triggerFields' => ['delegation_id'],
    'logic' => [
        'update' => function ($bean) {
            if (!empty($bean->delegation_id)) {
                $delegation = \BeanFactory::getBean('Delegations', $bean->delegation_id);
                if ($delegation && !empty($delegation->assigned_user_id)) {
                    return ['assigned_user_id' => $delegation->assigned_user_id];
                }
            }
            return [];
        },
    ],
],
```

**See [MintLogic Instructions](12-mintlogic.instructions.md) for:**
- Complete formula reference
- More migration patterns
- Common errors and solutions
- Best practices

### Step 5: Remove from legacy_views.php

Edit `api/constants/legacy_views.php` and remove the module entry:

```php
// REMOVE THIS:
'Transportations' => [
    'list' => false,
    'record' => true,
],
```

### Step 6: Test and Verify

1. **Clear cache**: Browser cache and server cache
2. **Run Quick Repair**: Admin → Repair → Quick Repair and Rebuild
3. **Test module**: Open module in new view, verify all fields display correctly
4. **Test logic**: Change trigger fields, verify visibility/calculated fields work
5. **Test subpanels**: Verify related records display correctly

## Quick Reference

### View Tools to MintLogic Mapping

| View Tool | MintLogic | Notes |
|-----------|-----------|-------|
| `vt_dependency` | `visible` | Requires BOTH show and hide rules |
| `vt_calculated` | `update` | Use closure with BeanFactory |
| `vt_required` | `required` | Requires BOTH true and false rules |
| `vt_readonly` | `readonly` | Single rule often sufficient |

### Common Formulas

- `Formula::equals('$field', 'value')` - Single value check
- `Formula::notEquals('$field', 'value')` - Negation
- `Formula::inArray('$field', ['val1', 'val2'])` - Multiple values
- `Formula::notInArray('$field', ['val1', 'val2'])` - Exclusion

**Full formula reference**: [MintLogic System](12-mintlogic.instructions.md)

## Troubleshooting

**Module still shows legacy view**:
1. Verify module removed from `api/constants/legacy_views.php`
2. Clear browser cache (Ctrl+Shift+R)
3. Check recordviewdefs.php exists in correct location

**Fields not showing/hiding**:
1. Verify BOTH show and hide rules exist (critical for visibility!)
2. Check trigger field names match vardefs
3. See [MintLogic Common Errors](12-mintlogic.instructions.md#common-errors)

**Calculated fields not updating**:
1. Verify triggerFields includes all dependencies
2. Ensure function returns array (even empty `[]`)
3. Check BeanFactory::getBean() returns valid bean

## Best Practices

**DO**:
- ✅ Always create BOTH show and hide rules for visibility
- ✅ Use `Hook::ALL, Hook::CHANGE` as standard pattern
- ✅ Test all trigger field combinations
- ✅ Run Quick Repair after creating logicdefs.php
- ✅ Refer to [MintLogic guide](12-mintlogic.instructions.md) for formulas

**DON'T**:
- ❌ Create only show OR hide rule for visibility
- ❌ Skip testing edge cases
- ❌ Forget to remove module from legacy_views.php
- ❌ Use deprecated hooks like `Hook::INIT, Hook::CHANGE`

---

**Related**: 
- [MintLogic System](12-mintlogic.instructions.md) - Complete logic system reference
- [Legacy Integration](11-legacy-integration.instructions.md) - Working with legacy code
- [Troubleshooting](15-troubleshooting.instructions.md) - Common issues

**Version**: 1.0  
**Last Updated**: 2026-01-13  
**Maintained by**: AI Agent
