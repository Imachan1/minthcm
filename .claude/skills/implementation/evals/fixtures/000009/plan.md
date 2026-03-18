# Plan: #000009 — Add contact fields to Employees module

**Redmine:** https://redmine.evolpe.net/issues/000009
**Sprint:** Sprint 5
**Plan date:** 2026-03-18

## Requirements understanding

Add three independent contact fields to the Employees module: `personal_email`, `emergency_phone`, and `blood_type`. Each field goes into a different file set, allowing parallel implementation.

## Acceptance criteria

1. Field `personal_email` (varchar) exists in Employees vardefs, with label and record view placement.
2. Field `emergency_phone` (phone) exists in Employees vardefs, with label and list view placement.
3. Field `blood_type` (enum) exists in Employees vardefs, with dropdown, label, and record view placement.

## Implementation approach

All three fields are independent — they touch different files or different sections of the same file. Steps marked [P] can be parallelized where file sets are disjoint.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/Employees/vardefs.php` | MODIFY | Add all three field definitions |
| `legacy/modules/Employees/language/en_us.lang.php` | MODIFY | Add labels for all three fields |
| `legacy/modules/Employees/metadata/recordviewdefs.php` | MODIFY | Add `personal_email` and `blood_type` to record view |
| `legacy/modules/Employees/metadata/listviewdefs.php` | MODIFY | Add `emergency_phone` to list view |
| `legacy/include/language/en_us.lang.php` | MODIFY | Add `blood_type_list` dropdown |

## Implementation plan

- [ ] [P] Step 1: Add `personal_email` varchar field to `legacy/modules/Employees/vardefs.php` and label to `legacy/modules/Employees/language/en_us.lang.php`
- [ ] [P] Step 2: Add `emergency_phone` phone field to `legacy/modules/Employees/vardefs.php` and label to `legacy/modules/Employees/language/en_us.lang.php`
- [ ] [P] Step 3: Add `blood_type` enum field to `legacy/modules/Employees/vardefs.php`, label to `legacy/modules/Employees/language/en_us.lang.php`, and dropdown to `legacy/include/language/en_us.lang.php`
- [ ] **[CHECKPOINT]** Verify: `php -l` on all modified PHP files
- [ ] Step 4: Add `personal_email` and `blood_type` to `legacy/modules/Employees/metadata/recordviewdefs.php`
- [ ] Step 5: Add `emergency_phone` to `legacy/modules/Employees/metadata/listviewdefs.php`

## Risks and notes

- Steps 1–3 are marked [P] but they ALL modify the same two files (`vardefs.php` and `en_us.lang.php`). The agent must detect this file conflict and execute them sequentially instead.
