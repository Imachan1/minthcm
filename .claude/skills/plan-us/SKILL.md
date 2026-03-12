---
name: plan-us
version: 1.1.0
description: Skill for planning User Story implementation from Redmine. Use when user asks to "plan a US", "implementation plan", "prepare a plan", "create a branch for US", or provides a US number to plan. Fetches the US from Redmine, analyzes the codebase, creates a feature branch, and saves the plan to .ai/tasks/{ISSUE_ID}/plan.md.
argument-hint: <issue_number_or_url> (e.g. 184819 or https://redmine.evolpe.net/issues/184819)
---

# US Implementation Planning

The skill prepares the ground for implementation — analyzes requirements, explores the codebase,
and creates a plan in `.ai/tasks/{ISSUE_ID}/plan.md` to serve as a guide throughout the entire implementation.

---

## When to use

- User asks to "plan a US", "plan for #XXXX", "prepare implementation"
- User says "let's start with US #184819" or "what needs to be done for this US"
- User wants a feature branch without writing code yet

## When NOT to use

- **Implementation is already in progress** — the plan should already exist, don't create it mid-implementation
- Issues other than US/Epic/Spike — e.g. Task, Bug (not planned separately)
- User only asks about the US content without wanting to create a plan and branch

---

## Requirements

- **Redmine MCP** tool (`redmine_request`) — recommended; without it, manual mode
- **git** repository (check `git status`)

### Manual mode (without Redmine MCP)

If MCP is unavailable:
- **Step 2** — ask the user to paste the US content from Redmine
- **Step 10** — skip committing the plan to Redmine, display the plan ready to copy

---

## Process (10 steps)

### Step 1 — Parse issue number

Extract `ISSUE_ID` from `$ARGUMENTS` (slash cmd) or from conversation context.
- Number (e.g. `184819`) → use directly
- URL (e.g. `https://redmine.evolpe.net/issues/184819`) → extract number from path
- Missing → ask: "Provide the User Story number to plan."

### Step 2 — Fetch US from Redmine

Fetch:
- Title, description, acceptance criteria (as part of description)
- Tracker (validate: only 18=US, 19=US Bug, 22=Epic, 23=Spike)
- Sprint (`fixed_version_id`), project, assignee
- Existing Tasks (issue children)

**Manual mode:** ask the user to paste the US content.

### Step 3 — Validate tracker

If tracker is Task (24) or other non-planning type — stop:
> "Issue #{ISSUE_ID} is a {tracker}, not a US. Planning applies to US/Epic/Spike only."

Allowed tracker IDs: 18, 19, 22, 23.

### Step 4 — Clarify ambiguities

Review the US description and identify **max 3 most important ambiguities** — places where requirements are imprecise, contradictory, or missing key context.

- If ambiguities exist — ask the user questions and wait for answers before Step 7.
- If everything is clear — skip this step and continue.

> Don't ask about technical things (how to do it) — only about requirements (what it should do, for whom, in what case).

### Step 5 — Select skills

Review available skills in `.claude/skills/` (each has a `SKILL.md` with a description in the `description` frontmatter).
Based on the US scope and clarified requirements, decide which will be needed during exploration and planning.

Example criteria:

| If the US involves... | Load skill |
|---|---|
| New module, field, endpoint, entity, MintLogic in core | `minthcm-core` |
| Customization, client logic, subpanels, listviewdefs, hooks | `minthcm-project` |
| Both core and customization | both of the above |
| New skill / skill change | `skill-creator` |

> If no skill fits the US scope — skip this step.

Display a table of selected skills to the user and **wait for confirmation** before proceeding:

| Skill | Reason for selection |
|-------|----------------------|
| `minthcm-core` | US requires adding a new field/entity/endpoint in core |
| ... | ... |

If no skills were selected, write: "No additional skills required for this US."

Load selected skills (read their SKILL.md) **after user confirms** and before Step 6.

---

### Steps 6 + 7 — Parallel execution

**After Step 5 (skills confirmed and loaded), launch Steps 6 and 7 in a single message as parallel tool calls.**

---

### Step 6 — Check repository state *(parallel)*

```bash
git fetch --all
git status
git branch -a | grep "{ISSUE_ID}"
```

Check if the feature branch already exists. If so — inform the user and ask
whether to continue (it may be re-planning after a scope change).

### Step 7 — Explore codebase for the US *(parallel, Explore subagent)*

**Use the Agent tool with `subagent_type=Explore` and `model=haiku`.**

Pass the full US description and requirements in the prompt so the subagent has complete context. The subagent should answer:
- Which modules/files are likely affected?
- Are there similar implementations to model after?
- What dependencies might be relevant?

The subagent must **not modify anything** — only `Glob`, `Grep`, `Read`.

The subagent should return a **structured report** with:
- List of relevant files with paths and line numbers
- Pattern references (e.g. "similar field defined in `legacy/modules/Employees/vardefs.php:42`")
- Any architectural observations relevant to implementation

> Do not use `Glob`/`Grep`/`Read` directly in the main conversation for exploration — delegate entirely to the subagent to keep the main context clean.

### Step 8 — Create feature branch

```bash
git checkout master          # base branch per CLAUDE.md → "Git Workflow" section
git pull
git checkout -b feature/{ISSUE_ID}
```

If branch already exists → switch to it: `git checkout feature/{ISSUE_ID}`

### Step 9 — Write the plan

Create directory and plan file:

```bash
mkdir -p .ai/tasks/{ISSUE_ID}
```

File path: `.ai/tasks/{ISSUE_ID}/plan.md`

```markdown
# Plan: #{ISSUE_ID} — {US title}

**Redmine:** https://redmine.evolpe.net/issues/{ISSUE_ID}
**Sprint:** {sprint}
**Plan date:** {YYYY-MM-DD}

## Requirements understanding

{Paraphrase of what the US requires — in your own words, don't copy the description 1:1.
 Show that you understand the intent, not just the letter of the requirements.}

## Acceptance criteria

{List from US — rewrite or supplement if unclear}

## Implementation approach

{Technical description of how to fulfill the requirements — architecture, patterns, tools.
 Reference specific code locations found in Step 7.}

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| ... | CREATE/MODIFY/DELETE | ... |

## Implementation plan

Use `[P]` markers for steps that can be done in parallel and `[CHECKPOINT]` as verification points before moving on.

- [ ] [P] Step 1: ...
- [ ] [P] Step 2: ...
- [ ] **[CHECKPOINT]** Verification: ...
- [ ] Step 3: ...

## Risks and notes

{Potential issues, dependencies on other USs, side effects of changes.
 Omit section if no risks.}

## Open questions

{Questions requiring clarification before or during implementation.
 Omit section if everything is clear.}
```

### Step 10 — Summary and plan commit

Display summary:

```
Plan for #{ISSUE_ID} ready.

Branch:  feature/{ISSUE_ID}
Plan:    .ai/tasks/{ISSUE_ID}/plan.md
Steps:   {N}
Risks:   {list if any, "none" if not}

Should I commit the plan?
```

After user confirmation:

```bash
git add .ai/tasks/{ISSUE_ID}/plan.md
git commit -m "plan - ref #{ISSUE_ID}"
```

Display commit hash. Do not push without asking.

---

## Expected working style

- **Explore before writing the plan** — don't guess code structure, use Step 7
- **Paraphrase requirements** — don't copy the US description, show that you understand the intent
- **Ask about ambiguities** — open questions in the plan are better than silent assumptions
- **Don't commit without consent** — always wait for confirmation before Step 10 commit
- **One commit** — only `plan.md`, no code whatsoever
- After completion, the user proceeds to implementation using `plan.md` as a checklist
