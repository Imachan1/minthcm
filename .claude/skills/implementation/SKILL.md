---
name: implementation
version: 1.3.0
description: Executes implementation plans created by plan-us. Trigger on: "implement", "execute the plan", "zaimplementuj", "kontynuuj implementację", "wznów implementację", "implement #XXXX", "zaimplementuj US #XXXX", "dokończ implementację", "start coding for US #XXXX", "let's implement this", "let's code this up", "can you build what's in the plan", "execute plan.md", or any issue number after a plan already exists. If no plan exists yet, tell the user to run plan-us first.
argument-hint: <issue_id> or <plan_path> (e.g. 184819 or .ai/tasks/184819/plan.md)
---

# Implementation

Executes an implementation plan produced by `plan-us`, step by step. Keeps `plan.md` as the
single source of truth — updating step statuses in real time so that work can be interrupted
and resumed at any point, by the same or a different agent.

---

## When to use

- User asks to implement, execute, or continue a plan created by `plan-us`
- User says "zaimplementuj plan", "implement #184819", "kontynuuj implementację"
- User references a specific `.ai/tasks/{ISSUE_ID}/plan.md` file to execute
- User says "start coding" or "let's implement this" after planning is complete
- User wants to resume interrupted implementation

## When NOT to use

- **No plan exists yet** — tell the user: "Plan for #{ISSUE_ID} doesn't exist. Run `plan-us` first."
- User only asks about the plan contents without wanting to implement (just reading/reviewing)
- User wants to create or modify the plan itself (use `plan-us`)
- User asks for code review (use `code-review`)

---

## Process overview

```
1. Locate plan       → find .ai/tasks/{ISSUE_ID}/plan.md
2. Load skills       → select and load domain skills based on plan scope
3. Detect resume     → git diff + status markers → find where to continue
4. Execute steps     → sequential or parallel ([P]), updating markers live
5. Checkpoints       → auto-verify work, fix issues, continue (no user confirmation)
6. Commit            → single commit after full implementation, with user consent
```

## Table of Contents

- [Step 1 — Locate the plan](#step-1--locate-the-plan)
- [Step 2 — Load domain skills](#step-2--load-domain-skills)
- [Step 3 — Detect resume point](#step-3--detect-resume-point)
- [Step 4 — Execute implementation steps](#step-4--execute-implementation-steps)
- [Step 5 — Update plan with implementation notes](#step-5--update-plan-with-implementation-notes)
- [Step 6 — Commit](#step-6--commit)
- [Expected working style](#expected-working-style)

---

## Step 1 — Locate the plan

Extract `ISSUE_ID` from arguments or conversation context:
- Number (`184819`) → look for `.ai/tasks/184819/plan.md`
- Path (`.ai/tasks/184819/plan.md`) → extract `ISSUE_ID` from path
- Issue URL (`https://redmine.evolpe.net/issues/184819`) → extract number
- Not provided → ask: *"Which US should I implement? Provide the issue number."*

If the plan file doesn't exist → stop: *"Plan for #{ISSUE_ID} not found at `.ai/tasks/{ISSUE_ID}/plan.md`. Run `plan-us` first."*

Read `plan.md` and **detect plan type**:

### 1a. Hierarchical plan

A plan is **hierarchical** when `plan.md` contains a "Sub-plans" table (a markdown table with
links to files matching `plan_*.md`) AND at least one file matching that pattern exists on disk.

**If hierarchical:**

1. Display layer status (read the Status column from the Sub-plans table):
   ```
   US #184819 — hierarchical plan detected.

   Layers:
     ⬜ Legacy   — plan_legacy.md  (pending)
     ✅ API      — plan_api.md     (done)
     ⬜ Vue      — plan_vue.md     (pending)
   ```

2. Determine the target sub-plan:
   - If the user specified a layer (e.g. "implement the API part", "zaimplementuj warstwę Vue")
     → use that sub-plan
   - Otherwise → auto-select: the first sub-plan where status ≠ ✅ done/gotowe, in the order
     declared in the master plan's "Architecture overview" section (respect declared dependencies)

3. Load context — read **only**:
   - From `plan.md`: the header block + "Requirements understanding" section +
     "Architecture overview" section + "Sub-plans" table.
     **Skip** the detailed implementation steps of other layers — they are not needed and
     would unnecessarily consume context.
   - The target sub-plan **in full** as the working document for this session.

4. Extract from the **master plan's** H1 heading:
   - **ISSUE_SUBJECT** — from `# Plan: #XXXXX — {ISSUE_SUBJECT}` (use the master plan heading,
     **not** the sub-plan heading which includes a `[layer]` suffix — the commit message should
     describe the full US, not a single layer)

5. The **sub-plan becomes the single source of truth** for this session. All step markers
   (`[~]`, `[x]`, `[!]`) are updated in the sub-plan file, not in `plan.md`.

6. After all steps in the sub-plan are marked `[x]`, before proceeding to Step 6 (Commit):
   update the master `plan.md` Sub-plans table — change the layer's status from `⬜ pending`
   to `✅ done`.

### 1b. Simple (regular) plan

If no sub-plan files are detected, proceed with `plan.md` as the single working document.

Read the plan and extract:
- **ISSUE_SUBJECT** — from the plan's `# Plan: #XXXXX — ...` heading
- **Implementation steps** — from the "Implementation plan" / "Plan implementacji" section
- **Files to modify/create** — from the files table
- **Developer notes** — any inline notes or decision updates added during implementation

If the plan file exists but has no "Implementation plan" / "Plan implementacji" section or contains no `- [ ]` steps → warn:
*"Plan for #{ISSUE_ID} exists but contains no implementation steps. Check the plan format or re-run `plan-us`."* Then stop.

### 1c. Open questions

If the plan (or the active sub-plan) contains an "Open questions" section:

1. **Check if the plan already provides working assumptions** — plans generated by `plan-us` often
   document open questions alongside inline assumptions (e.g., *"max_level is a constant 5 pending
   clarification"*). If a working assumption exists, proceed with it — do not stop to ask.

2. **If no assumption is stated and the question blocks implementation** (e.g., you cannot write
   the code without knowing the answer), inform the user and ask before proceeding:

   > ⚠️ Open question blocks implementation:
   > *"{question text}"*
   > How should I proceed?

3. **If the question is non-blocking** (e.g., a future enhancement consideration, or a "nice to
   have" clarification that doesn't affect the current steps), note it in the resume summary and
   continue with implementation.

---

## Step 2 — Load domain skills

Review available skills in `.claude/skills/` and select those relevant to the implementation
scope (based on what the plan describes). Load them immediately — no need to pause for user
confirmation since the selection is driven directly by the plan content.

After loading, display a brief informational summary:

```
Skills loaded:
  ✓ minthcm-project — plan involves custom hooks and layout changes
```

**If no system/domain skill is found** (e.g., no `minthcm-project`, `spicecrm-project`, or
similar per-system skill matches the codebase), inform the user clearly before continuing:

> ⚠️ **No domain skill found** for the implemented system. Proceeding without a
> system-specific skill — implementation will rely on general coding patterns. For better
> accuracy, consider creating a domain skill for this system.

Continue with implementation regardless — a missing domain skill is not a blocker.

If no domain skills are needed at all, state: "No additional skills required." and proceed.

---

## Step 3 — Detect resume point

Before executing anything, assess the current state by combining two signals:

### 3a. Read status markers in plan.md

Scan the implementation steps for their markers:
- `- [x]` — completed
- `- [~]` — in progress (interrupted)
- `- [!]` — failed
- `- [ ]` — not started

### 3b. Run git diff

**Always run these as actual bash commands — do not simulate or skip:**

```bash
git diff --stat HEAD
git diff --name-only
```

This gives the ground truth about what files were actually changed, regardless of what
`plan.md` says. It protects against stale marker states (e.g., a step marked `[ ]` whose
file was already modified by another agent).

**Required**: These must be real tool invocations. Visual inspection of file contents alone
is not sufficient — `git diff` captures staged and unstaged changes that may not be visible
from reading files.

### 3c. Determine where to continue

Apply this logic in order:

1. **Steps marked `[~]`** — these were interrupted mid-execution. For each one, compare
   `git diff` output against what the step was supposed to produce. If the work is complete,
   mark `[x]` and move on. If partial, resume from where it left off.

2. **Steps marked `[!]`** — failed steps. Inform the user about the failure (quote the
   failure reason from the note near the marker), run `git diff` to assess current state,
   then automatically retry the step **exactly once**.
   - If the retry **succeeds**: mark `[x]` and continue with remaining steps.
   - If the retry **fails again**: mark `[!]` (update the failure note), **stop immediately**,
     and ask the user for guidance. Do **not** attempt a third retry under any circumstances.

3. **If no `[~]` or `[!]` exist** — find the first `[ ]` step and start there.

4. **All steps `[x]`** — implementation is complete. Inform the user and proceed to commit
   (Step 6).

Display a resume summary:

```
Implementation status for #184819:
  ✓ 4 steps completed
  ~ 1 step in progress (partially done)
  · 3 steps remaining

Resuming from: "mcp/Auth/Services/InternalTokenService.php — create class..."
```

---

## Step 4 — Execute implementation steps

Work through steps in the order defined by the plan, respecting phase boundaries.

### Status marker protocol

**Before starting a step:** write its marker in `plan.md` from `[ ]` to `[~]` — use the
file edit tool to make this change, not just note it mentally.
**After completing a step:** write its marker from `[~]` to `[x]` — again, a real file edit.
**If a step fails:** write `[!]`, inform the user, and stop.

Update `plan.md` immediately after each status change — this is what enables safe
interruption and resume. **These must be actual writes to the file**, not mental state
tracking. Another agent reading the file mid-execution must see the current marker.

### Sequential steps

Execute them one by one. For each step:
1. Write `[~]` in plan.md (file edit)
2. Read the relevant files (from the plan's file table)
3. Implement the change
4. Write `[x]` in plan.md (file edit)

### Parallel steps `[P]`

Steps marked `[P]` within the same phase can be executed in parallel using subagents.

**Before launching parallel steps, check for file conflicts**: review the files each step
touches (from the plan's file table). If two or more parallel steps modify the same file,
execute those conflicting steps sequentially instead — concurrent writes to the same file
will produce conflicts or silently overwrite each other's changes. Only run steps in parallel
when their file sets are fully disjoint.

Before launching:
1. Mark **all** parallel steps as `[~]` in `plan.md` in a single update
2. Launch a subagent for each step

**Context for each subagent** — pass only what's needed for that specific step, not the
entire plan:

- The step description (exactly as written in the plan)
- Relevant rows from the "Files to modify/create" table
- Code snippets or file contents directly related to the step
- Loaded skill instructions if relevant to the step's scope
- Clear instruction: *"Execute only this step. Do not modify files outside its scope."*

After all subagents complete:
- Review each result
- Mark successful steps as `[x]`
- Mark failed steps as `[!]`
- If any step failed → stop and inform the user

### Handling `[CHECKPOINT]`

When the plan contains a `[CHECKPOINT]` line, the agent verifies autonomously — no user
confirmation needed. Checkpoints also act as **phase boundaries**: don't start steps after
a checkpoint until all steps before it pass verification.

1. **Self-verify** — check that the work done so far is consistent:
   - Run `git diff --stat` to confirm expected files were modified
   - Read modified files to verify changes match the plan's intent
   - **Run syntax checks as actual bash commands** on every modified file:
     - PHP: `php -l <file>` for each `.php` file
     - JavaScript/TypeScript: `node --check <file>` or `npx tsc --noEmit` if available
     - JSON: `python3 -c "import json; json.load(open('<file>'))"` for `.json` files
   - Run tests if a test runner is configured (look for `composer test`, `npm test`, `phpunit`)

   **Important**: Syntax checks must be executed as real bash commands, not inferred from
   reading the file content. A file can look syntactically correct on visual inspection but
   still fail `php -l`.

2. **If issues are found** — fix them immediately without asking the user. Re-run the full
   verification suite after fixing. If a fix fails after two attempts, mark the step `[!]`
   and inform the user.

3. **Display a brief verification summary** and continue to the next phase:

```
[CHECKPOINT] Phase 1 — OK
  ✓ mcp/.htaccess — RewriteRule added
  ✓ mcp/Config/Config.php — getInternalSecret() method added (php -l OK)
  Continuing to Phase 2...
```

---

## Step 5 — Update plan with implementation notes

`plan.md` is a living document. During implementation, update it when:

- A technical decision deviates from the original plan (add a note explaining why)
- A new dependency or risk is discovered
- The user provides new instructions that change the scope
- A step requires a different approach than originally planned

Keep notes inline near the relevant step or in the "Risks and notes" section.

### Discovering missing work

During implementation you may discover that the plan is incomplete — for example, a
relationship requires link fields on the parent module, or a new file is needed that wasn't
listed. This is normal and expected.

When you discover missing work:

1. **Add the new file** to the "Files to modify / create" table in `plan.md`
2. **Correct any wrong actions** (e.g., the plan says MODIFY but the file doesn't exist yet → change to CREATE)
3. **Add a brief note** next to the relevant step explaining the discovery
4. **Do the work** — don't stop to ask the user unless the scope change is significant (e.g., adds a new module, changes architecture)

This keeps the plan accurate as a post-hoc record of what was actually done, which helps
future agents and developers understand the implementation.

---

## Step 6 — Commit

Commit only after **all** implementation steps are marked `[x]` and **only with user
consent**.

### Commit flow

1. Display a summary:

```
Implementation of #184819 complete.

Files changed:
  M mcp/.htaccess
  M mcp/Auth/OAuthEndpoints.php
  A mcp/Auth/Services/InternalTokenService.php
  M mcp/Config/Config.php
  ...

Ready to commit. Who should review this?
(e.g. "Aleksander Bąk" or "aleksander.bak")
```

2. **Always ask for the reviewer login.** The user may provide the name in different formats:
   - `imie.nazwisko` login (e.g. `aleksander.bak`) → use directly
   - Full name with spaces (e.g. `Aleksander Bąk`) → normalize: lowercase, replace Polish
     diacritics (ą→a, ć→c, ę→e, ł→l, ń→n, ó→o, ś→s, ź→z, ż→z), replace space with dot
     → `aleksander.bak`
   - Display the normalized login for user confirmation: *"Reviewer: aleksander.bak — correct?"*

   **After confirmation**, commit with the reviewer:
   ```bash
   git add -A
   git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT} #CR|{REVIEWER_LOGIN}"
   ```

   If the user **declines, provides something unrecognizable** (single word, nickname, unclear
   string that cannot be turned into a `name.surname` login), or **explicitly skips** — commit
   without the login suffix:
   ```bash
   git add -A
   git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT} #CR"
   ```

3. **Do not push** without explicit request from the user.

### Exception: early commit

If the user explicitly asks to commit mid-implementation, comply — but use the same format
and reviewer flow. Note in `plan.md` that a partial commit was made.

---

## Expected working style

- **plan.md is the single source of truth** — update it immediately on every status change.
  Another agent or the user may read it mid-execution; stale markers lead to duplicate work
  or skipped steps.
- **Don't modify plan structure** unless a new technical decision or scope change emerges —
  the plan is a contract with the user and should stay stable so they can follow along.
- **Don't push without asking** — pushing affects the shared remote branch and may trigger
  CI pipelines or notify reviewers before the work is ready. Committing locally is safe;
  pushing is a shared, visible action.
- **One commit** at the end of full implementation — this keeps history clean and makes code
  review easier. If the user asks for an early commit, comply but note it in `plan.md`.
- **Respect phase boundaries** — a `[CHECKPOINT]` separates phases because later steps may
  depend on earlier ones being correct. Starting Phase 2 before Phase 1 is verified risks
  compounding errors that become harder to untangle.
- **Pass minimal context to subagents** — they don't need the full plan, only their specific
  step. Excess context bloats token usage and increases the risk of subagents drifting
  outside their scope.
- **Inform, don't assume** — when encountering genuine ambiguity in requirements, ask the
  user. Silent assumptions tend to produce work that must be redone.
- **Amend the plan when reality differs** — if you discover missing files, wrong action
  types, or new dependencies, update `plan.md` to reflect reality. The plan is also a
  post-implementation record that future developers will read.
- **Verify before moving on** — at every checkpoint, run concrete syntax checks as real bash
  commands, not visual inspection. A file can look correct but still fail `php -l`.
