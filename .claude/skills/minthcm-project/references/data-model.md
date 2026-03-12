# Data Model Customization

## Adding Fields to Existing Modules

Create `legacy/custom/modules/{Module}/Ext/Vardefs/{Module}.{ProjectName}.php`:

```php
<?php
$dictionary['{Module}']['fields']['custom_field_name'] = [
    'name' => 'custom_field_name',
    'vname' => 'LBL_CUSTOM_FIELD',
    'type' => 'varchar',
    'len' => 100,
    'comment' => 'Description of the field',
    'audited' => true,
];
```

After adding: Admin -> Repair -> Quick Repair and Rebuild. This creates the DB column and regenerates Doctrine entities.

## Vardefs Field Types Reference

```php
// String types
'type' => 'varchar', 'len' => 255          // Short text
'type' => 'text'                             // Long text (no len needed)
'type' => 'name', 'len' => 255              // Module name field

// Numeric types
'type' => 'int', 'len' => 11                // Integer
'type' => 'decimal', 'len' => '26,6'        // Decimal with precision
'type' => 'float'                            // Floating point
'type' => 'currency'                         // Currency (paired with currency_id)

// Boolean
'type' => 'bool', 'default' => '0'          // Checkbox (0/1)

// Date/time
'type' => 'date'                             // Date only
'type' => 'datetime'                         // Date and time
'type' => 'datetimecombo'                    // Combined datetime

// Enum (dropdown)
'type' => 'enum', 'options' => 'dropdown_name_dom'         // Single select
'type' => 'multienum', 'options' => 'dropdown_name_dom'    // Multi select

// Relate (relationship fields)
'type' => 'relate',
'module' => 'Accounts',
'id_name' => 'account_id',
'rname' => 'name',
'source' => 'non-db',
'link' => 'accounts',

// Link (relationship link, non-db)
'type' => 'link',
'relationship' => 'module_accounts',
'source' => 'non-db',
'vname' => 'LBL_ACCOUNTS',

// Contact info
'type' => 'email'                            // Email
'type' => 'phone'                            // Phone
'type' => 'url'                              // URL/website

// File
'type' => 'file', 'dbType' => 'varchar', 'len' => 255   // File upload
'type' => 'image', 'dbType' => 'varchar', 'len' => 255  // Image upload
```

## Creating Dropdown Lists

Create `legacy/custom/Extension/application/Ext/Language/{lang}.{ProjectName}.php`:

```php
<?php
// English
// File: en_us.ConVista.php
$app_list_strings['con_status_dom'] = [
    '' => '',
    'new' => 'New',
    'in_progress' => 'In Progress',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
];

// Polish
// File: pl_PL.ConVista.php
$app_list_strings['con_status_dom'] = [
    '' => '',
    'new' => 'Nowy',
    'in_progress' => 'W trakcie',
    'completed' => 'Zakończony',
    'cancelled' => 'Anulowany',
];
```

Then use in vardefs: `'options' => 'con_status_dom'`

## Adding Relationships

### One-to-Many (e.g., Account has many Contacts)

In `legacy/custom/modules/{Module}/Ext/Vardefs/custom_relationships.php`:

```php
<?php
// ID field (stored in DB)
$dictionary['con_BankAccounts']['fields']['employee_id'] = [
    'name' => 'employee_id',
    'type' => 'id',
    'reportable' => false,
];

// Relate field (display field, non-db)
$dictionary['con_BankAccounts']['fields']['employee_name'] = [
    'name' => 'employee_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_EMPLOYEE',
    'module' => 'Employees',
    'id_name' => 'employee_id',
    'rname' => 'name',
    'link' => 'employees',
];

// Link field
$dictionary['con_BankAccounts']['fields']['employees'] = [
    'name' => 'employees',
    'type' => 'link',
    'relationship' => 'employee_con_bankaccounts',
    'source' => 'non-db',
    'vname' => 'LBL_EMPLOYEES',
    'side' => 'right',
];

// Relationship definition
$dictionary['con_BankAccounts']['relationships']['employee_con_bankaccounts'] = [
    'lhs_module' => 'Employees',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'con_BankAccounts',
    'rhs_table' => 'con_bankaccounts',
    'rhs_key' => 'employee_id',
    'relationship_type' => 'one-to-many',
];
```

### Many-to-Many

Uses a join table. Define `'relationship_type' => 'many-to-many'` with `'join_table'`, `'join_key_lhs'`, `'join_key_rhs'`.

## Adding Language Labels

Create `legacy/custom/Extension/modules/{Module}/Ext/Language/{lang}.{Module}.{ProjectName}.php`:

> **File naming rule**: Language Extension files **must** include the language code as the first segment, e.g.:
> - `en_us.Employees.ConVista.php`
> - `pl_PL.Employees.ConVista.php`
>
> A file named `Employees.ConVista.php` (without language prefix) is **incorrect** and will not be loaded.

```php
<?php
// File: en_us.Employees.ConVista.php
$mod_strings['LBL_CUSTOM_FIELD'] = 'Custom Field';
$mod_strings['LBL_PANEL_CUSTOM'] = 'Custom Information';
```

## Creating New Modules

New modules use MODULE_PREFIX from `minthcm-project.yaml` (e.g., `con_BankAccounts`).

> **IMPORTANT — module location rule**:
> - **New project modules** that do not exist anywhere in the codebase go directly in `legacy/modules/{con_BankAccounts}/` — NOT in `legacy/custom/modules/`.
> - `legacy/custom/modules/` is reserved for **overriding/extending existing core modules** (e.g., adding a logic hook to `Employees`).
> - The module is registered via the Extension Framework (`legacy/custom/Extension/application/Ext/Include/…`), but the module directory itself lives in `legacy/modules/`.

Required files for a new module:

```
legacy/modules/{con_BankAccounts}/
  vardefs.php                    # Field definitions
  {con_BankAccounts}.php         # Module bean class (extends SugarBean)
  metadata/
    recordviewdefs.php           # Vue record view layout
    eslistviewdefs.php           # ES list view columns
    subpaneldefs.php             # Subpanel config
  language/
    en_us.lang.php               # English labels
    pl_PL.lang.php               # Polish labels
```

Bean class:

```php
<?php
// legacy/modules/con_BankAccounts/con_BankAccounts.php
class con_BankAccounts extends SugarBean
{
    public $module_dir = 'con_BankAccounts';
    public $object_name = 'con_BankAccounts';
    public $table_name = 'con_bankaccounts';
    public $module_name = 'con_BankAccounts';
}
```

Register module in `legacy/custom/Extension/application/Ext/Include/{ProjectName}.php`:

```php
<?php
$beanList['con_BankAccounts'] = 'con_BankAccounts';
$beanFiles['con_BankAccounts'] = 'modules/con_BankAccounts/con_BankAccounts.php';
$moduleList[] = 'con_BankAccounts';
```

Add module icon via constants:

```php
// api/custom/constants/module_icons/project_modules.php
<?php
return [
    'con_BankAccounts' => 'account_balance',
];
```

## Doctrine Entity Auto-Generation

Doctrine entities are auto-generated from vardefs during Quick Repair. **Never edit entity files directly.** If you need custom ORM-mapped properties, extend in `api/custom/app/Entities/`.

## Common Mistakes

1. **Forgetting `'source' => 'non-db'`** on relate and link fields -- causes DB errors.
2. **Missing id field** for relate fields -- the `id_name` field must exist as a separate `'type' => 'id'` field.
3. **Wrong table name** in relationships -- Sugar tables are lowercase module names (e.g., `users` for Employees, module-specific tables for custom modules).
4. **Not running Quick Repair** after vardefs changes -- fields won't exist in DB or API.
5. **Editing auto-generated Doctrine entities** -- changes are overwritten on next Quick Repair.
