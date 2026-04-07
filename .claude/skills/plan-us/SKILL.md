---
name: plan-us
version: 2.0.0
description: Skill for planning User Story implementation from Redmine. Use when user asks to "plan a US", "implementation plan", "prepare a plan", "create a branch for US", or provides a US number to plan. Fetches the US from Redmine, analyzes the codebase, creates a feature branch, and saves the plan to .ai/tasks/{ISSUE_ID}/plan.md. For large USes spanning multiple architectural layers, automatically creates a hierarchical plan (master + sub-plans per layer). Also handles bug trackers (Błąd krytyczny, Błąd niekrytyczny, User Story Bug) with a root-cause diagnosis loop before planning.
argument-hint: <issue_number_or_url> (e.g. 184819 or https://redmine.evolpe.net/issues/184819)
---

# Issue Implementation Planning

The skill prepares the ground for implementation — analyzes requirements, explores the codebase,
and creates a plan in `.ai/tasks/{ISSUE_ID}/` to serve as a guide throughout the entire implementation.

**Two distinct paths depending on issue type:**

| Path | Trackers | Description |
|------|----------|-------------|
| **US flow** | US (18), Epic (22), Spike (23), Mały rozwój (14) | Clarify requirements → explore → plan |
| **Bug diagnosis flow** | User Story Bug (19), Błąd krytyczny (15), Błąd niekrytyczny (16) | Find root cause → confirm with user → select skills → plan |

---

## When to use

- User asks to "plan a US/bug/issue", "plan for #XXXX", "prepare implementation"
- User says "let's start with #184819" or "what needs to be done for this issue"
- User wants a feature branch without writing code yet

## When NOT to use

- **Implementation is already in progress** — the plan should already exist, don't create it mid-implementation
- Issues of type Task (24), Wsparcie (3), Spotkanie (11), and other non-planning trackers
- User only asks about the issue content without wanting to create a plan and branch

---

## Requirements

- **Redmine MCP** tool (`redmine_request`) — recommended; without it, manual mode
- **git** repository (check `git status`)

### Manual mode (without Redmine MCP)

If MCP is unavailable:
- **Step 2** — ask the user to paste the issue content from Redmine
- Final step — skip committing the plan to Redmine, display the plan ready to copy

---

## Common steps (all paths)

### Step 1 — Parse issue number

Extract `ISSUE_ID` from `$ARGUMENTS` (slash cmd) or from conversation context.
- Number (e.g. `184819`) → use directly
- URL (e.g. `https://redmine.evolpe.net/issues/184819`) → extract number from path
- Missing → ask: "Provide the issue number to plan."

### Step 2 — Fetch issue from Redmine

Fetch:
- Title, description, acceptance criteria (as part of description)
- Tracker (ID and name), Sprint (`fixed_version_id`), project, assignee
- Existing children (Tasks, sub-issues)

**Manual mode:** ask the user to paste the issue content.

### Step 3 — Validate tracker and route

**Routing table:**

| Tracker ID | Name | Path |
|---|---|---|
| 14 | Mały rozwój | → **US flow** |
| 18 | User Story | → **US flow** |
| 22 | Epic | → **US flow** |
| 23 | Spike | → **US flow** |
| 19 | User Story Bug | → **Bug diagnosis flow** |
| 15 | Błąd krytyczny | → **Bug diagnosis flow** |
| 16 | Błąd niekrytyczny | → **Bug diagnosis flow** |

If tracker is any other (Task 24, Wsparcie 3, Spotkanie 11, etc.) — stop:
> "Issue #{ISSUE_ID} is a {tracker_name}. Planning applies to US, Epic, Spike, Mały rozwój, and bug trackers only."

**After routing:**
- **US flow** → **Read `references/us-flow.md`** and continue from Step 4 defined there.
- **Bug diagnosis flow** → **Read `references/bug-diagnosis.md`** and continue from Step B1 defined there.

---

## Expected working style

- **Explore before writing the plan** — don't guess code structure
- **Paraphrase requirements** — don't copy the description 1:1, show that you understand the intent
- **Ask about ambiguities** — open questions in the plan are better than silent assumptions
- **Don't commit without consent** — always wait for confirmation before the final commit
- **One commit** — only plan files, no code whatsoever
- **Bug flow: diagnose before planning** — for bug trackers, confirm root cause with the user *before* writing the plan
- **Bug flow: no domain skills during diagnosis** — load `minthcm-core` / `minthcm-project` only after the user confirms the root cause
- **Hierarchical when it matters** — split only when 2+ layers have meaningful work; don't split artificially
- **Layers come from the project, not from you** — always derive layer names from the project's `CLAUDE.md` or `README.md` via the Explore subagent. Never invent layer names.
- **Sub-plans must be self-contained** — each sub-plan should make sense on its own without reading the others
- After completion, the user proceeds to implementation using the plan files as a checklist
