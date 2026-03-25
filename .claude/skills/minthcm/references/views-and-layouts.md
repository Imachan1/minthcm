# Views and Layouts

## recordviewdefs.php

Defines Vue record view layout (detail + edit). Location:
- Existing modules: `legacy/modules/{Module}/metadata/recordviewdefs.php`
- New/custom modules: `legacy/custom/modules/{Module}/metadata/recordviewdefs.php`

### Basic structure

```php
<?php
$viewdefs['{Module}'] = [
    'order' => ['basicInfo', 'subpanels'],     // Panel display order
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_BASIC_INFO',       // Optional panel title
            'data' => [
                'actions' => [                  // BeanActions (optional)
                    'Audit',
                    'Delete',
                    [
                        'name' => 'Duplicate',
                        'skipFields' => ['id', 'date_entered'],
                    ],
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['first_name', 'last_name'],     // 2 columns
                            ['email'],                        // Full width
                            ['phone', ''],                    // Left only
                            ['', 'status'],                   // Right only
                        ],
                    ],
                    'details' => [
                        'title' => 'LBL_DETAILS',
                        'fields' => [
                            ['category', 'priority'],
                            ['description'],
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_RELATED',
        ],
    ],
];
```

### Field layout rules

```php
'fields' => [
    ['field1', 'field2'],          // Row: 2 columns (50% each)
    ['field3'],                     // Row: 1 column (100% width)
    ['field4', ''],                 // Row: left column only
    ['', 'field5'],                 // Row: right column only
    ['f1', 'f2', 'f3'],           // Row: 3 columns (33% each)
]
```

### Field with custom type override

```php
'fields' => [
    [
        ['name' => 'email1', 'type' => 'email'],    // Override type
        'phone_mobile',
    ],
    [
        ['name' => 'birthdate', 'type' => 'age'],   // Show as age
        'status',
    ],
    [
        [
            'name' => 'primary_address',
            'type' => 'fieldset',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'properties' => [
                'fields' => [
                    'primary_address_street',
                    'primary_address_city',
                    'primary_address_state',
                    'primary_address_postalcode',
                    'primary_address_country',
                ],
                'separator' => ', ',
            ],
        ],
        '',
    ],
]
```

### Available panel components

| Component | Purpose |
|-----------|---------|
| `MintPanelRecordDetails` | Form fields with sections |
| `MintPanelSubpanels` | Related records subpanels |
| `MintPanelFiles` | File attachments |
| `MintPanelScheduler` | Calendar/scheduler |
| `MintPanelChecklist` | Checklist items |
| `MintPanelPositionCard` | Position card (HR-specific) |
| `MintPanelRecordPanel` | Generic custom panel |

### BeanActions configuration

Actions appear in the record view toolbar menu:

```php
'actions' => [
    'Audit',                           // Simple action
    'Delete',
    [
        'name' => 'Duplicate',         // Action with config
        'skipFields' => ['id'],
    ],
    'GenerateReport',               // Custom action class
],
```

## eslistviewdefs.php

Defines ElasticSearch-based list view columns. Location: `legacy/modules/{Module}/metadata/eslistviewdefs.php`

```php
<?php
$module_name = 'con_BankAccounts';
$ESListViewDefs['con_BankAccounts'] = [
    'columns' => [
        'name' => [
            'link' => true,             // Clickable link to record
            'default' => true,          // Visible by default
        ],
        'employee_name' => [
            'link' => true,
            'default' => true,
        ],
        'account_number' => [
            'default' => true,
        ],
        'bank_name' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'date_entered' => [
            // No 'default' => visible only when user adds it
        ],
        'email1' => [
            'link' => true,
            'default' => true,
            'sortable' => false,        // Disable sorting
        ],
    ],
];
```

Column options:
- `'link' => true` -- renders as clickable link to the record
- `'default' => true` -- shown by default (user can hide)
- `'sortable' => false` -- disable column sorting
- No options needed for basic columns -- just `'field_name' => []`

## listviewdefs.php

Legacy list view definition (used when ES is not available):

```php
<?php
$listViewDefs['con_BankAccounts'] = [
    'NAME' => [
        'width' => '30%',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
    ],
    'EMPLOYEE_NAME' => [
        'width' => '20%',
        'label' => 'LBL_EMPLOYEE',
        'link' => true,
        'default' => true,
    ],
    'STATUS' => [
        'width' => '15%',
        'label' => 'LBL_STATUS',
        'default' => true,
    ],
];
```

**Note**: Field names are UPPERCASE in listviewdefs.php.

## subpaneldefs.php

Defines subpanels (related records) shown on the record view. Location: `legacy/modules/{Module}/metadata/subpaneldefs.php`

```php
<?php
$layout_defs['con_BankAccounts']['subpanel_setup'] = [
    'employees' => [
        'order' => 100,
        'module' => 'Employees',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'name',
        'title_key' => 'LBL_EMPLOYEES_TITLE',
        'get_subpanel_data' => 'employees',     // Link name from vardefs
        'top_buttons' => [
            ['widget_class' => 'SubPanelTopButtonQuickCreate'],
            ['widget_class' => 'SubPanelTopSelectButton'],
        ],
    ],
    'notes' => [
        'order' => 200,
        'module' => 'Notes',
        'subpanel_name' => 'default',
        'sort_order' => 'desc',
        'sort_by' => 'date_entered',
        'title_key' => 'LBL_NOTES_TITLE',
        'get_subpanel_data' => 'notes',
        'top_buttons' => [
            ['widget_class' => 'SubPanelTopButtonQuickCreate'],
        ],
    ],
];
```

### Subpanel inline actions

Defined in the subpanel's `_buttons` or via widget_class in subpanel definition:

| Widget Class | Action | What It Does |
|-------------|--------|--------------|
| `SubPanelEditButton` | Edit | Redirects to related record's EditView |
| `SubPanelDeleteButton` | Delete | Deletes the related record |
| `SubPanelRemoveButton` | Remove | Unlinks (removes relationship only) |

### Top buttons

| Widget Class | What It Does |
|-------------|--------------|
| `SubPanelTopButtonQuickCreate` | Quick-create a new related record |
| `SubPanelTopSelectButton` | Select existing record to relate |

## Removing Module from Legacy Views

To enable Vue views for a module that currently uses legacy views, override via constants:

```php
// api/custom/constants/legacy_views/con_enable_vue.php
<?php
// Return array without the module to remove it from legacy views
// Or return empty array to keep all modules in Vue
return [];
```

Alternatively, if you need to add a module to legacy views:

```php
<?php
return [
    'con_LegacyModule' => true,
];
```

## Complete New Module Example

```php
<?php
// legacy/modules/con_BankAccounts/metadata/recordviewdefs.php
$viewdefs['con_BankAccounts'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => ['Audit', 'Delete'],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name', 'employee_name'],
                            ['account_number', 'bank_name'],
                            ['iban', 'swift'],
                            ['status', 'currency_id'],
                            ['description'],
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
```

## Common Mistakes

1. **Forgetting to add panel key to `order` array** -- panel exists but won't display.
2. **Wrong field names** -- must exactly match vardefs field names (case-sensitive).
3. **Putting logic in recordviewdefs** -- use MintLogic for visibility/required/readonly, not view defs.
4. **UPPERCASE field names in eslistviewdefs** -- eslistviewdefs uses lowercase; only legacy listviewdefs uses uppercase.
5. **Missing `get_subpanel_data` in subpaneldefs** -- must match the link field name from vardefs.
