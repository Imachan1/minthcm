# Data Model Customization

> **Vardefs syntax, field types, relationships** → see [vardefs-reference.md](../shared/vardefs-reference.md)

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

> For the full triad pattern (id + relate + link + relationship definition) and many-to-many syntax, see [vardefs-reference.md](../shared/vardefs-reference.md#relationships--the-triad-pattern).

**Project-specific note**: relationship files go in `legacy/custom/modules/{Module}/Ext/Vardefs/{Module}.{ProjectName}.php` — same file as custom fields for that module.

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

> See also [vardefs-reference.md](../shared/vardefs-reference.md#common-mistakes) for the full list.

Project-specific pitfall: **wrong Extension file name** — must be `{Module}.{ProjectName}.php` (e.g., `Employees.ConVista.php`), not just `custom_field.php`.
