# Plan: #000007 — Add task priority field to Projects module [Vue layer]

**Part of:** [plan.md](plan.md)
**Depends on:** API layer must be complete (endpoints must be available).

## Context

Create a new Vue field type `task-priority` that renders in the Project record view. Auto-discovered by `Field.config.ts`.

## Acceptance criteria (this layer)

- AC 4: A `task-priority` Vue field type renders in the Project record view.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `vue/src/components/Fields/task-priority/task-priority.detail.vue` | CREATE | Read-only priority display |
| `vue/src/components/Fields/task-priority/task-priority.edit.vue` | CREATE | Editable priority selector |

## Implementation plan

- [ ] Step 1: Create `task-priority.detail.vue` — fetch priority from API, render as chip/badge
- [ ] Step 2: Create `task-priority.edit.vue` — fetch on mount, render v-select with priority options, PUT on save
- [ ] **[CHECKPOINT]** Verify in browser: detail mode shows priority, edit mode allows changing it

## Notes

- Directory name `task-priority` must match the vardef `type` value.
- No `.list.vue` needed — falls back to varchar in list views.
