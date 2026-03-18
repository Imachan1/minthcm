# Plan: #000002 — Add department field to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000002
**Sprint:** Sprint 1
**Plan date:** 2026-03-17

## Requirements understanding

Add a `department` varchar field to the Employees module so that each employee can be assigned to an organizational department.

## Acceptance criteria

- Field `department` (type: varchar, length: 100) exists in the Employees module
- Field is visible and editable in the record view
- Value is persisted and retrieved correctly

## Implementation approach

1. Define `department` as a `varchar(100)` field in `legacy/modules/Employees/vardefs.php`.
2. Add the field to the record view layout in `legacy/modules/Employees/metadata/recordviewdefs.php`.
3. Add a corresponding column to the list view in `legacy/modules/Employees/metadata/listviewdefs.php`.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add `department` varchar field definition |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `department` field to the details panel |
| `legacy/modules/Employees/metadata/listviewdefs.php` | MODIFY | Add `department` column to list view |

## Implementation plan

- [x] Step 1.1: Add `department` field definition to `legacy/modules/Employees/vardefs.php`
- [~] Step 2.1: Add `department` to the layout in `legacy/modules/Employees/metadata/recordviewdefs.php`
  <!-- NOTE: file was modified but panel placement still needs verification — resuming -->
- [~] Step 3.1: Add `department` column to `legacy/modules/Employees/metadata/listviewdefs.php`
  <!-- NOTE: file not yet touched -->
- [ ] **[CHECKPOINT]** Final verification: php -l on all modified files; confirm fields appear in layouts

## Risks and notes

- Step 2.1 is marked [~] but `recordviewdefs.php` already contains the department entry (verify via git diff before re-executing).
- Step 3.1 is marked [~] but `listviewdefs.php` has NOT been modified yet — resume required.
