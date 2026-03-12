---
name: code-review
description: >
  Skill do przeprowadzania AI code review na feature branchu powiązanym
  z zagadnieniem Redmine. Używaj gdy user prosi o code review, CR,
  przegląd kodu, review brancha, review zagadnienia, lub podaje numer
  zagadnienia Redmine do przeglądu. Generuje raport .ai/tasks/{ISSUE_ID}/cr.md,
  inline FIXME komentarze i tworzy zagadnienie Task/Bug w Redmine.
argument-hint: <numer_lub_url_zagadnienia> (np. 184819 lub https://redmine.evolpe.net/issues/184819)
---

# Code Review

Skill do przeprowadzania AI code review na feature branchu powiązanym z zagadnieniem Redmine.

---

## Kiedy używać

- User prosi o "code review", "CR", "przegląd kodu", "review brancha"
- User podaje numer zagadnienia Redmine lub URL do przeglądu
- Re-review po wprowadzeniu poprawek z poprzedniego CR
- User mówi "zrób CR dla #184819" lub "przejrzyj kod do tego issue"

## Kiedy nie używać

- **Self Code Review podczas implementacji** — to inna faza (wyniki do `.ai/tasks/XXXXXX/cr`), nie ten skill
- Review kodu bez powiązanego zagadnienia Redmine
- Review bez feature brancha w repozytorium git
- Analiza kodu bez kontekstu wdrożeniowego (użyj zwykłego czytania kodu)

---

## Wymagania

- Narzędzie **Redmine MCP** (`redmine_request`) — zalecane; bez niego skill działa w trybie manualnym (patrz niżej)
- Repozytorium **git** z feature branchem dla zagadnienia

### Tryb manualny (bez Redmine MCP)

Jeśli `redmine_request` **nie jest dostępne**, nie przerywaj — przejdź w tryb manualny:
- **Step 2** — poproś usera o wklejenie danych zagadnienia z Redmine
- **Step 6** — zapytaj usera kto jest autorem/osobą do przypisania
- **Step 13** — wyświetl gotowe dane do ręcznego utworzenia zagadnienia w Redmine

---

## Przegląd procesu (13 kroków)

Pełna procedura w `references/workflow.md`. Skrócony przebieg:

1. **Parsuj numer** — z `$ARGUMENTS` (slash cmd) lub z kontekstu rozmowy
2. **Pobierz z Redmine** — tytuł, opis, tracker, projekt, status
3. **Walidacja trackera** — tylko US/Epic/Spike (nie Task); dozwolone ID: 18, 19, 22, 23
4. **git fetch --all** — pobierz najnowsze zmiany
5. **Checkout branch** — `feature/{ISSUE_ID}` lub alternatywny po pytaniu
6. **Zidentyfikuj autora** — git log → Redmine lookup → potwierdzenie z userem
7. **Ustal zakres diff** — commity feature, re-review detection, potwierdzenie
8. **Przeprowadź review** — zbierz diff, uruchom 5 równoległych agentów review (Sonnet: 1–5), oceń pewność findings (Haiku), filtruj (próg confidence 80)
9. **Napisz raport** — `.ai/tasks/{ISSUE_ID}/cr.md` z werdyktem i checklistą CRITICAL/WARNING
10. **Dodaj FIXME** — inline komentarze dla CRITICAL i WARNING
11. **Zapytaj o commit** — podsumowanie + potwierdzenie usera
12. **Commit** — format `ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}`
13. **Utwórz zagadnienie** — Task z kategorią Bug w Redmine (tylko gdy są CRITICAL/WARNING)

---

## Routing do references

| Kiedy | Czytaj |
|---|---|
| Steps 2, 3, 6 (Redmine), 13 | `references/redmine-integration.md` |
| Steps 8b, 8c, 9, 10 | `references/review-criteria.md` |
| Pełna procedura, steps 1, 4–7, 11–12 | `references/workflow.md` |

Zacznij od `references/workflow.md` — zawiera kompletną sekwencję z odwołaniami do pozostałych plików.

---

## Oczekiwany sposób pracy

- **Potwierdzaj z userem** w kluczowych momentach: autor kodu (Step 6), zakres commitów (Step 7c), commit raportu (Step 11)
- **Nie commituj bez zgody** — zawsze czekaj na odpowiedź przed Step 12
- **Override kryteriów** — jeśli projekt ma własny skill review (np. `minthcm-project`), użyj jego kryteriów zamiast baseline z `review-criteria.md`; uruchom po jednym agencie na każde kryterium z dedykowanego skilla
- **Re-review** — jeśli istnieją wcześniejsze commity CR (`BUG|`), automatycznie ogranicz zakres do nowych zmian (Step 7b)
- **Brak CRITICAL/WARNING** — nie twórz zagadnienia w Redmine, wyświetl informację
