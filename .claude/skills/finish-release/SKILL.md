---
name: finish-release
version: 1.0.0
description: >
  Finalize a release branch by merging it into master, updating the version file,
  and generating release notes from Redmine issues. Use this skill when the user says
  "finish release", "merge release to master", "finalizuj release", "zakoncz release",
  "zamknij release", "release notes", "merge release/X.Y.Z", or mentions a release branch
  in the context of finalizing/closing a release cycle. Also trigger when the user is on
  a release/* branch and asks to "close it", "finish it", or "push to master".
argument-hint: "[release/X.Y.Z]"
---

# Finish Release

Merge a release branch into master, verify the merge, update the version file, collect
Redmine issues from commits, and generate a release notes document.

---

## When to use

- User wants to finalize a release and merge it into `master`
- User says "finish release", "merge release", "release notes", "zamknij release"
- User is on a `release/*` branch and wants to close the release cycle

## When NOT to use

- Merging a feature branch into a release branch (use `merge-to-release`)
- Creating a new release branch
- Hotfix workflow

---

## Inputs

The skill needs one value:

- **RELEASE_BRANCH** — e.g. `release/4.3.1`

Extract from `$ARGUMENTS`, current branch (`git branch --show-current`), or ask the user.
Derive the **VERSION** from the branch name (e.g. `release/4.3.1` -> `4.3.1`).

---

## Procedure

### Step 1 — Preparation

```bash
git fetch --all
git log --oneline master..origin/{RELEASE_BRANCH} > /dev/null
```

Verify the release branch exists on remote and has commits ahead of master.

Show a summary:
```
Release: {RELEASE_BRANCH}
Version: {VERSION}
Commits ahead of master: {count}
```

Ask the user to confirm before proceeding.

### Step 2 — Merge release into master

```bash
git checkout master
git pull origin master
git merge --no-ff origin/{RELEASE_BRANCH} -m "Merge {RELEASE_BRANCH} into master — release {VERSION}"
```

If there are **merge conflicts**:
1. List all conflicted files
2. For each file, show the conflict markers and propose a resolution
3. Ask the user to confirm or adjust each resolution
4. After all conflicts resolved: `git add .` and `git commit`

### Step 3 — Syntax check

Run syntax checks on files changed in the merge:

**PHP files:**
```bash
git diff --name-only HEAD~1 HEAD -- '*.php' | xargs -I{} php -l {}
```

**JS/TS/Vue files** (if any changed):
Check for obvious issues. If `vue/node_modules` exists, consider running:
```bash
cd vue && npx vue-tsc --noEmit 2>&1 | head -50
```

Report any errors found. If there are syntax errors, help the user fix them before continuing.

### Step 4 — Update version file

Edit `legacy/minthcm_version.php`:
- Set `$minthcm_version` to the new VERSION (e.g. `'4.3.1'`)
- Set `$minthcm_timestamp` to the current date/time in format `'YYYY-MM-DD HH:MM:SS'`

Commit this change:
```bash
git add legacy/minthcm_version.php
git commit -m "Update version to {VERSION}"
```

### Step 5 — Collect commits and Redmine issues

Get all commits that were part of the release:
```bash
git log --oneline master@{1}..HEAD~1
```

This shows commits from the merge (excluding the merge commit itself and the version bump).

**Extract issue IDs** from commit messages using these patterns:
- `#NNNNN` in message text
- `feature/NNNNN` or `hotfix/NNNNN` in merge commit messages
- `ref #NNNNN` pattern

Deduplicate the issue IDs — multiple commits often reference the same issue.

**Fetch issue details from Redmine** using `mcp__redmine__redmine_request`:
```
GET /issues/{ISSUE_ID}.json?include=tracker
```

For each issue, note:
- `id`, `subject`, `tracker.name`, `description`

**Categorize issues:**

Start with tracker-based categorization, then apply security detection:

| Tracker | Default Category |
|---|---|
| User Story (id=18) | New Features |
| User Story Bug (id=19) | Bug Fixes |
| Epic (id=22) | New Features |
| Spike (id=23) | Internal / Technical |
| Task (id=24) | depends on context |
| Other | Other Changes |

**Security detection** — an issue is categorized as **Security** (overriding the default)
if any of these signals are present:
- Issue subject or description contains `[SEC]`, `security`, `SQL injection`,
  `XSS`, `CSRF`, `CVE-`, `vulnerability`, `auth bypass`, `privilege escalation`,
  `injection`, `sanitize`, `escape`, `exploit`
- Commit messages referencing the issue contain `[SEC]` or similar security keywords
- The issue is tagged with a security-related category in Redmine

This detection is case-insensitive. When in doubt, ask the user.

**Identify orphan commits** — commits that don't reference any Redmine issue.

If there are orphan commits, present them to the user:
```
The following commits don't reference a Redmine issue:
- {hash} {message}
- {hash} {message}

For each, should I:
(s) Skip — don't include in release notes
(f) Add as New Feature
(b) Add as Bug Fix
(x) Add as Security Fix
(t) Add as Technical / Internal change
```

Wait for the user's decision on each orphan commit.

### Step 6 — Generate release notes

Create the directory if needed:
```bash
mkdir -p ai/releaseNotes
```

Write `ai/releaseNotes/release-{VERSION}.md` with this structure:

```markdown
# MintHCM {VERSION} — Release Notes

**Release date:** {YYYY-MM-DD}

## New Features

- **#{ID} {Subject}** — {brief description from Redmine or commit messages}

## Security Fixes

- **#{ID} {Subject}** — {brief description of the vulnerability and fix}

## Bug Fixes

- **#{ID} {Subject}** — {brief description}

## Technical / Internal Changes

- **#{ID} {Subject}** — {brief description}
  (or orphan commits the user chose to include)

---

*Generated from {RELEASE_BRANCH} merge into master.*
```

**Language:** Release notes MUST be written entirely in English, regardless of the language
used in Redmine issues or commit messages. Translate Polish subjects and descriptions
to clear, concise English.

Skip empty sections. Use the Redmine issue subject as the basis, translated to English.
If the issue has useful detail in its description field, incorporate a one-line summary.

### Step 7 — Review key changes

Analyze the diff for significant changes that may need attention:

1. **New modules** — any new directories under `legacy/modules/` or `api/app/Entities/`
2. **API changes** — new or modified routes in `api/app/Routes/`
3. **Database schema** — changes to `vardefs.php`, new install scripts, migration files
4. **Config changes** — `.env` changes, new config entries, Docker changes
5. **Frontend changes** — new Vue components, field types, store changes
6. **Dependencies** — `composer.json` or `package.json` changes

Present findings:
```
## Key Changes to Review

### New/Modified Modules
- ...

### API Changes
- ...

### Schema Changes
- ...

### Config / Infrastructure
- ...
```

Then ask:
- "Do any of these changes require **documentation updates**?"
- "Should we add an **upgrade instructions** section to the release notes?"

If the user wants upgrade instructions, add a `## Upgrade Instructions` section to the release notes file.

### Step 8 — Push

Ask the user:
```
Everything is ready. Push master to origin?
(y) Yes, push
(n) No, I'll push manually later
```

If yes:
```bash
git push origin master
```

### Step 9 — Summary

```
## Release {VERSION} — Done

- Merged: {RELEASE_BRANCH} -> master
- Version file: updated to {VERSION}
- Syntax check: passed
- Release notes: ai/releaseNotes/release-{VERSION}.md
- Push: {yes/no}
- Issues included: {count} ({features} features, {bugs} bug fixes, {other} other)
```

---

## Important behaviors

- **Always confirm** before merge (Step 2) and push (Step 8) — these are irreversible
- **Don't skip orphan commits silently** — always ask the user what to do with them
- If Redmine MCP is unavailable, work with commit messages only and note that issue details couldn't be fetched
- The release notes file should be committed to the repo (add and commit after creating it)
- Keep the release notes concise — one line per issue, not paragraphs
