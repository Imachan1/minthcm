# Plan: #000007 — Add task priority field to Projects module

**Redmine:** https://redmine.evolpe.net/issues/000007
**Sprint:** Sprint 5
**Plan date:** 2026-03-18
**Type:** Hierarchical plan

## Requirements understanding

Project managers need a way to set and display task priority directly on a project record. This involves adding the field definition in the legacy layer, exposing it via a REST endpoint in the API layer, and rendering an editable priority selector in the Vue frontend.

## Acceptance criteria

1. New enum field `task_priority` exists in the Projects module vardefs.
2. `GET /api/projects/{id}/task-priority` returns the current priority value.
3. `PUT /api/projects/{id}/task-priority` updates the priority; requires edit access.
4. A `task-priority` Vue field type renders in the Project record view.

## Architecture overview

This US spans 3 layers. Recommended implementation order:

1. **Legacy** (`plan_legacy.md`) — add `task_priority` enum field to vardefs + update recordviewdefs. No dependencies.
2. **API** (`plan_api.md`) — implement GET and PUT `/projects/{id}/task-priority`. Depends on: Legacy layer for vardef definition.
3. **Vue** (`plan_vue.md`) — create `task-priority` field type component. Depends on: API layer being available.

## Sub-plans

| Layer   | File            | Status     | Summary                                                        |
|---------|-----------------|------------|----------------------------------------------------------------|
| Legacy  | plan_legacy.md  | ⬜ pending | Enum field in Projects vardefs + recordviewdefs panel          |
| API     | plan_api.md     | ⬜ pending | ProjectsController + routes for GET/PUT task-priority          |
| Vue     | plan_vue.md     | ⬜ pending | New `task-priority` field type with detail + edit components   |

## Cross-layer risks and notes

- The `task_priority_list` dropdown values must be consistent between the legacy enum definition and the Vue component rendering.
- ACL check for PUT must use legacy `ACLController::checkAccess('Projects', 'edit', true)`.
- After vardef changes, Quick Repair and Rebuild is required.
