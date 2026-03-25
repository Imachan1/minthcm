# Business Logic

## Logic Hooks

Register hooks in `legacy/custom/modules/{Module}/logic_hooks.php`:

```php
<?php
$hook_array = [];
$hook_array['before_save'][] = [
    1,                                          // Sort order
    'Calculate total',                          // Description
    'custom/modules/{Module}/CalculateTotal.php', // File path
    'CalculateTotal',                        // Class name
    'run',                                      // Method name
];
$hook_array['after_save'][] = [
    1,
    'Send notification',
    'custom/modules/{Module}/SendNotification.php',
    'SendNotification',
    'run',
];
```

### Hook class implementation

```php
<?php
// legacy/custom/modules/{Module}/CalculateTotal.php
class CalculateTotal
{
    public function run($bean, $event, $arguments)
    {
        // $bean - current record (SugarBean)
        // $event - hook name (e.g., 'before_save')
        // $arguments - additional data

        if ($bean->fetched_row['status'] !== $bean->status) {
            // Status changed - do something
        }

        $bean->total = $bean->quantity * $bean->unit_price;
        // No need to call $bean->save() in before_save -- Sugar does it
    }
}
```

### Available hook points

| Hook | When | Notes |
|------|------|-------|
| `before_save` | Before record is saved to DB | Can modify $bean fields, no need to call save() |
| `after_save` | After record is saved | $bean has ID, safe to create related records |
| `before_delete` | Before soft-delete | Can prevent deletion by throwing exception |
| `after_delete` | After soft-delete | Clean up related data |
| `process_record` | When record is loaded for display | Read-only context |
| `after_retrieve` | After record is fetched from DB | Modify loaded data |
| `before_relationship_add` | Before relationship link created | |
| `after_relationship_add` | After relationship link created | |
| `before_relationship_delete` | Before relationship link removed | |
| `after_relationship_delete` | After relationship link removed | |

### Using BeanFactory in hooks

```php
public function run($bean, $event, $arguments)
{
    // Load related record
    $employee = BeanFactory::getBean('Employees', $bean->employee_id);

    // Create new record
    $note = BeanFactory::newBean('Notes');
    $note->name = 'Auto-generated note';
    $note->parent_type = $bean->module_name;
    $note->parent_id = $bean->id;
    $note->save();
}
```

## MintLogic (Dynamic Form Logic)

Define form behavior in `api/custom/lib/MintLogic/{ModuleName}/*logicdefs.php`:

```php
<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'rules' => [
        // Conditional visibility
        'show_rejection_reason' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::equals('$status', 'rejected'),
            'logic' => [
                'visible' => ['rejection_reason' => true],
            ],
        ],
        'hide_rejection_reason' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::notEquals('$status', 'rejected'),
            'logic' => [
                'visible' => ['rejection_reason' => false],
            ],
        ],

        // Conditional required
        'require_end_date' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::equals('$status', 'completed'),
            'logic' => [
                'required' => ['end_date' => true],
            ],
        ],

        // Read-only on init
        'readonly_fields' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'readonly' => [
                    'total_amount' => true,
                    'created_by_name' => true,
                ],
            ],
        ],

        // Auto-update field value
        'copy_from_related' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['employee_name'],
            'logic' => [
                'update' => function ($bean) {
                    if (!empty($bean->employee_id)) {
                        $emp = \BeanFactory::getBean('Employees', $bean->employee_id);
                        return [
                            'department_name' => $emp->department_name ?? '',
                        ];
                    }
                    return [];
                },
            ],
        ],
    ],
];
```

**IMPORTANT**: Visibility rules require BOTH show and hide rules (trigger true + trigger false).

### Formula helpers

```php
Formula::equals('$field', 'value')              // field == value
Formula::notEquals('$field', 'value')            // field != value
Formula::inArray('$field', ['a', 'b'])           // field in [a, b]
Formula::notInArray('$field', ['a', 'b'])        // field not in [a, b]
Formula::empty('$field')                         // field is empty
Formula::notEmpty('$field')                      // field is not empty
Formula::and(condition1, condition2)             // both true
Formula::or(condition1, condition2)              // either true
Formula::not(condition)                          // negation
Formula::validate(condition, 'ERR_LABEL')        // validation with error
```

### Field references in formulas

- `$field_name` -- current value
- `$new.field_name` -- new value (after change)
- `$old.field_name` -- old value (before change)

## Bean-Level Validators

> **When to use Bean-Level Validators vs logic hooks**:
> - Use **Bean-Level Validators** for any validation that must show an error to the user on the frontend (field-level errors, form-level errors). The `ValidationException` message is propagated back to the Vue form.
> - Use **`before_save` hooks** only for silent operations: auto-filling fields, calculations, transformations. Errors set in `before_save` are NOT shown to the user in the Vue frontend.
> - Uniqueness constraints (e.g., "no two records for the same employee and month") must use Bean-Level Validators.

```php
<?php
// api/custom/lib/MintLogic/{ModuleName}/validation.logicdefs.php
use MintHCM\Lib\MintLogic\Validators\DateRangeValidator;

return [
    'bean' => [
        'validation' => [
            DateRangeValidator::class,
        ],
    ],
];
```

Validator class:

```php
<?php
// api/custom/lib/Validators/DateRangeValidator.php
namespace MintHCM\Custom\Lib\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class DateRangeValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if (!empty($bean->date_from) && !empty($bean->date_to)) {
            if ($bean->date_from > $bean->date_to) {
                throw new ValidationException('ERR_DATE_FROM_AFTER_DATE_TO');
            }
        }
    }
}
```

Example — uniqueness validator (e.g., one record per employee per month):

```php
<?php
// api/custom/lib/Validators/UniqueSalaryValidator.php
namespace MintHCM\Custom\Lib\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class UniqueSalaryValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if (empty($bean->employee_id) || empty($bean->salary_month) || empty($bean->salary_year)) {
            return;
        }

        $db = \DBManagerFactory::getInstance();
        $id = $db->quote($bean->id ?? '');
        $employeeId = $db->quote($bean->employee_id);
        $month = (int) $bean->salary_month;
        $year  = (int) $bean->salary_year;

        $sql = "SELECT id FROM wis_salaries
                WHERE employee_id = '{$employeeId}'
                  AND salary_month = {$month}
                  AND salary_year  = {$year}
                  AND deleted = 0
                  AND id != '{$id}'
                LIMIT 1";

        $result = $db->query($sql);
        if ($db->fetchByAssoc($result)) {
            throw new ValidationException('ERR_WIS_SALARY_DUPLICATE');
        }
    }
}
```

Register in `api/custom/lib/MintLogic/wis_Salaries/validation.logicdefs.php`:

```php
<?php
use MintHCM\Custom\Lib\Validators\UniqueSalaryValidator;

return [
    'bean' => [
        'validation' => [
            UniqueSalaryValidator::class,
        ],
    ],
];
```

Add the error label to the frontend language file (e.g., `vue/src/custom/lang/pl_PL.json`):

```json
{
  "ERR_WIS_SALARY_DUPLICATE": "Wypłata dla tego pracownika w wybranym miesiącu już istnieje."
}
```

## Field-Level Validators

```php
return [
    'rules' => [
        'validate_iban' => [
            'hooks' => [Hook::ALL],
            'logic' => [
                'validation' => [
                    'bank_account_iban' => IBANValidator::class,
                ],
            ],
        ],
    ],
];
```

## Schedulers

Create `legacy/custom/modules/Schedulers/DailyReport.php`:

```php
<?php
function DailyReport()
{
    // Your scheduled logic
    $bean = BeanFactory::newBean('con_Reports');
    $bean->name = 'Daily Report ' . date('Y-m-d');
    $bean->save();

    return true; // Must return true on success
}
```

Register in `legacy/custom/Extension/modules/Schedulers/Ext/ScheduledTasks/Schedulers.ConVista.php`:

```php
<?php
$job_strings[] = 'DailyReport';
```

Add label in `legacy/custom/Extension/modules/Schedulers/Ext/Language/en_us.Schedulers.ConVista.php`:

```php
<?php
$mod_strings['LBL_DAILYREPORT'] = 'Generate Daily Report';
```

Then activate in Admin -> Schedulers.

## Common Mistakes

1. **Calling `$bean->save()` in `before_save` hook** -- causes infinite loop. Just modify `$bean` fields directly.
2. **Missing both show AND hide MintLogic visibility rules** -- visibility won't work properly with only one direction.
3. **Not returning an array from MintLogic `update` functions** -- must always return `[]` even when no updates.
4. **Forgetting `return true`** in scheduler functions -- Sugar marks the job as failed without it.
5. **Using `$bean->fetched_row` in `after_save`** -- it contains values from BEFORE save. Compare with current `$bean` fields.
6. **Using `before_save` for user-facing validation** -- errors thrown or set in `before_save` are **not displayed to the user** on the frontend. For any validation that must show an error message to the user, use **MintLogic Bean-Level Validators** (`ValidationException`). Reserve `before_save` for silent data transformations, auto-fills, and calculations.
