---
name: coding-standards
version: 1.0.0
description: |
  Skill for verifying and applying coding standards. Use whenever you are writing new code, doing a code review, refactoring, or have a question about naming, function structure, class design, or code organization. Applies to PHP, JavaScript, C# and Python. Also trigger for questions like: "is this name OK?", "how should I name this class?", "is this function too long?", "how should I split this code?".
---

# Coding Standards

This skill contains the coding standards — semantics, naming conventions, function and class design, and code quality rules. Applies to PHP, JavaScript, C# and Python.

## When to use

- writing new code and want to follow standards from the start,
- doing a code review and assessing code quality,
- refactoring existing code,
- have a question about naming conventions, function size, or class structure,
- want to verify that code complies with standards.
- questions about CI/CD, deployment, infrastructure.

---

## Naming rules

### General (language-agnostic)

**Communicate intent** — a name should say what the variable, function or class does. If after writing a name you feel you need to add a comment explaining its purpose — that's a sign the name is too weak. Choose a better one.

**Don't disinform** — when the purpose or scope of an object changes, change its name too. An outdated name is worse than no name — it actively misleads.

**Pronounceable names** — developers talk about code. A name that can't be pronounced hinders team communication.

**No type encoding** — don't use Hungarian notation (`strName`, `iCount`, `bFlag`). IDEs show types; encoding them in the name is just noise.

**Names from the problem domain** — where possible, use business language. `Customer` and `Invoice` say more than `DataObject1` and `Processor`.

### Naming conventions per language

Detailed examples and edge cases per language: [PHP](references/naming-php.md) · [JavaScript/TypeScript](references/naming-javascript.md) · [C#](references/naming-csharp.md) · [Python](references/naming-python.md)

Quick reference table:

| Construct        | [PHP](references/naming-php.md) | [JavaScript](references/naming-javascript.md) | [C#](references/naming-csharp.md) | [Python](references/naming-python.md) |
|------------------|---------------------------------|-----------------------------------------------|-----------------------------------|---------------------------------------|
| Local variable   | `$my_variable`           | `myVariable`       | `myVariable`        | `my_variable`   |
| Class field      | `my_field`               | `myField`          | `_myField`          | `my_field`      |
| Public property  | `my_field`               | `myField`          | `MyProperty`        | `my_property`   |
| Function/method  | `myFunction()`           | `myFunction()`     | `MyMethod()`        | `my_function()` |
| Class            | `MyClass`                | `MyClass`          | `MyClass`           | `MyClass`       |
| Interface        | `MyInterface`            | `MyInterface` (TS) | `IMyInterface`      | —               |
| Constant         | `MY_CONSTANT`            | `MY_CONSTANT`      | `MyConstant`        | `MY_CONSTANT`   |
| File             | `MyClass.php`            | `myModule.js`      | `MyClass.cs`        | `my_module.py`  |
| Function param   | `$param_one`             | `paramOne`         | `paramOne`          | `param_one`     |

---

## Function and class design

**Functions should be short.** Aim for 4–10 lines. Above 15–20 lines is a clear signal to refactor. Short, well-named functions act like signposts — the reader immediately knows where they are and where they're going.

**Single responsibility.** Each function does exactly one thing. When you start joining things with "and" in a description of what a function does ("fetches data AND formats it") — split it.

**A long function hides a class.** If a function operates on many variables and keeps growing, it probably describes an object that should become a class. Extract it.

**Extract till you drop.** Keep extracting smaller functions until each one does a single simple thing. Don't be afraid of small, one-line methods if their name explains the intent.

**New functionality = new class.** Don't add logic to existing controllers, beans or services just because there's "room" there. For each new responsibility, create a dedicated class.

**Avoid `*Utils` and `*Helpers` classes.** These are a bin — a class that catches "everything that doesn't fit anywhere else". When you want to create `StringUtils`, think about what that class actually does and name it from that responsibility, e.g. `EmailFormatter`.

**Separate layers.** Business logic should not know about the database structure. A domain class should not know how data is serialized to JSON. Mixing layers creates dependencies that are hard to untangle later.

---

## Code quality rules

**Remove debug code before committing.** `var_dump()`, `console.log()`, `print()`, `Debug.WriteLine()`, `dd()` — all of it must be gone before changes reach the repo. No exceptions.

**Remove unnecessary logs before committing.** Especially logs to standard system log files (e.g. `sugarcrm.log`). Logs that served debugging purposes during a work session should not make it into production code.

**One file = one class/interface.** The filename is identical to the name of the class or interface it defines.

**User-facing text → language file.** Every label, error message, confirmation — goes into a translation file. Never hardcoded. This makes it reusable and translatable.

**Use factories and DI.** Where the framework provides BeanFactory / ViewFactory / ControllerFactory or a Dependency Injection mechanism — use it. Avoid `new ClassName()` directly when a better object creation mechanism is available.

**Build generic solutions.** Before writing a solution for one specific case, consider whether a similar one might appear soon. A generic solution from the start saves later refactoring.

