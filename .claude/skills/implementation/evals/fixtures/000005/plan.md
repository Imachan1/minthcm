# Plan: #000005 — Add emergency_contact field to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000005
**Sprint:** Sprint 1
**Plan date:** 2026-03-17

## Requirements understanding

Add an `emergency_contact` varchar field to the Employees module so that an emergency contact name can be stored per employee.

## Acceptance criteria

- Field `emergency_contact` (type: varchar, length: 150) exists in the Employees module
- Field is visible and editable in the record view
- Value is persisted and retrieved correctly

## Implementation approach

1. Define `emergency_contact` in `legacy/modules/Employees/vardefs.php`.
2. Add the field to the record view layout in `legacy/modules/Employees/metadata/recordviewdefs.php`.

**Note on file path deviation:** The actual recordviewdefs path may be under `legacy/custom/modules/Employees/metadata/recordviewdefs.php` (customization layer) rather than the core path. If discovered during implementation, add an inline note and adjust accordingly.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add `emergency_contact` varchar field definition |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `emergency_contact` field to the personal data panel |

## Implementation plan

- [ ] Step 1.1: Add `emergency_contact` field definition to `legacy/modules/Employees/vardefs.php`
- [ ] **[CHECKPOINT]** Phase 1 verification: run `php -l legacy/modules/Employees/vardefs.php`; confirm `emergency_contact` key appears in file
- [ ] Step 2.1: Add `emergency_contact` to the layout in `legacy/modules/Employees/metadata/recordviewdefs.php`
  <!-- If this file does not exist, check legacy/custom/modules/Employees/metadata/ instead and update plan accordingly -->

## Risks and notes

- The recordviewdefs.php may only exist in the `custom/` layer — agent should detect this at runtime, add an inline note to plan.md, and proceed with the correct path without stopping to ask the user.
