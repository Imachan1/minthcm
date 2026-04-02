# US Flow — Steps 4–10

This flow applies to: **User Story (18), Epic (22), Spike (23), Mały rozwój (14)**.

Entry point: after Step 3 in `SKILL.md` routed here.

---

## Step 4 — Clarify ambiguities

Review the issue description and identify **all business ambiguities** — places where requirements
are imprecise, contradictory, or missing key context needed to plan correctly.

- If ambiguities exist — ask **all of them at once** and wait for answers before proceeding to Step 5.
- If everything is clear — skip this step and continue.

> Ask only about **business** things (what it should do, for whom, in what case, what the expected
> behavior is). Do not ask about technical implementation choices — those are your job to decide.

---

## Step 5 — Select skills

Review available skills in `.claude/skills/` (each has a `SKILL.md` with a description in the
`description` frontmatter). Based on the issue scope and clarified requirements, decide which will
be needed during exploration and planning.

Example criteria:

| If the issue involves... | Load skill |
|---|---|
| New module, field, endpoint, entity, MintLogic in core | `minthcm-core` |
| Customization, client logic, subpanels, listviewdefs, hooks | `minthcm-project` |
| Both core and customization | both of the above |
| New skill / skill change | `skill-creator` |

> If no skill fits the scope — skip this step.

Display a table of selected skills to the user and **wait for confirmation** before proceeding:

| Skill | Reason for selection |
|-------|----------------------|
| `minthcm-core` | Issue requires adding a new field/entity/endpoint in core |
| ... | ... |

If no skills were selected, write: "No additional skills required for this issue."

Load selected skills (read their SKILL.md) **after user confirms** and before Step 6.

---

## Steps 6 + 7 — Parallel execution

**After Step 5 (skills confirmed and loaded), launch Steps 6 and 7 in a single message as parallel tool calls.**

---

## Step 6 — Check repository state *(parallel)*

```bash
git fetch --all
git status
git branch -a | grep "{ISSUE_ID}"
```

Check if the feature branch already exists. If so — inform the user and ask whether to continue
(it may be re-planning after a scope change).

---

## Step 7 — Explore codebase *(parallel, Explore subagent)*

**Use the Agent tool with `subagent_type=Explore` and `model=haiku`.**

Pass the full issue description and requirements in the prompt so the subagent has complete context.
The subagent should answer:
- Which modules/files are likely affected?
- Are there similar implementations to model after?
- What dependencies might be relevant?
- **Which architectural layers** does this issue touch, and how significantly?

The subagent must **not modify anything** — only `Glob`, `Grep`, `Read`.

The subagent should return a **structured report** with:
- List of relevant files with paths and line numbers
- Pattern references (e.g. "similar field defined in `legacy/modules/Employees/vardefs.php:42`")
- Any architectural observations relevant to implementation
- **Layer assessment**: which layers are affected and estimated scope of work in each

> Do not use `Glob`/`Grep`/`Read` directly in the main conversation for exploration — delegate
> entirely to the subagent to keep the main context clean.

### Layer identification (for the explore subagent)

As the **first action**, read the project's architecture documentation:
1. Read `CLAUDE.md` (repo root or `.claude/`) — look for an "Architecture" section.
2. If missing or uninformative, read `README.md`.
3. If neither defines layers, infer from top-level directory structure.

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

Layers affected by this issue:
- `{layer_key}`: {minimal / moderate / significant} — {what specifically changes}
- `{layer_key}`: not affected
```

`{layer_key}` — short lowercase, filesystem-safe identifier (e.g. `frontend`, `api`, `core`).
`{Layer Name}` — human-readable display name (e.g. `Frontend`, `API`, `Core Engine`).

---

## Step 8 — Create feature branch

```bash
git checkout release/4.3.0   # base branch per CLAUDE.md → "Git Workflow" section
git pull
git checkout -b feature/{ISSUE_ID}
```

If branch already exists → switch to it: `git checkout feature/{ISSUE_ID}`

---

## Step 8a — Assess complexity and choose plan structure

Based on the exploration report from Step 7, decide whether to create a simple or hierarchical plan.

**Create a hierarchical plan if:**
- The issue touches **2 or more architectural layers** with meaningful work in each, OR
- The total projected implementation steps exceed **15**

**Keep a simple plan if:**
- Only one layer is affected
- Purely configuration with no significant code changes across multiple layers

Inform the user of the decision:

```
Plan structure: hierarchical
Reason: issue touches {Layer Name 1} ({brief scope}) + {Layer Name 2} ({brief scope})
Layers: {Layer Name 1}, {Layer Name 2}[, ...]
```

or:

```
Plan structure: simple
Reason: only {layer_key} layer affected
```

No user confirmation needed — continue to Step 9 immediately.

---

## Step 9 — Write the plan

Create directory:

```bash
mkdir -p .ai/tasks/{ISSUE_ID}
```

### If simple plan

File path: `.ai/tasks/{ISSUE_ID}/plan.md`

**Important:** The heading `# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}` must contain the exact
**ISSUE_SUBJECT** from Redmine. The `implementation` skill extracts ISSUE_SUBJECT from this heading.

```markdown
# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}

**Redmine:** https://redmine.evolpe.net/issues/{ISSUE_ID}
**Sprint:** {sprint}
**Plan date:** {YYYY-MM-DD}

## Requirements understanding

{Paraphrase of what the issue requires — in your own words, don't copy the description 1:1.
 Show that you understand the intent, not just the letter of the requirements.}

## Acceptance criteria

{List from issue — rewrite or supplement if unclear}

## Implementation approach

{Technical description of how to fulfill the requirements — architecture, patterns, tools.
 Reference specific code locations found in Step 7.}

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| ... | CREATE/MODIFY/DELETE | ... |

## Implementation plan

Use `[P]` markers for steps that can be done in parallel and `[CHECKPOINT]` as verification points.

- [ ] [P] Step 1: ...
- [ ] [P] Step 2: ...
- [ ] **[CHECKPOINT]** Verification: ...
- [ ] Step 3: ...

## Risks and notes

{Potential issues, dependencies on other issues, side effects. Omit if no risks.}

## Open questions

{Questions requiring clarification. Omit if everything is clear.}
```

---

### If hierarchical plan

Create `plan.md` (master hub) + one sub-plan per affected layer.

**Important:** Both master and sub-plan headings must contain the exact **ISSUE_SUBJECT** from Redmine.

#### Master plan — `plan.md`

```markdown
# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}

**Redmine:** https://redmine.evolpe.net/issues/{ISSUE_ID}
**Sprint:** {sprint}
**Plan date:** {YYYY-MM-DD}
**Type:** Hierarchical plan

## Requirements understanding

{Paraphrase of what the full issue requires — in your own words.}

## Acceptance criteria

{Full list from issue}

## Architecture overview

This issue spans {N} layers. Recommended implementation order:
1. {Layer Name 1} (`plan_{layer_key_1}.md`) — {one-liner: what changes here}
2. {Layer Name 2} (`plan_{layer_key_2}.md`) — {one-liner} [depends on: {Layer Name 1}]

## Sub-plans

| Layer           | File                    | Status       | Summary   |
|-----------------|-------------------------|--------------|-----------|
| {Layer Name 1}  | plan_{layer_key_1}.md   | ⬜ pending   | {brief}   |
| {Layer Name 2}  | plan_{layer_key_2}.md   | ⬜ pending   | {brief}   |

## Cross-layer risks and notes

{Dependencies, potential conflicts, shared data structures. Omit if none.}

## Open questions

{Questions requiring clarification. Omit if everything is clear.}
```

#### Sub-plan — `plan_{layer_key}.md`

```markdown
# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT} [{Layer Name} layer]

**Part of:** [plan.md](plan.md)
**Depends on:** {Preceding Layer Name} layer must be complete first — or "No dependencies".

## Context

{Brief: what does this layer's work achieve in the context of the full issue.
 A developer reading only this sub-plan must understand why this work is needed.}

## Acceptance criteria (this layer)

{Only the AC items relevant to this layer}

## Implementation approach

{Technical description for this layer, with references to specific files from Step 7.}

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

Each sub-plan must be **self-contained** — a developer should be able to implement it by reading
only the master summary + this sub-plan, without reading the other sub-plans.

---

## Step 10 — Summary and plan commit

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
