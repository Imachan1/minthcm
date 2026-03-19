# US #100001 — Add "preferred_language" field to Employees module

**Tracker:** User Story (18)
**Sprint:** Sprint 42
**Project:** MintHCM Core
**Assignee:** Jan Kowalski

## Description

As an HR manager, I want to store the preferred communication language of each employee,
so that I can generate documents and send notifications in the correct language.

The field should be a dropdown (enum) with at least the following values:
- Polish (pl)
- English (en)
- German (de)

The field should be visible on the Employee record view (detail/edit) and in the employee
list view as an optional column.

## Acceptance criteria

1. New enum field `preferred_language` exists in the Employees module vardefs
2. Dropdown `preferred_language_list` is defined with at least pl, en, de options
3. Field is visible on the Employee record view (recordviewdefs)
4. Field is visible as an optional column in the list view (listviewdefs / eslistviewdefs)
5. Field label is translated in both Polish and English (en_us.lang.php, pl_pl.lang.php)

## Notes

- This is a core change (not a customization)
- No API endpoint needed — field is served through the existing generic beans API
- No special Vue component needed — standard `enum` field type is sufficient
