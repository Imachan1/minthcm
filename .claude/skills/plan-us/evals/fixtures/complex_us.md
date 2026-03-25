# US #100002 — Competency matrix — display and edit employee competency levels

**Tracker:** User Story (18)
**Sprint:** Sprint 43
**Project:** MintHCM Core
**Assignee:** Anna Nowak

## Description

As an HR manager, I want to see and edit the competency levels of an employee directly on
the Employee record view, so that I can assess and update skills without navigating to a
separate module.

The competency matrix should display all competencies assigned to the employee's position,
with the current level (from the Competencies relationship) editable inline.

This feature requires:
1. A new API endpoint returning the competency matrix for a given employee (list of
   competencies + current level for each)
2. A new Vue component `CompetencyMatrix` embedded in the Employee record view as a custom
   field type
3. Legacy changes: a new virtual field definition in Employee vardefs to expose the component,
   and updated recordviewdefs to include the panel

## Acceptance criteria

1. `GET /api/employees/{id}/competency-matrix` returns an array of `{ competency_id, name, level, max_level }` objects
2. The endpoint is protected — only accessible to logged-in users with read access to the employee
3. `PUT /api/employees/{id}/competency-matrix` accepts an array of `{ competency_id, level }` and updates competency levels
4. A new Vue field type `competency-matrix` renders the matrix as an editable grid in record view
5. The matrix is visible on the Employee record view in a new "Competencies" panel
6. In read mode: shows competency names and level indicators (e.g. stars or numeric)
7. In edit mode: levels are editable (dropdown or stepper per row)
8. Empty state shown if no competencies assigned

## Dependencies

- Requires the existing `Competencies` and `Positions` modules (already present in core)
- Relies on the existing relationship between Employees ↔ Competencies

## Notes

- The virtual field in vardefs is just a placeholder to trigger the Vue component — no DB column
- The API layer must validate that the requesting user has edit rights before accepting PUT
- Competency levels are integers 0–5 (defined per competency record)
