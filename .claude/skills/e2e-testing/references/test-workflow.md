# Workflow testowania

Zasady organizacji pracy przy testowaniu — logi, zgłaszanie bugów, retest, decyzja o podejściu do testu.

---

## Logi testu

Każdy test po zakończeniu **musi zawierać log** z następującymi informacjami:

- **Kiedy** — data i godzina wykonania testu
- **Co sprawdzono** — lista sekcji/elementów przetestowanych (odwołanie do checklisty)
- **Wynik** — status per sekcja: OK / BUG / SUGESTIA / NA
- **Środowisko** — instancja, użytkownik testowy, przeglądarka

### Format logu (do Redmine)

Log zapisujemy w formacie Textile (natywny format Redmine) jako komentarz do zadania lub w opisie testu:

```textile
h3. Log testu: <nazwa modułu / pola>

*Data:* <dd.mm.yyyy HH:MM>
*Instancja:* <adres>
*Użytkownik:* <nazwa>
*Przeglądarka:* <np. Chrome 120>

|_. Sekcja |_. Status |_. Uwagi |
| Początkowe | %{color:green}OK% | — |
| Tworzenie rekordu | %{color:red}BUG% | Brak walidacji pola Telefon |
| DetailView | %{color:green}OK% | — |
| Audyt | %{color:green}OK% | — |
| ListView | %{color:green}OK% | — |
| Filtrowanie | %{color:orange}SUGESTIA% | Pole "Typ" brak w filtrach |
| ... | ... | ... |
```

### OBOWIĄZKOWE: zapisz log w Redmine

Po zakończeniu testu **zawsze wyślij log do Redmine** przez MCP (`redmine_request`). Log dopisz jako komentarz do podzagadnienia "Test" w User Story. Jeśli podzagadnienie "Test" nie istnieje — utwórz je.

Nie wystarczy wygenerować log w konsoli — musi trafić do Redmine, żeby zespół widział wynik testu.

---

## Zgłaszanie bugów

### Kiedy zgłaszamy buga

Gdy test zakończy się **niepowodzeniem** (status BUG w którejkolwiek sekcji) — utwórz zgłoszenie buga w Redmine.

### Gdzie zgłaszamy buga

Zależy od kontekstu projektu:

| Kontekst | Gdzie zgłosić | Tracker | Dodatkowe |
|----------|---------------|---------|-----------|
| **Projekt wdrożeniowy — BUG pod US** | Pod-zagadnienie w US | tracker_id: **24** | category_id: **813**, estimated_hours: **0**, prefiks **BUG:** w tytule |
| **Serwis** | Pod zagadnieniem "Mały rozwój" | Błąd (id: 1) | — |
| **Błąd krytyczny / niekrytyczny zgłoszony przez klienta** | Osobne zagadnienie | Błąd krytyczny (id: 15) lub Błąd niekrytyczny (id: 16) | — |

> **Uwaga:** Tester wewnętrzny w projekcie wdrożeniowym używa trackera id **24** z prefiksem **BUG:** w tytule, nie trackera "Błąd" (id: 1).

Zgłoszenie buga wykonuj **automatycznie** po wykryciu niepowodzenia w teście — nie pytaj użytkownika. Szczegóły API call i szablony opisu → patrz skill `bug-report`.

### Zasada: jedno zgłoszenie per test

Jeśli w jednym teście wykryto **wiele bugów** — **NIE twórz osobnego zgłoszenia dla każdego buga**. Utwórz **jedno zgłoszenie** z listą wszystkich wykrytych problemów. W tytule użyj nazwy testu (np. "Bugi z testu modułu Kandydaci"), a w opisie wylistuj poszczególne bugi z ich krokami reprodukcji i sekcją testu, w której zostały znalezione.

### Co musi zawierać zgłoszenie buga

- **Tytuł** — krótki, konkretny (np. "Brak walidacji pola Telefon w module Kandydaci" lub "Bugi z testu modułu Kandydaci" gdy wiele bugów)
- **Moduł** którego dotyczy
- **Kroki reprodukcji** — co zrobić żeby odtworzyć błąd (per bug jeśli wiele)
- **Wynik oczekiwany** vs **wynik rzeczywisty**
- **Screenshot** (jeśli pomaga zrozumieć problem)
- **Środowisko** — instancja, przeglądarka

Użyj skilla `bug-report` do stworzenia zgłoszenia przez MCP — skill prowadzi przez cały proces i ma szablon zarówno dla pojedynczego buga jak i dla wielu bugów z jednego testu.

---

## Retest buga

Po naprawieniu buga przez developera:

1. **Odtwórz kroki z oryginalnego zgłoszenia** — dokładnie te same
2. **Jeśli test przechodzi** → zamknij buga, zamknij test z logiem "Retest OK"
3. **Jeśli bug nadal występuje** → otwórz buga ponownie z opisem:
   - Co nadal nie działa
   - Czy zachowanie się zmieniło (częściowa naprawa) czy jest identyczne
   - Nowy screenshot jeśli potrzebny

---

## Małe vs duże testy — decyzja o podejściu

### Duże testy (test nowego modułu, regresja)

→ **Testy automatyczne Playwright**

Kiedy: nowy moduł, pełny test regresyjny, test wymagający powtarzalności.

Twórz pełny test automatyczny zgodnie z konwencjami (POM, faker, fixtures). Użyj checklisty `module-test-checklist.md`.

### Małe testy (dodanie pola/pól do istniejącego modułu)

→ **Preferuj rozszerzenie istniejącego testu lub test manualny przez Claude in Chrome**

Podejście w kolejności preferencji:

1. **Jeśli istnieje test automatyczny dla tego modułu** — rozszerz go:
   - Dodaj nowe pole do fixture (`generateXxx()`)
   - Dodaj wypełnienie pola w Page Object
   - Dodaj asercję w spec
   - NIE twórz nowego testu od zera

2. **Jeśli nie ma testu automatycznego** — przetestuj manualnie przez **Claude in Chrome**:
   - Przetestuj pole zgodnie z `field-test-checklist.md`
   - Zapisz **log/raport** z informacją co przetestowałeś i jaki jest wynik
   - Log umieść jako komentarz w zadaniu Redmine

---

## Pliki testowe (załączniki)

Jeśli test wymaga dodania pliku (PDF, Excel, JPG, lub cokolwiek innego):

**NIE twórz pliku sam** — poproś użytkownika o dostarczenie pliku testowego.

Uzasadnienie: pliki testowe powinny odzwierciedlać realne dane klienta (format, rozmiar, zawartość). Wygenerowany plik może nie ujawnić problemów, które wystąpią z prawdziwym plikiem.

---

## Podsumowanie przepływu — WYKONAJ AUTOMATYCZNIE

**WAŻNE:** Poniższe kroki wykonuj **automatycznie jeden po drugim** — nie pytaj użytkownika o pozwolenie między krokami. Cały pipeline od napisania testu do zgłoszenia buga to jedna operacja.

```
Nowe zadanie testowe
    │
    ├── Duży test (nowy moduł / regresja)
    │   ├── 1. Przeczytaj US i kryteria akceptacji
    │   ├── 2. Załaduj checklistę (module-test-checklist.md)
    │   ├── 3. Sprawdź selektory na żywej instancji
    │   ├── 4. Stwórz test automatyczny (POM + spec + fixtures)
    │   ├── 5. URUCHOM test (npx playwright test --headless) ← nie czekaj na pozwolenie
    │   ├── 6. Wyślij log testu do Redmine (podzagadnienie Test w US) ← automatycznie
    │   └── 7. Jeśli BUG → zgłoś przez skill bug-report ← automatycznie
    │
    └── Mały test (dodanie pola/pól)
        ├── 1. Przeczytaj US i kryteria akceptacji
        ├── 2. Załaduj checklistę (field-test-checklist.md)
        ├── 3. Sprawdź selektory na żywej instancji
        ├── 4. Rozszerz istniejący test LUB stwórz nowy
        ├── 5. URUCHOM test (npx playwright test --headless) ← nie czekaj na pozwolenie
        ├── 6. Wyślij log testu do Redmine ← automatycznie
        └── 7. Jeśli BUG → zgłoś przez skill bug-report ← automatycznie
```

### Pipeline po napisaniu testu

Po napisaniu kodu testu (spec + POM + fixtures) **natychmiast wykonaj kolejne kroki bez pytania**:

1. **Uruchom test** — `npx playwright test <ścieżka-do-spec> --reporter=list`
2. **Przeanalizuj wynik** — które testy przeszły (✓), które nie (✗)
3. **Wygeneruj log** w formacie Textile (patrz sekcja "Logi testu" wyżej)
4. **Wyślij log do Redmine** — jako komentarz do podzagadnienia "Test" w US (utwórz podzagadnienie jeśli nie istnieje)
5. **Jeśli test wykrył BUG** — od razu zgłoś buga w Redmine przez skill `bug-report` (pod US jako podzagadnienie)

Nie pytaj użytkownika "czy uruchomić test?", "czy dodać log?", "czy zgłosić buga?" — **rób to automatycznie**.