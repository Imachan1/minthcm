---
name: self-test
version: 1.0.0
description: >
  Użyj gdy deweloper kończy zadanie, feature lub bugfix i chce wiedzieć co manualnie
  zweryfikować przed pushem lub wysłaniem na code review. Triggery: "self test",
  "co przetestować", "co sprawdzić przed CR", "co przeklikać", "co muszę sprawdzić
  w przeglądarce", "daj mi scenariusze testowe", "ścieżki testowe", "zweryfikuj ręcznie",
  "skończyłem zadanie — co testować". Używaj też gdy user wspomina że skończył branch/issue
  i pyta co przetestować lub zweryfikować ręcznie. Generuje checklistę kroków do przeklikania
  w przeglądarce + opcjonalne snippety Chrome console na podstawie git diff.
  Wyniki zapisuje do .ai/tasks/{ISSUE_ID}/self-test.md.
  NIE używaj do: pisania testów automatycznych (unit/integration/selenium), code review,
  ani analizy jakości lub bezpieczeństwa kodu.
argument-hint: <numer_zagadnienia> (opcjonalny — domyślnie inferowany z nazwy brancha)
---

# Self Test

Skill do generowania ścieżek testowych i Chrome console snippetów dla manualnego testowania zmian.

---

## Kiedy używać

- User chce wiedzieć co przetestować ręcznie po implementacji
- User prosi o "self test", "ścieżki testowe", "co przetestować", "jak przetestować zmiany"
- Przed formalnym CR — weryfikacja że feature działa poprawnie
- Po naprawie bugów z formalnego CR — sprawdzenie czy poprawki działają
- Niezależnie od self-code-review — można użyć samodzielnie w dowolnym momencie

## Kiedy NIE używać

- **Automatyczne testy** (unit/integration) — to nie jest skill do pisania testów automatycznych
- **Formalny Code Review** — użyj skilla `code-review`
- **Review kodu** (błędy, logika, security) — użyj skilla `self-code-review`

---

## Kontekst w procesie AI

```
Implementacja → Self Code Review → [Self Test] → Code Review → Testy → Poprawki
```

Self Test typowo następuje po Self Code Review, ale można go użyć niezależnie — np. po naprawie bugów z formalnego CR, gdy chcesz szybko sprawdzić co przetestować ręcznie.

---

## Przegląd procesu (7 kroków)

Pełna procedura w `references/test-paths.md`. Skrócony przebieg:

1. **Kontekst** — ISSUE_ID z argumentów lub nazwy brancha
2. **Analiza zmian** — diff + plan, identyfikacja zmienionych modułów/widoków/endpointów
3. **Generuj ścieżki** — happy path, edge cases, regresja (zasady w `references/test-paths.md`)
4. **Chrome snippety** — opcjonalne snippety JS (tylko gdy zmiana dotyczy frontendu)
5. **Raport** — zapisz do `.ai/tasks/{ISSUE_ID}/self-test.md`
6. **Zaproponuj test w Chrome** — zapytaj usera czy chce przetestować w przeglądarce (uruchom `/chrome`)
7. **Test w przeglądarce** — (opcjonalny) Claude przechodzi przez ścieżki, raportuje OK/FAIL

---

## Output

Skill generuje jeden artefakt:

- **Raport Self Test** — `.ai/tasks/{ISSUE_ID}/self-test.md` z checklistą ścieżek testowych i opcjonalnymi wynikami testu w przeglądarce

Raport jest dokumentem roboczym — **nie jest commitowany**. Przy ponownym uruchomieniu jest nadpisywany.

---

## Routing do references

| Kiedy | Czytaj |
|---|---|
| Pełna procedura, zasady generowania, format raportu | `references/test-paths.md` |

Zacznij od `references/test-paths.md`.
