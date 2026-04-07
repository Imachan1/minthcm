# Bug #100010 — Candidature status not refreshed in list view after record save

**Tracker:** Błąd niekrytyczny (16)
**Sprint:** Sprint 45
**Project:** MintHCM Core
**Assignee:** Marek Kowalczyk

## Description

After saving a Candidature record (changing the status field, e.g. from "New" to "In progress"),
the list view still shows the old status value. The user must manually reload the page (F5) to
see the updated value in the list.

The record view itself shows the correct value immediately after save — the problem is only
in the list view.

## Steps to reproduce

1. Open the Candidatures module list view
2. Click on any candidature record
3. In the record view, change the `status` field to a different value
4. Click Save
5. Navigate back to the list view (using breadcrumb or sidebar menu)
6. Observe: the `status` column still shows the old value

## Expected behavior

After saving, returning to the list view should show the updated status value without requiring
a page reload.

## Actual behavior

List view shows stale data. Hard reload (F5) fixes the display.

## Environment

- Browser: Chrome 124, Firefox 125 (reproducible in both)
- Occurs in both record view and edit view save paths

## Notes

- Possibly related to Pinia store caching — the list store may not be invalidated after a record
  save event
- Similar stale-data symptom was reported for the Employees module 2 sprints ago (resolved)

---

## Diagnosis confirmation (user response)

Tak, zgadzam się z diagnozą. Rzeczywiście po zapisie rekordu store listy nie jest odświeżany.
Punkt 1 jest trafny — brakuje wywołania akcji odświeżenia listy po powrocie z widoku rekordu.
Możemy przejść do planowania.
