---
name: plan-us
version: 1.2.0
description: Skill for planning User Story implementation from Redmine. Use when user asks to "plan a US", "implementation plan", "prepare a plan", "create a branch for US", or provides a US number to plan. Fetches the US from Redmine, analyzes the codebase, creates a feature branch, and saves the plan to .ai/tasks/{ISSUE_ID}/plan.md. For large USes spanning multiple architectural layers, automatically creates a hierarchical plan (master + sub-plans per layer). Layers are discovered from the project's architecture documentation — the skill is not tied to any specific tech stack.
argument-hint: <issue_number_or_url> (e.g. 184819 or https://redmine.evolpe.net/issues/184819)
---

# US Implementation Planning

The skill prepares the ground for implementation — analyzes requirements, explores the codebase,
and creates a plan in `.ai/tasks/{ISSUE_ID}/` to serve as a guide throughout the entire implementation.

For simple USes (single layer or no meaningful layer split): creates `plan.md`.
For complex USes (multiple distinct layers): creates `plan.md` (master hub) + `plan_{layer_key}.md` per layer.
Layers are discovered from the project's architecture documentation (e.g. `CLAUDE.md`, `README.md`), not hardcoded.

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

## Process (11 steps)

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
- **Which architectural layers** does this US touch, and how significantly? (Layers are identified from project architecture docs — see Layer identification instructions below.)

The subagent must **not modify anything** — only `Glob`, `Grep`, `Read`.

The subagent should return a **structured report** with:
- List of relevant files with paths and line numbers
- Pattern references (e.g. "similar field defined in `legacy/modules/Employees/vardefs.php:42`")
- Any architectural observations relevant to implementation
- **Layer assessment**: which layers are affected and estimated scope of work in each

> Do not use `Glob`/`Grep`/`Read` directly in the main conversation for exploration — delegate entirely to the subagent to keep the main context clean.

#### Layer identification (for the explore subagent)

As the **first action**, read the project's architecture documentation:
1. Read `CLAUDE.md` (repo root or `.claude/`) — look for an "Architecture" section describing top-level directories or service boundaries.
2. If missing or uninformative, read `README.md`.
3. If neither defines layers, infer from top-level directory structure: each significant directory containing application code is a candidate layer.

A **layer** is a distinct responsibility boundary where work is self-contained and sequenceable
independently. Categories to look for:
- **Frontend**: `vue/`, `react/`, `frontend/` — UI components, views, stores
- **Backend / API**: `api/`, `backend/`, `server/` — REST endpoints, controllers, business logic
- **Module engine / core**: `legacy/`, `modules/`, `core/` — data model, hooks, module structure
- **Service**: `auth-service/`, `payment-service/` — independent microservice boundaries

The subagent must include a **Layer Assessment** section in its report:

```
## Layer Assessment

Discovered layers (from CLAUDE.md / README / directory structure):
- `{layer_key}` ({Layer Name}): {one-line role description}

Layers affected by this US:
- `{layer_key}`: {minimal / moderate / significant} — {what specifically changes}
- `{layer_key}`: not affected
```

`{layer_key}` — short lowercase, filesystem-safe identifier used for the sub-plan filename
(e.g. `frontend`, `api`, `core`, `auth-service`; spaces → hyphens; no special chars).
`{Layer Name}` — human-readable display name (e.g. `Frontend`, `API`, `Core Engine`).

If no architecture docs exist: flag it — "No architecture documentation found — layers inferred
from directory structure. Please confirm before writing the plan."

### Step 8 — Create feature branch

```bash
git checkout master          # base branch per CLAUDE.md → "Git Workflow" section
git pull
git checkout -b feature/{ISSUE_ID}
```

If branch already exists → switch to it: `git checkout feature/{ISSUE_ID}`

### Step 8a — Assess complexity and choose plan structure

Based on the exploration report from Step 7, decide whether to create a simple or hierarchical plan.

**Create a hierarchical plan if:**
- The US touches **2 or more architectural layers** (as identified in the Layer Assessment from Step 7) with meaningful work in each, OR
- The total projected implementation steps exceed **15**

**Keep a simple plan if:**
- Only one layer is affected (even if it has many steps)
- The US is purely configuration with no significant code changes across multiple layers

Inform the user of the decision:

```
Plan structure: hierarchical
Reason: US touches {Layer Name 1} ({brief scope}) + {Layer Name 2} ({brief scope})
Layers: {Layer Name 1}, {Layer Name 2}[, ...]
```

or:

```
Plan structure: simple
Reason: only {layer_key} layer affected
```

No user confirmation needed — continue to Step 9 immediately.

### Step 9 — Write the plan

Create directory:

```bash
mkdir -p .ai/tasks/{ISSUE_ID}
```

---

#### If simple plan

File path: `.ai/tasks/{ISSUE_ID}/plan.md`

**Important:** The heading `# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}` must contain the exact
**ISSUE_SUBJECT** from Redmine. The `implementation` skill extracts ISSUE_SUBJECT from this
heading and uses it in the commit message.

```markdown
# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}

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

---

#### If hierarchical plan

Create `plan.md` (master hub) + one sub-plan per affected layer.

**Important:** Both master and sub-plan headings must contain the exact **ISSUE_SUBJECT** from
Redmine. The `implementation` skill extracts ISSUE_SUBJECT from the H1 heading of whichever
plan file it opens as the working document for the session.

##### Master plan — `plan.md`

```markdown
# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}

**Redmine:** https://redmine.evolpe.net/issues/{ISSUE_ID}
**Sprint:** {sprint}
**Plan date:** {YYYY-MM-DD}
**Type:** Hierarchical plan

## Requirements understanding

{Paraphrase of what the full US requires — in your own words.}

## Acceptance criteria

{Full list from US}

## Architecture overview

This US spans {N} layers. Recommended implementation order:
1. {Layer Name 1} (`plan_{layer_key_1}.md`) — {one-liner: what changes here}
2. {Layer Name 2} (`plan_{layer_key_2}.md`) — {one-liner} [depends on: {Layer Name 1}]
[...one entry per affected layer, in dependency order]

## Sub-plans

| Layer           | File                    | Status       | Summary   |
|-----------------|-------------------------|--------------|-----------|
| {Layer Name 1}  | plan_{layer_key_1}.md   | ⬜ pending   | {brief}   |
| {Layer Name 2}  | plan_{layer_key_2}.md   | ⬜ pending   | {brief}   |
[...one row per affected layer — omit unaffected layers]

## Cross-layer risks and notes

{Dependencies, potential conflicts, shared data structures between layers.
 Omit if no cross-layer concerns.}

## Open questions

{Questions requiring clarification. Omit if everything is clear.}
```

Only include rows in the Sub-plans table for layers that actually have work.
`{layer_key}` values come from the Layer Assessment in Step 7. Use short lowercase identifiers.

##### Sub-plan — `plan_{layer_key}.md`

Where `{layer_key}` is from the Layer Assessment (e.g. `plan_frontend.md`, `plan_api.md`,
`plan_core.md`). Create one sub-plan per affected layer. `{layer_key}` must be filesystem-safe:
lowercase, spaces → hyphens (e.g. `auth-service`, `business-logic`).

```markdown
# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT} [{Layer Name} layer]

**Part of:** [plan.md](plan.md)
**Depends on:** {Preceding Layer Name} layer must be complete first — or "No dependencies" if this is the first layer.

## Context

{Brief: what does this layer's work achieve in the context of the full US.
 A developer reading only this sub-plan must understand why this work is needed.}

## Acceptance criteria (this layer)

{Only the AC items relevant to this layer}

## Implementation approach

{Technical description for this layer, with references to specific files from Step 7 exploration.}

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| ... | CREATE/MODIFY/DELETE | ... |

## Implementation plan

- [ ] [P] Step 1: ...
- [ ] [P] Step 2: ...
- [ ] **[CHECKPOINT]** Verification: ...
- [ ] Step 3: ...

## Notes

{Layer-specific risks, edge cases, or open issues. Omit if none.}
```

Each sub-plan must be **self-contained** — a developer (or agent) should be able to implement
it by reading only the master summary + this sub-plan, without reading the other sub-plans.

---

### Step 10 — Summary and plan commit

**If simple plan**, display:

```
Plan for #{ISSUE_ID} ready.

Branch:  feature/{ISSUE_ID}
Plan:    .ai/tasks/{ISSUE_ID}/plan.md
Steps:   {N}
Risks:   {list if any, "none" if not}

Should I commit the plan?
```

**If hierarchical plan**, display:

```
Plan for #{ISSUE_ID} ready.

Branch:  feature/{ISSUE_ID}
Plans:
  .ai/tasks/{ISSUE_ID}/plan.md                    ← master
  .ai/tasks/{ISSUE_ID}/plan_{layer_key_1}.md      ← {Layer Name 1} layer ({N} steps)
  .ai/tasks/{ISSUE_ID}/plan_{layer_key_2}.md      ← {Layer Name 2} layer ({N} steps)
  [...one line per affected layer]
Total steps: {N}
Risks:   {list if any, "none" if not}

Should I commit the plan?
```

After user confirmation:

**Simple:**
```bash
git add .ai/tasks/{ISSUE_ID}/plan.md
git commit -m "plan - ref #{ISSUE_ID}"
```

**Hierarchical:**
```bash
git add .ai/tasks/{ISSUE_ID}/plan.md
git add .ai/tasks/{ISSUE_ID}/plan_*.md
git commit -m "plan - ref #{ISSUE_ID}"
```

Display commit hash. Do not push without asking.

---

## Expected working style

- **Explore before writing the plan** — don't guess code structure, use Step 7
- **Paraphrase requirements** — don't copy the US description, show that you understand the intent
- **Ask about ambiguities** — open questions in the plan are better than silent assumptions
- **Don't commit without consent** — always wait for confirmation before Step 10 commit
- **One commit** — only plan files, no code whatsoever
- **Hierarchical when it matters** — split only when 2+ layers have meaningful work; don't split artificially
- **Layers come from the project, not from you** — always derive layer names from the project's `CLAUDE.md` or `README.md` via the Step 7 subagent. Never invent layer names or default to `legacy/api/vue` on projects that don't use those names.
- **Sub-plans must be self-contained** — each sub-plan should make sense on its own without reading the others
- After completion, the user proceeds to implementation using the plan files as a checklist
