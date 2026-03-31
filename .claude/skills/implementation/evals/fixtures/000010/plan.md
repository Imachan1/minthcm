# Plan: #000010 — Add multiple independent files to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000010
**Sprint:** Sprint 5
**Plan date:** 2026-03-18

## Requirements understanding

Add a `nickname` varchar field and a `dietary_restrictions` text field to the Employees module. Each field requires its own language file (separate translation files for different locales) and view metadata update. The fields are fully independent.

## Acceptance criteria

1. Field `nickname` (varchar) exists in Employees vardefs with English label and record view placement.
2. Field `dietary_restrictions` (text) exists in Employees vardefs with English label and list view placement.

## Implementation approach

The two fields touch completely different files (except vardefs.php which is shared). Steps are organized so that truly parallel steps have disjoint file sets.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add both field definitions |
| `legacy/modules/Employees/language/en_us.lang.php` | MODIFY | Add labels for both fields |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `nickname` to record view |
| `legacy/modules/Employees/metadata/listviewdefs.php` | MODIFY | Add `dietary_restrictions` to list view |

## Implementation plan

- [ ] Step 1: Add `nickname` and `dietary_restrictions` field definitions to `legacy/modules/Employees/vardefs.php` and labels to `legacy/modules/Employees/language/en_us.lang.php`
- [ ] **[CHECKPOINT]** Verify: `php -l legacy/modules/Employees/vardefs.php` and `php -l legacy/modules/Employees/language/en_us.lang.php`
- [ ] [P] Step 2: Add `nickname` to `legacy/modules/Employees/metadata/recordviewdefs.php`
- [ ] [P] Step 3: Add `dietary_restrictions` to `legacy/modules/Employees/metadata/listviewdefs.php`

## Risks and notes

- Steps 2 and 3 are [P] and touch different files (`recordviewdefs.php` vs `listviewdefs.php`) — safe to parallelize.
