# Plan: #000007 — Add task priority field to Projects module [API layer]

**Part of:** [plan.md](plan.md)
**Depends on:** Legacy layer must be complete (vardef field must exist).

## Context

Expose the task priority via two REST endpoints following the existing controller/route pattern.

## Acceptance criteria (this layer)

- AC 2: `GET /api/projects/{id}/task-priority` returns the current priority.
- AC 3: `PUT /api/projects/{id}/task-priority` updates the priority with edit ACL check.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `api/app/Routes/routes/projects.php` | MODIFY | Add GET and PUT routes for task-priority |
| `api/app/Controllers/ProjectsController.php` | MODIFY | Add `getTaskPriority` and `putTaskPriority` methods |

## Implementation plan

- [ ] Step 1: Add GET and PUT route definitions to `api/app/Routes/routes/projects.php`
- [ ] Step 2: Add `getTaskPriority` method to `ProjectsController` — read priority from entity, return JSON
- [ ] Step 3: Add `putTaskPriority` method to `ProjectsController` — validate input, ACL check, update entity, return JSON
- [ ] **[CHECKPOINT]** Verify: GET endpoint returns JSON with priority value; PUT with invalid data returns 422

## Notes

- Use existing `PositionsController` as pattern reference for ACL check implementation.
