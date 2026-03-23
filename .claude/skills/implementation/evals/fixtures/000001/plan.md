# Plan: #000001 — Add notes field to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000001
**Sprint:** Sprint 1
**Plan date:** 2026-03-17

## Requirements understanding

Add a free-text `notes` field to the Employees module so HR staff can record miscellaneous observations about an employee directly on the record view.

## Acceptance criteria

- Field `notes` (type: text) exists in the Employees module
- Field is visible and editable in the employee record view
- Value is persisted and retrieved correctly

## Implementation approach

1. Define `notes` as a `text` field in `legacy/modules/Employees/vardefs.php`.
2. Add the field to the record view layout in `legacy/modules/Employees/metadata/recordviewdefs.php`.

Entity regeneration (Quick Repair & Rebuild) is a manual admin step outside automated implementation scope.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add `notes` text field definition |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `notes` field to the details panel |

## Implementation plan

- [ ] Step 1.1: Add `notes` field definition to `legacy/modules/Employees/vardefs.php`
- [ ] **[CHECKPOINT]** Phase 1 verification: confirm `notes` is defined in vardefs.php; run `php -l legacy/modules/Employees/vardefs.php`
- [ ] Step 2.1: Add `notes` to the layout in `legacy/modules/Employees/metadata/recordviewdefs.php`

## Risks and notes

- Quick Repair & Rebuild must be run manually after implementation to regenerate the Doctrine entity.
