# Plan: #000004 — Add birth_date field to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000004
**Sprint:** Sprint 1
**Plan date:** 2026-03-17

## Requirements understanding

Add a `birth_date` date field to the Employees module so that the employee's date of birth can be recorded for HR compliance purposes.

## Acceptance criteria

- Field `birth_date` (type: date) exists in the Employees module
- Field is visible and editable in the record view
- Value is persisted and retrieved correctly

## Implementation approach

1. Define `birth_date` as a `date` field in `legacy/modules/Employees/vardefs.php`.
2. Add the field to the record view layout in `legacy/modules/Employees/metadata/recordviewdefs.php`.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add `birth_date` date field definition |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `birth_date` field to the personal data panel |

## Implementation plan

- [x] Step 1.1: Add `birth_date` field definition to `legacy/modules/Employees/vardefs.php`
- [x] **[CHECKPOINT]** Phase 1 verification: php -l legacy/modules/Employees/vardefs.php — passed
- [x] Step 2.1: Add `birth_date` to the layout in `legacy/modules/Employees/metadata/recordviewdefs.php`

## Risks and notes

- All steps complete. Ready for commit.
