# Bug Diagnosis Flow — Steps B1–B9

This flow applies to: **User Story Bug (19), Błąd krytyczny (15), Błąd niekrytyczny (16)**.

Entry point: after Step 3 in `SKILL.md` routed here.

**Key difference from US flow:** domain skills (`minthcm-core`, `minthcm-project`) are NOT loaded
until the user confirms the root cause diagnosis. Planning only begins after that agreement.

---

## Step B1 — Root cause exploration (WITHOUT domain skills)

Do NOT load `minthcm-core`, `minthcm-project`, or any other domain skill at this point.

**Perform the exploration directly** using `Glob`, `Grep`, and `Read` tools. Do NOT delegate to a
subagent — bug diagnosis requires deep reasoning and chained reads across multiple files that only
work well in a single context.

**Exploration protocol (follow in order):**

1. **Orient** — identify the architectural layers involved (e.g. frontend, API, backend, DB). Use `CLAUDE.md` or project README to understand the project structure before searching.
2. **Locate the entry point** — find the file(s) most directly responsible for the reported behavior (e.g. the model, controller, component, or service named in the bug description).
3. **Read the core logic** — read the relevant file(s) in full or in the relevant section. Look for save/update handlers, field calculations, validation, and event hooks.
4. **Trace the data flow** — follow the data from where the user interacts (form, UI event) through to where it is persisted or returned. At each boundary (frontend → API → backend → DB), check what is sent, transformed, or dropped.
5. **Look for conditional logic** — identify any branches (e.g. `if request type == X`, feature flags, null checks) that could cause the bug to appear only in certain scenarios.
6. **Check extension/hook points** — look for lifecycle hooks, event listeners, or override files (e.g. custom/, plugins, middleware) that may intercept or modify the data.
7. **Find analogous patterns** — search for similar operations elsewhere in the codebase that work correctly; they reveal the expected approach and help spot the deviation.

At each step: **read actual file content**, not just file names. A hypothesis based on reading code
is worth ten based on search results alone.

After completing the exploration, form a **Diagnosis Report**:

```
## Diagnosis Report

**Hypothesis 1:** {one-sentence root cause}
- Evidence: `{file}:{line}` — {what it does wrong, with code snippet}
- Confidence: high / medium / low
- Reason: {why this confidence level}

**Hypothesis 2** *(if another plausible cause exists)*:
- Evidence: `{file}:{line}` — {explanation}
- Confidence: high / medium / low

**Recommended fix area:** {file or module}
```

---

## Step B2 — Present diagnosis and start the confirmation conversation

Present the diagnosis clearly and invite discussion:

```
## Root Cause Diagnosis — #{ISSUE_ID}: {title}

Based on the codebase analysis, I found the following likely root causes:

**1. {Hypothesis 1 in plain language}**
   - `{file}:{line}` — {explanation}
   - Confidence: {high/medium/low}

**2. {Hypothesis 2}** *(if exists)*
   - `{file}:{line}` — {explanation}
   - Confidence: {high/medium/low}

---
Czy zgadzasz się z tą diagnozą? Możesz zaakceptować wybrane punkty i doprecyzować pozostałe.
Na przykład: "Zgadzam się z 1, ale co do 2 — sprawdź też X."
```

---

## Step B3 — Diagnosis conversation loop

This is a **conversation**, not a binary yes/no gate. The user can:
- Accept some hypotheses and ask for deeper investigation on others
- Provide corrections or additional context for specific points
- Add new angles to investigate

**Handle each response:**

1. **Partial acceptance + correction:**
   - Note which hypotheses are accepted (lock them in)
   - Re-run exploration (Step B1) for the rejected/unclear points, passing:
     - The accepted hypotheses as context (don't re-investigate these)
     - The user's correction or additional direction
   - Update the diagnosis and return to Step B2 with only the remaining open points

2. **Full acceptance:**
   - Proceed to Step B4

3. **Full rejection + new direction:**
   - Discard previous hypotheses
   - Re-run Step B1 with the user's correction as primary context
   - Return to Step B2

**Keep full context between iterations** — each new exploration pass must include:
- Previously accepted hypotheses
- Previously rejected hypotheses and why they were rejected
- The user's latest correction

Continue iterating until the user accepts all open points. There is no limit on iterations —
the goal is genuine alignment, not speed.

---

## Step B4 — Select domain skills

Root cause is now confirmed. Select domain skills based on where the fix will live.

Review available skills in `.claude/skills/`:

| If the fix involves... | Load skill |
|---|---|
| Core module, field, entity, endpoint, MintLogic | `minthcm-core` |
| Customization, client logic, views, hooks | `minthcm-project` |
| Both core and customization | both |
| Skill change | `skill-creator` |

Display selected skills and **wait for confirmation** before loading:

| Skill | Reason |
|-------|--------|
| `minthcm-project` | Bug is in a custom view definition |
| ... | ... |

If no skills needed: "No additional skills required for this fix."

Load skills after user confirmation.

---

## Steps B5 + B6 — Parallel (after skills loaded)

**Launch Steps B5 and B6 in a single message as parallel tool calls.**

### Step B5 — Check repository state *(parallel)*

```bash
git fetch --all
git status
git branch -a | grep "{ISSUE_ID}"
```

If branch already exists — inform the user and ask whether to continue.

### Step B6 — Deep targeted exploration *(parallel)*

**Perform directly** using `Glob`, `Grep`, and `Read`. Do NOT delegate to a subagent.

Now that the root cause is confirmed and domain skills are loaded, do a focused read to fill in
implementation details. Use the confirmed hypotheses as a starting point — don't re-investigate
what's already known, focus on *how to fix*:

- Read the exact lines to modify and their surrounding context (±20 lines)
- Find similar patterns in the codebase that show the correct approach
- Check for potential side effects: other callers of the affected function, related fields, related modules
- Look for any existing tests or fixtures that should be updated

Produce a **Fix Exploration Report**:
- Exact files and line ranges to change
- Code snippet of what currently exists vs what it should become
- Pattern references (similar correct implementations in the codebase)
- Potential side effects to watch for

---

## Step B7 — Create feature branch

```bash
git checkout release/4.3.0   # for USB (19) — part of a US in a Wdrożenie project
git pull
git checkout -b feature/{ISSUE_ID}
```

> **For Błąd krytyczny (15) or Błąd niekrytyczny (16)** from a service project:
> these are typically hotfixes. Confirm with the user:
> "This is a {tracker_name}. Should I use `hotfix/{ISSUE_ID}` from `master`, or `feature/{ISSUE_ID}`
> from the current release branch?"

If branch already exists → switch to it.

---

## Step B8 — Assess complexity and choose plan structure

Bugs typically touch one layer and result in a simple plan. Create a hierarchical plan only if:
- The fix requires changes in **2 or more architectural layers** with meaningful work in each
- Total projected steps exceed **15**

Inform the user of the decision. No user confirmation needed — continue to Step B9 immediately.

---

## Step B9 — Write the plan

Create directory:

```bash
mkdir -p .ai/tasks/{ISSUE_ID}
```

**Important:** The heading `# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}` must contain the exact
**ISSUE_SUBJECT** from Redmine. The `implementation` skill extracts ISSUE_SUBJECT from this heading.

### Simple plan — `.ai/tasks/{ISSUE_ID}/plan.md`

```markdown
# Plan: #{ISSUE_ID} — {ISSUE_SUBJECT}

**Redmine:** https://redmine.evolpe.net/issues/{ISSUE_ID}
**Tracker:** {tracker_name}
**Sprint:** {sprint}
**Plan date:** {YYYY-MM-DD}

## Root Cause

{Confirmed diagnosis in plain language — what exactly causes the bug.}

**Location:** `{file}:{line}` — {brief explanation}

{If multiple root causes were confirmed, list each one.}

## Bug description

{Paraphrase of the reported bug — steps to reproduce, expected vs actual behavior.}

## Fix approach

{Technical description of how to fix the root cause.
 Reference exact files and lines from the B6 exploration.}

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| ... | MODIFY/CREATE/DELETE | ... |

## Implementation plan

Use `[P]` markers for steps that can be done in parallel and `[CHECKPOINT]` as verification points.

- [ ] [P] Step 1: ...
- [ ] [P] Step 2: ...
- [ ] **[CHECKPOINT]** Verification: {how to confirm the bug is fixed}
- [ ] Step 3: ...

## Risks and notes

{Potential regressions, side effects, related areas that could be affected. Omit if none.}
```

### Hierarchical plan

Follow the hierarchical plan format from `references/us-flow.md` (Step 9, "If hierarchical plan").
The only difference: add a **Root Cause** section to the master `plan.md`, directly after the
frontmatter block and before `## Requirements understanding`:

```markdown
## Root Cause

{Confirmed diagnosis in plain language — what exactly causes the bug.}

**Location:** `{file}:{line}` — {brief explanation}
```

---

## Step B10 — Summary and plan commit

Display:

```
Bug fix plan for #{ISSUE_ID} ready.

Branch:  feature/{ISSUE_ID}   (or hotfix/{ISSUE_ID})
Plan:    .ai/tasks/{ISSUE_ID}/plan.md
Root cause: {one-liner confirmed diagnosis}
Risks:   {list if any, "none" if not}

Should I commit the plan?
```

After user confirmation:

```bash
git add .ai/tasks/{ISSUE_ID}/plan.md
git commit -m "plan - ref #{ISSUE_ID}"
```

Display commit hash. Do not push without asking.
