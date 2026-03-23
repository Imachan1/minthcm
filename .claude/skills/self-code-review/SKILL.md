---
name: self-code-review
version: 1.0.0
description: >
  Self code review po implementacji, przed formalnym CR.
  Trigger: "self review", "self CR", "sprawdź moje zmiany", "przejrzyj co zrobiłem",
  "zrób self review", "zrób self CR", "sprawdź kod przed CR".
  Weryfikuje kompletność vs plan, coding standards, typowe błędy logiczne, security i performance.
  Wyniki zapisuje do .ai/tasks/{ISSUE_ID}/self-cr.md.
argument-hint: <numer_zagadnienia> (opcjonalny — domyślnie inferowany z nazwy brancha)
---

# Self Code Review

Skill do przeprowadzania self code review po zakończeniu implementacji, przed przekazaniem do formalnego Code Review.

---

## Kiedy używać

- User kończy implementację i chce sprawdzić kod przed formalnym CR
- User prosi o "self review", "self CR", "sprawdź moje zmiany", "przejrzyj co zrobiłem"
- User chce zweryfikować czy implementacja jest kompletna względem planu
- Szybkie sprawdzenie jakości kodu na własnym feature branchu

Ten skill realizuje fazę **Self Code Review** z procesu AI w SD — po Implementacji, przed formalnym Code Review.

## Kiedy NIE używać

- **Formalny Code Review** — to osobna faza, użyj skilla `code-review`
- **Testowanie zmian** — użyj skilla `self-test`
- Brak ukończonej implementacji — najpierw zakończ implementację
- Review cudzego kodu — to jest self-review, nie peer review

---

## Kontekst w procesie AI

```
Implementacja → [Self Code Review] → self-test → Code Review → Testy → Poprawki
```

Self CR jest fazą między Implementacją a formalnym CR. Jego celem jest wychwycenie oczywistych błędów i weryfikacja kompletności **zanim** inny reviewer poświęci czas na przegląd.

---

## Przegląd procesu (7 kroków)

Pełna procedura w `references/workflow.md`. Skrócony przebieg:

1. **Kontekst** — ISSUE_ID z argumentów lub nazwy brancha (`feature/{ID}`)
2. **Plan** — wczytaj `.ai/tasks/{ISSUE_ID}/plan.md` (opcjonalny — bez planu skip weryfikacji kompletności)
3. **Diff** — `git diff origin/{DEFAULT_BRANCH}` (zacommitowane + niezacommitowane), filtruj vendor/generated
4. **Review** — sekwencyjny, single-pass, 5 obszarów (patrz `references/review-checklist.md`)
5. **Raport** — zapisz do `.ai/tasks/{ISSUE_ID}/self-cr.md`
6. **Podsumowanie** — pokaż wyniki w terminalu, lista CRITICAL/WARNING
7. **Decyzja** — NEEDS FIXES → napraw i ponów; READY FOR CR → zasugeruj `/self-test` i potem `/code-review`

---

## Output

Skill generuje jeden artefakt:

- **Raport Self CR** — `.ai/tasks/{ISSUE_ID}/self-cr.md` z listą findings i statusem READY FOR CR / NEEDS FIXES

Raport jest dokumentem roboczym — **nie jest commitowany**. Przy ponownym uruchomieniu jest nadpisywany (nie appendowany).

---

## Routing do references

| Kiedy | Czytaj |
|---|---|
| Pełna procedura, kroki 1–3, 5–7 | `references/workflow.md` |
| Krok 4 — przeprowadzenie review, format raportu | `references/review-checklist.md` |

Zacznij od `references/workflow.md`.
