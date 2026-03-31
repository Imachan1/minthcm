# Legacy Layer (SuiteCRM) -- Core Developer Reference

## Module Structure

```
legacy/modules/{ModuleName}/
├── {ModuleName}.php          # Bean class (extends SugarBean)
├── vardefs.php               # Field definitions (the schema)
├── language/
│   └── en_us.lang.php        # English labels only (core uses English)
├── metadata/
│   ├── recordviewdefs.php    # Vue record view layout
│   ├── listviewdefs.php      # List view columns
│   ├── eslistviewdefs.php    # ElasticSearch list view columns
│   ├── detailviewdefs.php    # Legacy detail view
│   ├── editviewdefs.php      # Legacy edit view
│   └── subpaneldefs.php      # Subpanel configuration
└── logic_hooks.php           # Logic hook registrations
```

Custom overrides: `legacy/custom/modules/{Module}/` mirrors the same structure.

## Metadata Files

### recordviewdefs.php (Vue views)

```php
$viewdefs['Employees'] = [
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => ['Edit', 'Delete', 'Audit', ['name' => 'Duplicate', 'skipFields' => ['status']]],
                'sections' => [
                    'LBL_BASIC_INFO' => [
                        ['first_name', 'last_name'],
                        ['email', 'phone_work'],
                        ['department', 'position_name'],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'data' => [
                'subpanels' => ['documents', 'history', 'activities'],
            ],
        ],
    ],
];
```

Each row in `sections` is an array of field names. Two fields per row = two-column layout.

### listviewdefs.php

```php
$listViewDefs['Employees'] = [
    'NAME' => ['width' => '20%', 'label' => 'LBL_NAME', 'default' => true, 'link' => true],
    'STATUS' => ['width' => '10%', 'label' => 'LBL_STATUS', 'default' => true],
    'DEPARTMENT' => ['width' => '15%', 'label' => 'LBL_DEPARTMENT', 'default' => true],
];
```

### subpaneldefs.php

```php
$layout_defs['Employees'] = [
    'subpanel_setup' => [
        'documents' => [
            'order' => 10,
            'module' => 'Documents',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'documents',
            'title_key' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
        ],
    ],
];
```

## Beans (SugarBean)

```php
// Get existing bean
$employee = BeanFactory::getBean('Employees', $id);

// Create new bean
$employee = BeanFactory::newBean('Employees');
$employee->first_name = 'John';
$employee->last_name = 'Doe';
$employee->save();

// Delete (soft delete -- sets deleted=1)
$employee->mark_deleted($id);

// Load relationship
$employee->load_relationship('documents');
$related = $employee->documents->getBeans();

// Query via bean
$list = $employee->get_list('date_entered DESC', "employees.status = 'Active'", 0, 20);
```

## Global Variables

```php
global $current_user;        // Logged-in user (User bean)
global $db;                  // DBManager instance
global $app_strings;         // Application-level labels
global $app_list_strings;    // Dropdown option lists
global $mod_strings;         // Module-specific labels
global $beanList;            // Module → bean class mapping
global $sugar_config;        // System configuration
```

## Logic Hooks

Register in `legacy/modules/{Module}/logic_hooks.php`:

```php
$hook_array['before_save'][] = [
    1,                                    // Sort order
    'Calculate total',                    // Description
    'modules/Invoices/hooks/Calculate.php', // File
    'Calculate',                          // Class
    'beforeSave',                         // Method
];
```

Hook types: `before_save`, `after_save`, `before_delete`, `after_delete`, `before_retrieve`, `after_retrieve`, `before_restore`, `after_restore`, `process_record`.

### Hook class

```php
class Calculate
{
    public function beforeSave($bean, $event, $arguments)
    {
        $bean->total = $bean->subtotal + $bean->tax;
    }
}
```

## Language Files

```php
// legacy/modules/Employees/language/en_us.lang.php
$mod_strings = [
    'LBL_MODULE_NAME' => 'Employees',
    'LBL_FIRST_NAME' => 'First Name',
    'LBL_LAST_NAME' => 'Last Name',
    'LBL_STATUS' => 'Status',
];
```

Custom: `legacy/custom/modules/Employees/language/en_us.lang.php` -- merged with core.

Dropdown options:
```php
// legacy/custom/include/language/en_us.lang.php
$app_list_strings['employee_status_dom'] = [
    '' => '',
    'Active' => 'Active',
    'Inactive' => 'Inactive',
    'Terminated' => 'Terminated',
];
```

## ACL (Access Control)

```php
$bean->ACLAccess('edit');    // Can current user edit?
$bean->ACLAccess('delete');
$bean->ACLAccess('view');
$bean->ACLAccess('list');
$bean->ACLAccess('export');
```

## Key HCM Modules

`Employees`, `Recruitments`, `Candidatures`, `WorkSchedules`, `Positions`, `Competencies`, `Trainings`, `Evaluations`, `Benefits`, `Delegations`, `Spots`, `Skills`, `Appraisals`, `Goals`, `Contracts`, `Offboardings`, `Onboardings`.

## Customization Layer

`legacy/custom/modules/{Module}/` mirrors core structure:
- `Ext/Vardefs/` -- custom field definitions
- `metadata/` -- custom view layouts
- `language/` -- custom labels (merged with core)
- `logic_hooks.php` -- custom hooks (merged with core)

## Common Mistakes

1. **Editing core module files** -- always use `legacy/custom/modules/{Module}/`. Core files are overwritten on upgrade.
2. **Forgetting `global $current_user`** -- PHP requires explicit `global` keyword to access Sugar globals inside functions/methods.
3. **Not calling `load_relationship()` before accessing relationship** -- `$bean->documents` is null until `$bean->load_relationship('documents')` is called.
4. **Hardcoding strings instead of using `$mod_strings`** -- breaks translations and client customization.
5. **Missing `Quick Repair` after metadata changes** -- vardefs, viewdefs, and relationship changes require Quick Repair and Rebuild to take effect.
