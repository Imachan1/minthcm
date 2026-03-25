# Plan: #000003 — Add phone_mobile field to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000003
**Sprint:** Sprint 1
**Plan date:** 2026-03-17

## Requirements understanding

Add a `phone_mobile` varchar field to the Employees module so that mobile phone numbers can be stored per employee.

## Acceptance criteria

- Field `phone_mobile` (type: varchar, length: 25) exists in the Employees module
- Field is visible and editable in the record view
- Value is persisted and retrieved correctly

## Implementation approach

1. Define `phone_mobile` in `legacy/modules/Employees/vardefs.php`.
2. Add the field to the record view layout in `legacy/modules/Employees/metadata/recordviewdefs.php`.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add `phone_mobile` varchar field definition |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `phone_mobile` field to the contact panel |

## Implementation plan

- [x] Step 1.1: Add `phone_mobile` field definition to `legacy/modules/Employees/vardefs.php`
- [!] Step 2.1: Add `phone_mobile` to the layout in `legacy/modules/Employees/metadata/recordviewdefs.php`
  <!-- FAILURE: php -l reported a parse error — unexpected ')' on line 47. File was partially edited. -->
- [ ] **[CHECKPOINT]** Final verification: php -l on all modified files; confirm field appears in layout

## Risks and notes

- Step 2.1 failed due to a PHP syntax error introduced during editing. Retry should fix the malformed line.
