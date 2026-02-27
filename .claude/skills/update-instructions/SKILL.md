---
name: update-instructions
description: "Use this skill after coding sessions to update the AI instruction files. Triggers when the user says 'update instructions', 'zaktualizuj instrukcje', or after discovering new patterns, errors, or improvements that should be documented for future AI agents working on MintHCM."
---

# Update MintHCM AI Instructions

You are a specialized agent responsible for maintaining and improving instruction files for AI agents working on MintHCM. Your task is to review the current conversation and update relevant instruction files.

## Input

$ARGUMENTS

(If empty, analyze the full conversation history for changes worth documenting.)

## Procedure

### 1. Read All Existing Instructions

Read these files in parallel:
- `.github/copilot-instructions.md` — main instructions
- All files in `.github/instructions/` (00-17)
- `.github/prompts/updateInstructions.prompt.md` — change history

### 2. Analyze the Conversation

Review the full conversation to identify:
- New patterns discovered or confirmed
- Errors encountered and their solutions
- Corrections to existing instructions
- New best practices or anti-patterns
- Architectural decisions made
- Bugs that revealed missing guidance

### 3. Update Instruction Files

Based on analysis, update the relevant files in `.github/instructions/`:

**Which file to update:**
- New coding pattern → file matching topic (e.g., MintLogic → `12-mintlogic.instructions.md`)
- New Vue/frontend pattern → `04-frontend-vue.instructions.md` or `05-field-system.instructions.md`
- New PHP/backend pattern → `08-backend-php.instructions.md`
- New MintLogic error/fix → `12-mintlogic.instructions.md`
- New migration step → `17-legacy-migration.instructions.md`
- Cross-cutting concern → `copilot-instructions.md`

**Rules for editing:**
- Add new patterns to existing sections, don't create new files unless truly needed
- Keep instructions concise and actionable
- Use code examples for non-obvious patterns
- Remove or correct instructions that turned out to be wrong
- **NEVER add version numbers, dates, "Last Updated", or change logs to instruction files**
- **NEVER add metadata or comments about the change to instruction files**

### 4. Update Project Documentation

After updating instructions, check if corresponding user-facing documentation needs updating:
- `api/documentation/` — backend docs for developers
- `vue/documentation/` — frontend docs for developers

**IMPORTANT**: Documentation files are for real developers, not AI agents.
- Never reference `.github/instructions/` from documentation files
- Documentation should only cross-reference other documentation files

### 5. Update Change History

Add a new entry to the change history section in `.github/prompts/updateInstructions.prompt.md`:

```
**{YYYY-MM-DD}**: {Brief description of what changed and why.}
```

Add it at the top of the "## 📝 Change History" section (after the header).

## Quality Check

Before finishing, verify:
- [ ] Instructions are complete and actionable
- [ ] No version history or metadata added to instruction files
- [ ] No references to AI instructions from documentation files
- [ ] Change history entry added to updateInstructions.prompt.md
- [ ] No duplicate content introduced
- [ ] Language is consistent (English for all code and instructions)
