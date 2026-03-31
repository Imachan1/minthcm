# Plan: #000003b — Add phone_work field to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000003b
**Sprint:** Sprint 1
**Plan date:** 2026-03-17

## Requirements understanding

Add a `phone_work` varchar field to the Employees module so that work phone numbers can be stored per employee.

## Acceptance criteria

- Field `phone_work` (type: varchar, length: 25) exists in the Employees module
- Field is visible and editable in the record view
- Value is persisted and retrieved correctly

## Implementation approach

1. Define `phone_work` in `legacy/modules/Employees/vardefs.php`.
2. Add the field to the record view layout in `legacy/modules/Employees/metadata/recordviewdefs.php`.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add `phone_work` varchar field definition |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `phone_work` field to the contact panel |

## Implementation plan

- [x] Step 1.1: Add `phone_work` field definition to `legacy/modules/Employees/vardefs.php`
- [!] Step 2.1: Add `phone_work` to the layout in `legacy/modules/Employees/metadata/recordviewdefs.php`
  <!-- FAILURE: recordviewdefs.php — syntax error on line 52. File locked by deployment process. Cannot write. -->

## Risks and notes

- Step 2.1 has failed TWICE (original attempt + first retry). The file cannot be written — likely locked by a deployment script.
- Second retry should NOT be attempted. Agent must stop and ask user for guidance.
