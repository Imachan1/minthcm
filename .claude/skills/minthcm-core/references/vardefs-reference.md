# Vardefs Reference

Complete guide to building `vardefs.php` files in MintHCM/SuiteCRM.

## Structure

Every module's schema lives in `$dictionary` array:

```php
$dictionary['ModuleName'] = [
    'table' => 'table_name',
    'audited' => true,
    'activity_enabled' => false,
    'duplicate_merge' => true,
    'optimistic_locking' => true,
    'unified_search' => true,
    'fields' => [ ... ],
    'relationships' => [ ... ],
    'indices' => [ ... ],
];
```

**Location**: `legacy/modules/{Module}/vardefs.php` (core) or `legacy/custom/modules/{Module}/Ext/Vardefs/` (customization).

## Field Definition Anatomy

```php
'field_name' => [
    'name' => 'field_name',           // Must match the array key
    'type' => 'varchar',              // Field type (see below)
    'vname' => 'LBL_FIELD_NAME',     // Language label key (module strings)
    'label' => 'LBL_FIELD_NAME',     // Alternative to vname
    'len' => 255,                     // Max length (string types)
    'required' => true,               // Validation: required field
    'default' => 'value',             // Default value
    'audited' => true,                // Track changes in audit log
    'reportable' => true,             // Available in reports
    'massupdate' => false,            // Allow bulk update
    'importable' => 'true',          // Allow import ('true'/'false'/'required')
    'exportable' => true,             // Allow export
    'duplicate_merge' => 'enabled',   // Include in merge duplicates view
    'comment' => 'Business description of the field',  // ALWAYS set — plain text describing what this field is and what it controls
    'help' => 'LBL_HELP',            // Tooltip help text
    'readonly' => false,              // Prevent editing
    'source' => 'non-db',            // 'non-db' = not stored in DB (for relate/link)
    'dbType' => 'varchar',           // Override database column type
    'disable_num_format' => false,    // For int: TRUE if field is a sequence number or ID
],
```

## Field Attribute Standards

Default values and rules for every field attribute. Follow these unless the analyst specifies otherwise.

| Attribute | Standard value | Rules |
|-----------|---------------|-------|
| `comment` | *(always set)* | **Always provide** a plain-text business description of what the field is and what it controls. Not a label key — literal text |
| `audited` | **`true`** | Set `false` for: auto-calculated fields (date_modified, modified_by), technical/hidden fields |
| `importable` | `'true'` | `'false'` for auto-generated or technical fields; **`'required'`** when `required => true` |
| `exportable` | `true` | `false` only for technical/hidden fields |
| `required` | `false` | Consult analyst. Exceptions: `name` field (always required), enum without empty option |
| `default` | `''` | For **status enums**: default = first status (e.g., `'New'`, `'Draft'`), dropdown list should NOT have empty option. For **multienum**: no default, list should NOT have empty option. For **regular enums**: list SHOULD have empty `'' => ''` option |
| `reportable` | `true` | `false` for technical/hidden fields and non-db computed fields without a DB column |
| `readonly` | `false` | — |
| `massupdate` | `false` | Set `true` for: `assigned_user_id`, team/group fields, enum/multienum/date/datetime fields **without** special logic |
| `len` | varies by type | varchar: `255`, float/decimal: `18`, currency: `26`. Reduce for dropdowns and numeric fields, especially in modules with many fields (MySQL row size limit) |
| `precision` | `8` | Only for float/decimal/currency. Currency default: **`6`** |
| `duplicate_merge` | **`'enabled'`** | Allows conscious value selection during record merge |
| `disable_num_format` | `false` | For `int` type only: set `true` if field represents a sequence number or external ID |
| `help` | `''` | Consult analyst for tooltip text |

## Field Types

### String types

```php
// Short text
'name' => 'first_name',
'type' => 'varchar',
'len' => 255,

// Module name field (special: used as record title)
'name' => 'name',
'type' => 'name',
'len' => 255,

// Long text (textarea)
'name' => 'description',
'type' => 'text',
```

### Numeric types

```php
// Integer
'name' => 'count',
'type' => 'int',
'len' => 11,

// Integer as sequence number (no thousand separators)
'name' => 'order_number',
'type' => 'int',
'len' => 11,
'disable_num_format' => true,

// Decimal with precision (default len=18, precision=8)
'name' => 'rate',
'type' => 'decimal',
'len' => '18,8',

// Float (default len=18, precision=8)
'name' => 'score',
'type' => 'float',
'len' => '18,8',

// Currency (default len=26, precision=6; always paired with currency_id)
'name' => 'salary',
'type' => 'currency',
'len' => '26,6',
'default' => '0',
'related_fields' => ['currency_id'],
```

### Boolean

```php
'name' => 'is_active',
'type' => 'bool',
'default' => '0',
```

### Date / Time

```php
'name' => 'hire_date',
'type' => 'date',

'name' => 'created_at',
'type' => 'datetime',
```

### Enum (dropdown)

```php
// Single select — regular dropdown (with empty option in list)
'name' => 'category',
'type' => 'enum',
'options' => 'category_list',       // Key in $app_list_strings
'audited' => true,

// Single select — status field (NO empty option, has default)
'name' => 'status',
'type' => 'enum',
'options' => 'status_list',
'default' => 'New',                 // First status in workflow
'audited' => true,

// Multi select (NO empty option in list, no default)
'name' => 'categories',
'type' => 'multienum',
'options' => 'categories_list',

// Colored enum (MintHCM extension — renders colored badges)
'name' => 'status',
'type' => 'ColoredEnum',
'options' => 'status_list',
'default' => 'New',
'options_colors' => [
    'New' => 'blue',
    'Active' => 'green',
    'Closed' => 'red',
],
```

**Dropdown list rules:**
- Regular enum → include empty option: `'' => ''` as first entry
- Status enum → NO empty option, set `'default'` to initial status (`'New'`, `'Draft'`, etc.)
- Multienum → NO empty option, no default value

### Contact info

```php
'type' => 'email'    // Email field
'type' => 'phone'    // Phone number
'type' => 'url'      // URL/website
```

### File / Image

```php
'name' => 'attachment',
'type' => 'file',
'dbType' => 'varchar',
'len' => 255,

'name' => 'photo',
'type' => 'image',
'dbType' => 'varchar',
'len' => 255,
```

### ID field

```php
// Foreign key field — stores the related record ID
'name' => 'employee_id',
'type' => 'id',
'reportable' => false,
```

### Parent (polymorphic relationship)

```php
'name' => 'parent_name',
'type' => 'parent',
'type_name' => 'parent_type',
'id_name' => 'parent_id',
'source' => 'non-db',
'options' => 'parent_type_display_list',
'group' => 'parent_name',

'name' => 'parent_type',
'type' => 'parent_type',
'dbType' => 'varchar',
'len' => 255,

'name' => 'parent_id',
'type' => 'id',
'reportable' => false,
```

## Relationships — The Triad Pattern

A relationship between two modules requires **three fields on the "many" side** plus a relationship definition:

### 1. ID field (stored in DB)

```php
'employee_id' => [
    'name' => 'employee_id',
    'type' => 'id',
    'reportable' => false,
],
```

### 2. Relate field (display name, non-db)

```php
'employee_name' => [
    'name' => 'employee_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_EMPLOYEE',
    'module' => 'Employees',
    'id_name' => 'employee_id',     // Points to the ID field
    'rname' => 'name',              // Remote column to display
    'link' => 'employees',          // Points to the link field
    'table' => 'users',             // Remote table (Employees uses 'users')
],
```

### 3. Link field (relationship bridge, non-db)

```php
'employees' => [
    'name' => 'employees',
    'type' => 'link',
    'relationship' => 'employee_bankaccounts',
    'source' => 'non-db',
    'vname' => 'LBL_EMPLOYEES',
    'side' => 'right',              // 'right' = this is the many side
],
```

### 4. Relationship definition

```php
$dictionary['ModuleName']['relationships']['employee_bankaccounts'] = [
    'lhs_module' => 'Employees',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'BankAccounts',
    'rhs_table' => 'bankaccounts',
    'rhs_key' => 'employee_id',
    'relationship_type' => 'one-to-many',
];
```

### On the "one" side (Employees)

Only a link field is needed:

```php
'bankaccounts' => [
    'name' => 'bankaccounts',
    'type' => 'link',
    'relationship' => 'employee_bankaccounts',
    'source' => 'non-db',
    'vname' => 'LBL_BANKACCOUNTS',
    'side' => 'left',               // 'left' = this is the one side
],
```

### Many-to-Many

Uses a join table:

```php
$dictionary['ModuleName']['relationships']['employees_trainings'] = [
    'lhs_module' => 'Employees',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'Trainings',
    'rhs_table' => 'trainings',
    'rhs_key' => 'id',
    'relationship_type' => 'many-to-many',
    'join_table' => 'employees_trainings_c',
    'join_key_lhs' => 'employee_id',
    'join_key_rhs' => 'training_id',
];
```

Both sides get only a link field (no id/relate fields needed).

## Database Indices

When adding a foreign key field, always create a composite index for query performance:

```php
$dictionary['ModuleName']['indices']['idx_modulename_fk_deleted'] = [
    'name' => 'idx_modulename_fk_deleted',
    'type' => 'index',
    'fields' => ['deleted', 'foreign_key_id'],
];
```

Example:

```php
$dictionary['Contact']['indices']['idx_contact_cbd_id_deleted'] = [
    'name' => 'idx_contact_cbd_id_deleted',
    'type' => 'index',
    'fields' => ['deleted', 'cbd_id'],
];
```

## VardefManager Templates

Most modules end with a `VardefManager::createVardef()` call that merges in standard field sets:

```php
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('ModuleName', 'ModuleName',
    ['basic', 'assignable', 'security_groups', 'employee_related']
);
```

| Template | What it adds |
|----------|-------------|
| `basic` | `id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted` |
| `assignable` | `assigned_user_id`, `assigned_user_name`, `assigned_user_link` |
| `security_groups` | Security group relationship fields |
| `employee_related` | `employee_id`, `employee_name` relate/link to Employees |

## Vardefs → Doctrine Entity Mapping

**Quick Repair and Rebuild** (Admin > Repair) auto-generates `api/app/Entities/{Module}.php` from vardefs.

| Vardefs type | Doctrine type | PHP type |
|-------------|---------------|----------|
| varchar/char/name | string | string |
| text | text | string |
| int | integer | int |
| bool | boolean | bool |
| date | date | \DateTime |
| datetime | datetime | \DateTime |
| decimal/currency | decimal | string |
| enum/multienum/ColoredEnum | string | string |
| relate | string | string (stores ID) |
| id | string | string |
| link | — (non-db, skipped) | — |

## ElasticSearch Integration

```php
// Full-text search primary field
$dictionary['ModuleName']['full_text_search_meta_field'] = 'name';

// Search boost factor
$dictionary['ModuleName']['search_boost'] = 1.5;

// Nested fields for security groups
$dictionary['ModuleName']['elasticsearch']['nested']['security_groups'] = [
    'link' => 'SecurityGroups',
    'fields' => ['id', 'name'],
];
```

## Conditional Dependencies (Legacy)

```php
'field_name' => [
    // Show field only when condition is met
    'dependency' => "or(equal(\$status,'Active'),equal(\$status,'Inactive'))",

    // Validate as required when condition is met
    'vt_dependency' => "equals(\$status, 'Rejected')",
    'vt_required' => "equals(\$status, 'Rejected')",
],
```

> **Note**: For new implementations, prefer MintLogic over legacy dependencies. See mintlogic reference.

## Common Mistakes

1. **Missing `'source' => 'non-db'`** on relate and link fields — causes DB column creation errors.
2. **Missing id field** for relate — the `id_name` referenced in relate must exist as a separate `'type' => 'id'` field.
3. **Wrong table name** — Employees uses `users` table, not `employees`. Always check the target module's `$dictionary` for `table`.
4. **Editing auto-generated Doctrine entities** — code inside `// Auto-generated Section...` markers is overwritten on Quick Repair.
5. **Not running Quick Repair** after vardefs changes — fields won't exist in DB or API entities.
6. **Hardcoded strings** — use `vname`/`label` pointing to language keys, never literal text.
7. **Changing a standard field's type** — never change the type of an existing standard field. Create a new field with the correct type and hide the original. Changing types causes upgrade failures and package installation issues.
8. **Missing index on foreign key** — every new foreign key field needs a composite index on `[deleted, fk_field]` for query performance.
9. **Enum without empty option when not a status** — regular dropdowns should have `'' => ''` as first option. Only status fields omit the empty option.
10. **`importable` not matching `required`** — when a field is `required => true`, set `importable => 'required'` (not just `'true'`).
