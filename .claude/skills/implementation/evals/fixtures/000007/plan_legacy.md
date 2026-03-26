# Plan: #000007 — Add task priority field to Projects module [Legacy layer]

**Part of:** [plan.md](plan.md)
**Depends on:** No dependencies — this layer only adds metadata.

## Context

The Projects module uses standard SuiteCRM vardefs. Adding a new enum field follows the same pattern as other enum fields in the module.

## Acceptance criteria (this layer)

- AC 1: New enum field `task_priority` exists in the Projects module vardefs.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Projects/vardefs.php` | MODIFY | Add `task_priority` enum field definition |
| `legacy/modules/Projects/language/en_us.lang.php` | MODIFY | Add `LBL_TASK_PRIORITY` label |
| `legacy/modules/Projects/metadata/recordviewdefs.php` | MODIFY | Add `task_priority` field to layout |

## Implementation plan

- [ ] Step 1: Add `task_priority` enum field to `legacy/modules/Projects/vardefs.php`
- [ ] Step 2: Add `LBL_TASK_PRIORITY` label to `legacy/modules/Projects/language/en_us.lang.php`
- [ ] **[CHECKPOINT]** Verify vardefs and language files: `php -l` on both files
- [ ] Step 3: Add `task_priority` to the layout in `legacy/modules/Projects/metadata/recordviewdefs.php`

## Notes

- Quick Repair and Rebuild required after vardef changes.
