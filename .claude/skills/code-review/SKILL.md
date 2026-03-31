---
name: code-review
version: 1.4.0
description: >
  Skill do przeprowadzania AI code review na feature branchu powiązanym
  z zagadnieniem Redmine. Używaj gdy user prosi o code review, CR,
  przegląd kodu, review brancha, review zagadnienia, re-review, re-CR,
  ponowny przegląd, sprawdzenie czy poprawki z CR są ok, lub podaje numer
  zagadnienia Redmine do przeglądu. Generuje raport .ai/tasks/{ISSUE_ID}/cr.md,
  inline FIXME komentarze i tworzy zagadnienie Task/Bug w Redmine.
  Używaj tego skilla zawsze gdy user wymienia numer zagadnienia Redmine
  w kontekście przeglądu kodu — nawet jeśli nie padło słowo "code review",
  sam numer issue z wzmianką o branchu lub prośbą o "sprawdzenie kodu" wystarczy
  jako trigger.
argument-hint: <numer_lub_url_zagadnienia> (np. 184819 lub https://redmine.evolpe.net/issues/184819)
---

# Code Review

Skill do przeprowadzania AI code review na feature branchu powiązanym z zagadnieniem Redmine.

---

## Kiedy używać

- User prosi o "code review", "CR", "przegląd kodu", "review brancha"
- User podaje numer zagadnienia Redmine lub URL do przeglądu
- Re-review po wprowadzeniu poprawek z poprzedniego CR — np. "zrób re-review #184819", "sprawdź czy poprawki z CR są ok"
- User mówi "zrób CR dla #184819" lub "przejrzyj kod do tego issue"

Ten skill realizuje fazę **Code Review** z procesu AI w SD (po Self Code Review, przed Testami). Nie używaj go do Self Code Review podczas implementacji — to osobna faza.

## Kiedy nie używać

- **Self Code Review podczas implementacji** — to inna faza (użyj skilla `self-code-review`, wyniki do `.ai/tasks/XXXXXX/self-cr.md`), nie ten skill
- Review kodu bez powiązanego zagadnienia Redmine
- Review bez feature brancha w repozytorium git
- Analiza kodu bez kontekstu wdrożeniowego (użyj zwykłego czytania kodu)

---

## Wymagania

- Narzędzie **Redmine MCP** (`redmine_request`) — zalecane; bez niego skill działa w trybie manualnym (patrz niżej)
- Repozytorium **git** z feature branchem dla zagadnienia
- Skill **`redmine-guide`** — opcjonalny; jeśli dostępny w kontekście, używany w Step 13 przy tworzeniu zagadnienia

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
3. **Walidacja trackera** — tylko User Story/User Story Bug/Mały rozwój/Błąd krytyczny/Błąd niekrytyczny/Epic/Spike (nie Task); dozwolone ID: 14, 15, 16, 18, 19, 22, 23
4. **git fetch --all** — pobierz najnowsze zmiany
5. **Checkout branch** — `feature/{ISSUE_ID}` lub alternatywny po pytaniu
6. **Zidentyfikuj autora** — git log → Redmine lookup → potwierdzenie z userem
7. **Ustal zakres diff** — commity feature, re-review detection, potwierdzenie; jeśli to re-review → przejdź do `references/re-review.md`
8. **Przeprowadź review** — zbierz diff i trafne skille; uruchom review (sekwencyjny dla <20 linii, 5 równoległych agentów dla >=20 linii, podział na chunki po plikach dla >500 linii); szczegóły w `references/workflow.md` (Steps 8a–8f, 8b-bis)
9. **Napisz raport** — `.ai/tasks/{ISSUE_ID}/cr.md` z werdyktem i checklistą CRITICAL/WARNING
10. **Dodaj FIXME** — inline komentarze dla CRITICAL i WARNING
11. **Zapytaj o commit** — podsumowanie + potwierdzenie usera
12. **Commit** — format `ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}`; następnie zapytaj o push
12b. **Squash i merge** — tylko przy APPROVED: zaproponuj squash commitów brancha do jednego, następnie merge do `DEFAULT_BRANCH` (oba kroki wymagają osobnego potwierdzenia usera)
13. **Utwórz zagadnienie** — Task z kategorią Bug w Redmine (tylko gdy są CRITICAL/WARNING i werdykt to CHANGES REQUESTED; pomijany dla APPROVED i NEEDS DISCUSSION); dla projektów serwisowych twórz je w podprojekcie `-internal`, nie w projekcie nadrzędnym; następnie wyświetl podsumowanie końcowe

---

## Output

Skill generuje trzy artefakty:

- **Raport CR** — `.ai/tasks/{ISSUE_ID}/cr.md` z werdyktem (APPROVED / CHANGES REQUESTED / NEEDS DISCUSSION) i listą findings
- **Inline FIXME** — komentarze w plikach źródłowych dla każdego CRITICAL i WARNING
- **Zagadnienie Redmine** — Task z kategorią Bug, przypisany do autora kodu (tylko gdy są CRITICAL lub WARNING)
- **Obsługa sporów** — podczas re-CR, jeśli deweloper dodał komentarz przy FIXME wyjaśniający dlaczego nie poprawia, reviewer decyduje czy zaakceptować; zaakceptowane findings trafiają do checklisty jako zrealizowane z komentarzem dewelopera

---

## Routing do references

| Kiedy | Czytaj |
|---|---|
| Steps 2, 3, 6 (Redmine), 13 | `references/redmine-integration.md` |
| Steps 2, 6, 13 bez Redmine MCP (tryb manualny) | `references/redmine-integration.md` (sekcje "Tryb manualny") |
| Steps 8b–8f, 9, 10 | `references/review-criteria.md` |
| Pełna procedura, steps 1, 4–7, 11–12b | `references/workflow.md` |
| Re-CR (po wykryciu commitów CR w Step 7b) | `references/re-review.md` |

Zacznij od `references/workflow.md` — zawiera kompletną sekwencję z odwołaniami do pozostałych plików.

---

## Oczekiwany sposób pracy

- **Potwierdzaj z userem** w kluczowych momentach: autor kodu (Step 6), zakres commitów (Step 7c), commit raportu (Step 11)
- **Nie commituj bez zgody** — zawsze czekaj na odpowiedź przed Step 12
- **Skille** — w Step 8a przejrzyj skille załadowane w kontekście i zdecyduj, które są trafne na podstawie diffa i kontekstu zagadnienia z Redmine; wypisz podsumowanie wybranych, a przed uruchomieniem agentów (Step 8c) — mapowanie skill → agent; przekaż reguły agentom jako uzupełnienie baseline kryteriów
- **Re-review** — jeśli istnieją wcześniejsze commity CR (`BUG|`), automatycznie ogranicz zakres do nowych zmian (Step 7b), następnie przejdź do `references/re-review.md`
- **APPROVED (CR i Re-CR): przed squash i merge zawsze pytaj o potwierdzenie** — squash jest nieodwracalny lokalnie; merge do develop wymaga oddzielnego potwierdzenia
- **Brak CRITICAL/WARNING** — nie twórz zagadnienia w Redmine, wyświetl informację
- **Projekt serwisowy** — w Step 13 twórz CR BUG w podprojekcie `-internal`; `parent_issue_id` pozostaje numerem oryginalnego zagadnienia z projektu serwisowego
