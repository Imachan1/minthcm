---
name: e2e-testing
version: 1.1.0
description: "Skill do pisania testów automatycznych E2E w Playwright oraz testowania manualnego przez Claude in Chrome. Użyj gdy: tworzenie testu E2E, test formularza, test logowania, Page Object, POM, fixture faker, test workflow, test modułu CRM, test pola, checklist testowy, log testu, retest buga, zgłaszanie buga, struktura tests/, playwright config, uruchomienie testów, debugowanie testów, Claude in Chrome testowanie. Trigger: test, spec, e2e, playwright, POM, Page Object, fixture, faker, test automatyczny, scenariusz testowy, TC-, checklist, log testu, retest, bug."
---

# E2E Testing — konwencje testów automatycznych eVolpe

Skill dla testerów QA piszących testy E2E z użyciem Playwright oraz testujących manualnie przez Claude in Chrome w projektach eVolpe.

## Kiedy używać

- Testujesz nowy moduł CRM (pełny test)
- Testujesz dodanie/modyfikację pola (mały test)
- Tworzysz nowy test E2E (spec file)
- Tworzysz lub modyfikujesz Page Object
- Tworzysz fixture / fabrykę danych testowych z faker
- Konfigurujesz Playwright w projekcie
- Tworzysz helper API (setup/teardown danych)
- Piszesz test cross-modułowy (workflow)
- Debugujesz lub uruchamiasz testy
- Zapisujesz log/raport z testu
- Retest buga

## Kiedy NIE używać

- Implementujesz funkcjonalność w systemie CRM (użyj skilla systemowego, np. `minthcm-project`)
- Piszesz testy jednostkowe backend (to nie jest E2E)
- Konfigurujesz CI/CD pipeline (to osobny temat)
- Zgłaszasz buga w Redmine (użyj skilla `bug-report`)

## Routing

**Pierwsza decyzja — jaki to typ testu?**

| Sytuacja | Zacznij od |
|----------|-----------|
| Test nowego modułu / regresja | `references/module-test-checklist.md` + `references/test-workflow.md` |
| Dodanie pola / mały test | `references/field-test-checklist.md` + `references/test-workflow.md` |

**Potem — jak pisać / jak organizować:**

| Zadanie | Referencja |
|---------|-----------|
| Jak podejść do zadania (logi, bug, małe/duże testy, retest) | `references/test-workflow.md` |
| Co sprawdzać przy teście nowego modułu (14 sekcji) | `references/module-test-checklist.md` |
| Co sprawdzać przy teście pola (mały test) | `references/field-test-checklist.md` |
| Jak pisać test (konwencje kodu, scenariusze, asercje, nazewnictwo) | `references/test-writing-guide.md` |
| Struktura `tests/` w repo, konwencje plików, importy | `references/common.md` |
| Konfiguracja Playwright, `.env`, uruchamianie testów | `references/playwright-setup.md` |
| Tworzenie Page Objects (POM) | `references/pom-guide.md` |
| Fixtures (faker), helpers API, utils | `references/helpers-guide.md` |

**Na koniec — załaduj skill systemowy E2E** (np. `minthcm-e2e`) żeby poznać specyfikę platformy (selektory, iframe, dashlety, zachowania UI). Sprawdź `system` w `.claude/skills-manifest.yaml` żeby wiedzieć który skill systemowy załadować.

## Kluczowe zasady (skrót)

- **NIGDY nie zgaduj selektorów** — przed pisaniem POM sprawdź DOM na żywej instancji (DevTools lub Claude in Chrome). Dotyczy ID pól, wartości option w dropdown, klas CSS
- Testy żyją w `tests/` w głównym repo projektu
- Page Object Model (POM) jest obowiązkowy
- Faker.js z polską lokalizacją — nigdy hardkodowane dane
- Nazewnictwo testów: `TC-01: Krótka nazwa` (po polsku, max 3-5 słów)
- `const` domyślnie, `let` tylko dla dynamicznych wartości
- Pliki testowe: `nazwa-funkcji.spec.js` (kebab-case)
- Page Objects: `NazwaStronyPage.js` (PascalCase)
- Każdy test kończy się logiem (data, co sprawdzono, wynik) → **log wyślij do Redmine** jako komentarz do podzagadnienia "Test" w US
- Bug → zgłoś przez skill `bug-report`
- Pliki testowe (PDF, Excel, JPG) → proś o dostarczenie, nie twórz sam
- Duże testy → Playwright automatyzacja
- Małe testy → rozszerz istniejący test lub Claude in Chrome + log
