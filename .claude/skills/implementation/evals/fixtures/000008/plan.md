# Plan: #000008 — Add department_code field to Departments module

**Redmine:** https://redmine.evolpe.net/issues/000008
**Sprint:** Sprint 5
**Plan date:** 2026-03-18
**Type:** Hierarchical plan

## Requirements understanding

Each department needs a unique code for integration with external HR systems. This requires a legacy field, an API endpoint to read/update it, and a Vue component to display/edit it.

## Acceptance criteria

1. New varchar field `department_code` exists in the Departments module vardefs.
2. `GET /api/departments/{id}/code` returns the department code.
3. `PUT /api/departments/{id}/code` updates the code; requires edit access.
4. Field is visible and editable in the Department record view.

## Architecture overview

This US spans 2 layers. Recommended implementation order:

1. **Legacy** (`plan_legacy.md`) — add field + labels + recordviewdefs. No dependencies.
2. **API** (`plan_api.md`) — implement GET and PUT `/departments/{id}/code`. Depends on: Legacy layer.

## Sub-plans

| Layer   | File            | Status     | Summary                                                        |
|---------|-----------------|------------|----------------------------------------------------------------|
| Legacy  | plan_legacy.md  | ✅ done    | Varchar field in Departments vardefs + recordviewdefs + labels  |
| API     | plan_api.md     | ⬜ pending | DepartmentsController + routes for GET/PUT code                |

## Cross-layer risks and notes

- The `department_code` field should have a unique constraint enforced at the API level (not DB level) to allow graceful error messages.
