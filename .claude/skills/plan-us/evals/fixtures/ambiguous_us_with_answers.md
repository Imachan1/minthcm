# US #100003 — Improve the delegation process

**Tracker:** User Story (18)
**Sprint:** Sprint 44
**Project:** MintHCM Core
**Assignee:** Piotr Wiśniewski

## Description

The current delegation handling needs improvement. Users have reported that the process
is not clear and some things are missing. We should make it better and add what's needed.

The main issues are around notifications and the approval flow. Also, the list view could
show more information.

## Acceptance criteria

1. The delegation process is improved
2. Notifications work correctly
3. Approval flow is clearer
4. List view is better

## Notes

- Talk to the product team for details
- Priority: medium

---

## Clarifications (answered by product team)

**Q: Notifications — what type, who receives them, at what events?**

Email + in-app notifications. Triggered on delegation status changes:
- `created` → notify direct manager (to approve)
- `approved` → notify delegated employee
- `rejected` → notify delegated employee with rejection reason

The existing notification mechanism exists but is partially broken — emails on `approved`
and `rejected` statuses are not being sent. No new mechanism needed, fix the existing one.

**Q: Approval flow — does it exist, what statuses, who approves, how?**

An approval flow partially exists. Current statuses: `created`, `closed`. We need to add:
- `pending_approval` — set automatically when delegation is created
- `approved` — set by manager via a button on the delegation record
- `rejected` — set by manager via a button, with a required `rejection_reason` text field

Single approver = direct manager of the delegated employee (already accessible via
Employee → `reports_to_id`). Approval is done inside MintHCM via status-change buttons
on the Delegation record view.

**Q: List view — which columns, which view?**

Main Delegations list view only (not subpanels). Add two columns:
- `status` — the current approval status (enum)
- `approved_by_name` — name of the approver (relate field)

No changes to filtering or sorting needed.
