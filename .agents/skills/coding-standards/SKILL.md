---
name: coding-standards
description: Use this skill when the user asks to review code quality, check coding standards, perform a code review, or when writing new code that should follow company standards. Triggers on: 'review my code', 'check standards', 'code review', 'sprawdź kod', 'zrób review', 'czy kod spełnia standardy', 'oceń kod', 'przejrzyj kod'. Apply this skill proactively when writing new PHP, TypeScript or Vue code for MintHCM to ensure all rules are followed from the start.
---

# Coding Standards — MintHCM / Evolpe

Apply these rules when **writing new code** and when **reviewing existing code**. After a review always provide a summary listing violations grouped by section.

---

## Naming Conventions

| Element | Convention | Example |
|---|---|---|
| Module (Bean) | `ev_PrefixedPascalCase` | `ev_NewModule` |
| Variables / class fields | `snake_case` | `my_field`, `first_name` |
| Function parameters | `snake_case` | `myFunction($param_1, $param_2)` |
| Functions / methods | `camelCase` | `myFunction()`, `getRelatedBean()` |
| Classes / interfaces | `PascalCase` | `MyClass`, `MyInterface` |
| Constants | `SCREAMING_SNAKE_CASE` | `MAX_RETRY_COUNT` |

**General naming rules:**
- Names should be simple and as short as possible, but meaningful
- Names are spelled correctly
- Names include units of measurement where applicable (e.g. `timeout_ms`, `size_bytes`)
- No magic numbers — instead of `if ($x > 86400)` use named constants
- No negatively named boolean variables (`$not_found`, `$is_not_valid` → use `$found`, `$is_valid`)

---

## File and Class Structure

- **One file = one class/interface** — file name must match the class name
- New functionality → new dedicated classes, not extending the controller or bean
- **No `*Utils` / `*Helper` classes** — create classes for a specific purpose, not as a catch-all collection
- A VT API class may have **at most one** additional function for handling an API call
- Configuration belongs in config files — never hardcoded inside business logic
- Layer separation: business logic must not know the database structure

---

## Factories and Patterns

- Always use `BeanFactory`, `ViewFactory`, `ControllerFactory` — never `new SomeBean()` directly
- Apply design patterns correctly; if you use one — justify the choice
- Follow SOLID principles:
  - **S** — a class has a single responsibility
  - **O** — open for extension, closed for modification
  - **L** — objects replaceable with instances of their subtypes (Liskov)
  - **I** — many client-specific interfaces rather than one general-purpose interface
  - **D** — depend on abstractions, not on concrete implementations
- Follow the Law of Demeter — an object should not call methods on objects returned by other methods

---

## Code Quality

### Functions and Methods

- Functions must be **short** — if you can break it down, break it down
- No deep nesting — use early return instead of nested `if`s
- **No boolean parameters** in method signatures — they indicate a method does two things
- Correct access modifiers: `private`/`protected`/`public` — think through visibility
- Static methods are not overused
- Do not duplicate conditions — the same `if` condition should not appear multiple times

### Variables

- All variables are properly initialized before use
- Variables are immutable where possible
- Variables are in the smallest possible scope
- No unused variables
- No accidental use of `null` values
- `null` is not returned from methods unless intentional

### Expressions and Operators

- In `if` conditions — constant value on the **left** side: `if (1 == $variable)` (Yoda conditions)
- Do not mix `==` with `===` (and `!=` with `!==`) — use strict comparison where type matters
- Floating point numbers are not compared with `==`
- No complex/long boolean expressions — break them into named variables

### Loops

- Loops have a defined length and correct termination conditions
- Code blocks inside loops are as small as possible
- Use `break`/`continue` where they simplify code
- Watch out for off-by-one errors

---

## Texts and Internationalization

- **Every displayed text** must be in a language file as a Label
- No hardcoded strings displayed to the user in PHP/JS/Vue code
- Reuse existing labels instead of creating duplicates

---

## SQL and Database

- Every SQL query must include the condition `deleted = 0`
- Analyze all SQL queries for performance and correctness
- Use parameterized queries — never string concatenation with user data (SQL Injection)
- Business logic must not directly know the table structure

---

## Cleanup Before Commit

- Remove **all** `var_dump()`, `print_r()`, `echo` calls used for debugging
- Remove **all** unnecessary logs, especially to `sugarcrm.log`
- No commented-out code
- No dead code (unreachable at runtime)
- No stack traces printed to output

---

## Error and Exception Handling

- Catch clauses are fine-grained — catch specific exceptions, not a generic `Exception`
- Exceptions are not silently eaten — if they are, it must be documented
- Files, sockets and other resources are properly closed even when an exception occurs
- Invalid parameter values are handled without throwing unexpected exceptions

---

## API

- Validate input data at the API boundary — fail fast
- Check OAuth scope / user permissions
- API changes must be reflected in API documentation
- API returns correct HTTP status codes

---

## Logging

- Logs are easy to find and understand
- Required logs are present
- Unnecessary logs are absent
- No `print_r`, `var_dump`, stack traces in production logs

---

## Documentation and Comments

- Comments describe **WHY**, not **WHAT** the code is doing
- All public methods/interfaces/contracts are commented
- Non-standard behaviour and edge cases are described
- Data structures and units of measurement are explained
- Rationale for architectural decisions is documented

---

## Security

- All input data is validated (type, length, format, range)
- No sensitive data is logged or visible in a stack trace
- No SQL Injection, XSS or other OWASP Top 10 vulnerabilities

---

## Testability

- Code is unit testable
- Consider whether a test script can be written for this code
- Test cases are written wherever possible

---

## Review Checklist

Answer every question before approving code:

### General Code
- [ ] The code works correctly?
- [ ] The code is easy to understand?
- [ ] Naming conventions followed?
- [ ] No magic numbers or hardcoded values?
- [ ] No commented-out or dead code?
- [ ] No code duplication — checked that the function does not already exist?
- [ ] Ideal data structures used?
- [ ] No memory leaks?
- [ ] Performance considered?

### OOP and Architecture
- [ ] Code is written in an object-oriented way?
- [ ] Correct `private`/`protected`/`public` modifiers?
- [ ] Code is in the right place (correct class/layer)?
- [ ] Class covers a single responsibility (SRP)?
- [ ] VT API class has at most one additional function?
- [ ] `BeanFactory`/`ViewFactory`/`ControllerFactory` used?
- [ ] No `*Utils`/`*Helper` classes?

### Functions and Methods
- [ ] Methods are not too long, no excessive nesting?
- [ ] No boolean parameters?
- [ ] Static methods are not overused?
- [ ] No duplicated conditions in `if`s?

### Variables and SQL
- [ ] All variables properly initialized?
- [ ] Variables in the smallest possible scope?
- [ ] No unused variables?
- [ ] SQL contains `deleted = 0` everywhere?
- [ ] SQL queries analyzed for performance and security?

### Texts and Configuration
- [ ] All displayed texts in language files?
- [ ] Configuration in config files?

### Cleanup
- [ ] No debug `var_dump`, `print_r`, `echo` calls?
- [ ] No unnecessary logs to `sugarcrm.log`?
- [ ] No stack traces?

### Security and API
- [ ] Input data validated?
- [ ] Permissions checked?
- [ ] API documentation updated for API changes?
- [ ] Correct HTTP status codes?
- [ ] No sensitive data in logs?
