# MintHCM 4.3.1 — Release Notes

**Release date:** 2026-04-07

## Security Fixes

- **#186653 [SEC] SQL Injection in Schedulers** — Fixed SQL injection vulnerability in the Schedulers module by sanitizing query parameters in the SchedulerRepository.

## New Features

- **#184634 WCAG — Main page accessibility attributes for AI agent support** — Added aria-label and aria-description attributes to key UI elements (Kudos/News drawer, top bar buttons, global search, sidebar modules) to improve screen reader support and WCAG compliance.

## Bug Fixes

- **#186761 Mint CLI installer permission issue** — Fixed a permission error (`chmod: cannot read directory`) occurring during MintHCM CLI installation.
- **#185180 Kudos drawer showing incorrect records on tab switch** — Fixed a race condition where switching Kudos tabs (All/Received/Given) before data loaded would display incorrect records.
- **#180100 Incorrect display of quotes in list view and calendar** — Fixed HTML entity encoding issue causing single and double quotes to render as escaped entities (`&quot;`) in list views and calendar entries.
- **#184598 Supervisor change not saving on Employee record** — Fixed an issue where changing the "Reports to" field on an Employee record would not persist, by restricting association handling in getRecord to scalar (ManyToOne/OneToOne) relationships.
- **#184720 Incorrect record search on list views** — Fixed search functionality on list views where records were not found correctly when using partial name queries.

---

*Generated from release/4.3.1 merge into master.*
