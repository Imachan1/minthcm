# Plan: #000008 — Add department_code field to Departments module [API layer]

**Part of:** [plan.md](plan.md)
**Depends on:** Legacy layer must be complete (field must exist in vardefs).

## Context

Expose the department code via REST endpoints. Follow existing controller/route patterns.

## Acceptance criteria (this layer)

- AC 2: `GET /api/departments/{id}/code` returns the department code.
- AC 3: `PUT /api/departments/{id}/code` updates the code with edit ACL check and uniqueness validation.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `api/app/Routes/routes/departments.php` | CREATE | Route definitions for GET and PUT |
| `api/app/Controllers/DepartmentsController.php` | CREATE | Controller with get/put methods |

## Implementation plan

- [ ] Step 1: Create `api/app/Routes/routes/departments.php` with GET and PUT route definitions
- [ ] Step 2: Create `DepartmentsController.php` with `getDepartmentCode` and `putDepartmentCode` methods
- [ ] **[CHECKPOINT]** Verify: GET returns JSON; PUT with duplicate code returns 422; unauthenticated returns 401

## Notes

- Uniqueness check: query for existing record with same `department_code` before saving. Return 422 with message if duplicate found.
