# Review Checklist — Self Code Review

## 5 obszarów review

Przejdź przez każdy obszar sekwencyjnie. Zapisuj findings wg formatu poniżej.

---

### Obszar 1 — Kompletność vs plan

**Warunek:** Wykonaj tylko jeśli plan został wczytany w Step 2. Jeśli brak planu — pomiń ten obszar i odnotuj w raporcie: *(pominięto — brak planu)*.

**Co sprawdzamy:**
- Każdy krok planu implementacji — czy ma odpowiadający kod w diffie?
- Pliki wskazane w tabeli planu — czy zostały zmodyfikowane?
- Kryteria akceptacji z planu — czy są zrealizowane?

**Severity:**
- `CRITICAL` — krok planu kluczowy dla działania feature'a nie ma implementacji
- `WARNING` — krok planu pomocniczy pominięty lub częściowo zrealizowany
- `INFO` — drobne odchylenie od planu, nie wpływające na działanie

---

### Obszar 2 — Poprawność i logika

**Co sprawdzamy:**
- Null/undefined dereferencja — dostęp do właściwości bez sprawdzenia czy obiekt istnieje
- Brakujący error handling — brak obsługi wyjątków, błędów API, pustych wyników
- Złe warunki — odwrócone `if`, pomylone `&&`/`||`, błędna logika branching
- Off-by-one — pętle, indeksy, zakresy (`<` vs `<=`, `>` vs `>=`)
- TODOs/FIXMEs — nieukończone miejsca w kodzie
- Puste bloki catch — `catch(e) {}` bez obsługi błędu

**Severity:**
- `CRITICAL` — błąd który może spowodować wyjątek/crash w normalnym użyciu
- `WARNING` — błąd w ścieżce pobocznej lub edge case
- `INFO` — TODO/FIXME pozostawione w kodzie

---

### Obszar 3 — Bezpieczeństwo (lightweight)

**Co sprawdzamy:**
- SQL injection — zapytania budowane przez konkatenację z inputem użytkownika
- XSS — renderowanie niezesanityzowanych danych użytkownika w HTML
- Hardcoded credentials — hasła, tokeny, klucze API w kodzie źródłowym
- Brak CSRF protection — formularze POST bez tokenów CSRF (w kontekście frameworka)
- Brak walidacji inputów — dane od użytkownika używane bez sprawdzenia

**Severity:**
- `CRITICAL` — bezpośrednia podatność security (SQL injection, XSS, hardcoded secret)
- `WARNING` — potencjalna podatność wymagająca kontekstu (np. brak walidacji w ścieżce pobocznej)
- `INFO` — hardcoded URL testowy (nie credentials), drobne kwestie security

---

### Obszar 4 — Performance (lightweight)

**Co sprawdzamy:**
- Zapytania DB w pętlach — query wewnątrz `foreach`/`for`/`while` (N+1 problem)
- `SELECT *` — pobieranie wszystkich kolumn gdy potrzeba tylko kilku
- Brak `LIMIT` — zapytania bez limitu rekordów na potencjalnie dużych tabelach
- Oczywiste N+1 — wielokrotne pobieranie tego samego obiektu w pętli

**Uwaga:** Oceniaj tylko oczywiste przypadki widoczne w diffie. Nie szukaj subtelnych problemów wydajnościowych — to zakres formalnego CR.

**Severity:**
- `CRITICAL` — query w pętli iterującej po potencjalnie dużej kolekcji
- `WARNING` — `SELECT *` lub brak LIMIT w zapytaniu na dużej tabeli
- `INFO` — drobne nieefektywności (np. zbędne pobranie pełnego obiektu)

---

### Obszar 5 — Coding standards

**Co sprawdzamy:**
- Debug code — `var_dump()`, `print_r()`, `dd()`, `console.log()`, `die()` pozostawione w kodzie
- Nazewnictwo — zmienne/funkcje niespójne z konwencjami projektu (camelCase vs snake_case, prefixing itp.)
- Martwy kod — zakomentowany kod, nieużywane zmienne/funkcje/importy
- Długość funkcji — funkcje przekraczające ~100 linii bez wyraźnego powodu

**Uwaga:** Jeśli w kontekście załadowany jest skill `coding-standards`, przejrzyj jego reguły i uwzględnij je w tym obszarze.

**Severity:**
- `CRITICAL` — debug code (`var_dump`, `console.log`, `dd()`) pozostawiony w kodzie produkcyjnym
- `WARNING` — martwy kod, znaczące naruszenie konwencji nazewniczych projektu
- `INFO` — drobne kwestie stylystyczne

---

## Format findings

Dla każdego znalezionego problemu:

```
- `plik:linia` — **[Kategoria] Krótki opis** — sugestia naprawy
```

Gdzie `[Kategoria]` to: `Logika`, `Security`, `Performance`, `Standards`, `Kompletność`.

Przykłady:
```
- `src/Controller/UserController.php:42` — **[Security] SQL injection** — użyj prepared statements zamiast konkatenacji
- `assets/js/module.js:17` — **[Standards] Debug code** — usuń `console.log` przed CR
- `src/Model/Order.php:89` — **[Performance] Query w pętli** — pobierz rekordy przed pętlą jednym zapytaniem
```

---

## Szablon raportu

Zapisz do `.ai/tasks/{ISSUE_ID}/self-cr.md`:

```markdown
# Self Code Review: #{ISSUE_ID} — {subject}

**Data:** {YYYY-MM-DD}
**Branch:** {branch_name}
**Zakres:** {N} plików, {M} linii zmian (diff vs origin/{DEFAULT_BRANCH})

## Kompletność planu

{N}/{M} kroków zrealizowanych

| Krok | Status | Uwagi |
|------|--------|-------|
| {opis kroku} | ✅ OK / ⚠️ Częściowo / ❌ Brak | {opcjonalnie} |

*(Sekcję pomiń jeśli brak planu — zastąp: "Brak planu — weryfikacja kompletności pominięta.")*

## Znalezione problemy

### CRITICAL

- `plik:linia` — **[Kategoria] Opis** — sugestia naprawy

*(brak)* — jeśli nie znaleziono

### WARNING

- `plik:linia` — **[Kategoria] Opis** — sugestia naprawy

*(brak)* — jeśli nie znaleziono

### INFO

- `plik:linia` — Opis

*(brak)* — jeśli nie znaleziono

## Podsumowanie

{Krótka ocena — co jest ok, co wymaga uwagi}

**Status:** READY FOR CR / NEEDS FIXES
```

**Status:**
- `READY FOR CR` — brak CRITICAL i WARNING (mogą być INFO)
- `NEEDS FIXES` — co najmniej jeden CRITICAL lub WARNING
