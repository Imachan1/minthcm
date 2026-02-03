# Developer Instructions - MintHCM

## 🚀 Implementation Flow with Copilot Instructions

### 1. Working with Copilot Agent

During implementation, pass commands to Copilot in agent mode, breaking down User Story into smaller parts:

- Adding a single field
- Creating a new module
- Implementing specific functionality
- Realizing a slice of User Story

**💡 Recommended model:** **Grok Code Fast 1** (does not consume premium request limit)

### 2. Directing to Standards

When Copilot performs tasks:

- Inform him about our coding standards
- Indicate what needs to be added or corrected
- Ensure that the implementation meets our requirements
- Refer to instructions in `.github/copilot-instructions.md` and `.github/instructions/`

### 3. Updating Instructions

After completing development, perform instructions update:

```
/updateInstructions
```

**🎯 Use better model:** Claude Sonnet 4.5

This command will:
- Update copilot instructions based on conversation history
- Update project documentation

### 4. Code Review - Uncommitted Changes

After completed implementation, perform action **"Code Review - Uncommitted changes"**:

![Code Review Uncommitted Changes](images/code_review_uncommited.png)

Copilot:
- Displays interface with correction proposals
- Allows immediate rejection or acceptance of changes
- Verifies code based on our instructions

### 5. Finalization

- Commit the task according to standards
- Commit changes in copilot instructions (if there were updates)

---

## 🔍 Code Review Flow (CRs)

### Step 1: Preparation

Copy the hash of the commit that requires code review.

### Step 2: Analysis with Copilot

In Copilot Chat execute:

```
Perform code review of commit: [commit-hash]
```

### Step 3: Code Review

- Conduct CR as usual, supporting yourself with Copilot's suggestions
- Pay attention to compliance with standards in `.github/copilot-instructions.md`
- If you find irregularities in instructions, update them

### Step 4: Updating Instructions

Ask Copilot Chat to introduce changes in copilot instructions:

```
Based on found problems, update copilot instructions to avoid such errors in the future
```

---

## 📁 Repository Structure

Instructions and prompts for Copilot are located in the `.github/` directory:

```
.github/
├── copilot-instructions.md      # Main file with instructions
│                                 # Rules for creating code (backend/frontend)
│                                 # Syntax and naming conventions
│
├── instructions/                 # Detailed thematic instructions
│   └── ...
└── prompts/                      # Files with prompts for Copilot
    └── ...
```

---

## 💡 Tips for Instructions and Prompts

### Using DBCode

If you need information about database structure:

- Use DBCode extension in VS Code
- Point to the database from which Copilot should retrieve structure
- Copilot will have access to current table schema

### Best Practices

1. **Be specific** - The more detailed instructions, the better results
2. **Provide examples** - Code examples in instructions help Copilot understand expectations
3. **Update regularly** - After each implementation and Code Review, update instructions

---

## 📚 Copilot and VS Code Documentation

### Official sources:

**Copilot Instructions:**
- [Adding instructions to repository](https://docs.github.com/en/copilot/how-tos/configure-custom-instructions/add-repository-instructions?tool=vscode)
- [Custom Instructions in VS Code](https://code.visualstudio.com/docs/copilot/customization/custom-instructions)

**Prompt Files:**
- [Prompt Files in VS Code](https://code.visualstudio.com/docs/copilot/customization/prompt-files)

**Code Review:**
- [Code Review with Copilot](https://docs.github.com/en/copilot/how-tos/use-copilot-agents/request-a-code-review/use-code-review)

---

## 🎯 Quick Start

1. **Familiarize yourself with instructions:**
   - Read `.github/copilot-instructions.md`
   - Browse files in `.github/instructions/`

2. **Configure environment:**
   - Install GitHub Copilot extension
   - Configure DBCode (optional)

3. **Start work:**
   - Use agent mode for implementation
   - Update instructions after each task
   - Conduct Code Review with Copilot's help

4. **Share knowledge:**
   - If you find useful patterns, add them to instructions
   - Help in developing the project's knowledge base

**Remember:** Copilot Instructions is a living document. The more we use it and develop it, the better results we achieve! 🚀
