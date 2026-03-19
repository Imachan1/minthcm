# Plan: #000008 — Add department_code field to Departments module [Legacy layer]

**Part of:** [plan.md](plan.md)
**Depends on:** No dependencies.

## Context

Standard varchar field addition to the Departments module.

## Acceptance criteria (this layer)

- AC 1: New varchar field `department_code` exists in the Departments module vardefs.
- AC 4 (partial): Field is visible in the record view.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Departments/vardefs.php` | MODIFY | Add `department_code` varchar field |
| `legacy/modules/Departments/language/en_us.lang.php` | MODIFY | Add label |
| `legacy/modules/Departments/metadata/recordviewdefs.php` | MODIFY | Add field to layout |

## Implementation plan

- [x] Step 1: Add `department_code` varchar field to vardefs.php
- [x] Step 2: Add `LBL_DEPARTMENT_CODE` label to en_us.lang.php
- [x] **[CHECKPOINT]** Verify: `php -l` on both files
- [x] Step 3: Add `department_code` to recordviewdefs.php

## Notes

- Quick Repair and Rebuild required after changes.
