---
name: self-code-review
version: 1.0.0
description: >
  Faza Self Code Review w procesie AI SD — po implementacji, przed formalnym CR.
  Trigger: "self review", "self CR", "sprawdź moje zmiany", "przejrzyj co zrobiłem",
  "zrób self review", "zrób self CR", "sprawdź kod przed CR", "czy kod jest gotowy na CR".
  Weryfikuje: kompletność vs plan, coding standards, logikę, security i performance.
  Wynik: raport .ai/tasks/{ISSUE_ID}/self-cr.md ze statusem READY FOR CR lub NEEDS FIXES.
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
3. **Zakres** — wykryj `BASE_BRANCH` (reflog → origin/HEAD → nazwy), pokaż commity + working tree, potwierdź z userem
4. **Review** — sekwencyjny, 5 obszarów; tryb zależy od rozmiaru diffa: SEQUENTIAL (<150 linii), FOCUSED (150–600), CHUNKED (>600 — grupy tematyczne; patrz `references/workflow.md` Step 3b)
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
| Pełna procedura (kontekst, zakres, diff, raport, decyzja) | `references/workflow.md` |
| Przeprowadzenie review (5 obszarów) i format raportu | `references/review-checklist.md` |

Zacznij od `references/workflow.md`.
