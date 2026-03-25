# Kryteria Code Review i Format Raportu

## Spis treści

- [Kryteria review — baseline](#kryteria-review--baseline)
  - [Agent 1 — Poprawność](#agent-1--poprawność)
  - [Agent 2 — Błędy logiczne](#agent-2--błędy-logiczne)
  - [Agent 3 — Bezpieczeństwo](#agent-3--bezpieczeństwo)
  - [Agent 4 — Wydajność](#agent-4--wydajność)
  - [Agent 5 — Jakość kodu](#agent-5--jakość-kodu)
  - [Werdykt NEEDS DISCUSSION](#werdykt-needs-discussion)
- [Format findings (Step 8c)](#format-findings-step-8c)
- [Confidence scoring (Step 8e)](#confidence-scoring-step-8e)
- [Szablon raportu CR (Step 9)](#szablon-raportu-cr-step-9)
- [Inline FIXME komentarze (Step 10)](#inline-fixme-komentarze-step-10)

---

## Kryteria review — baseline (Step 8b i 8c, per-agent)

Każdy agent otrzymuje diff + pełne pliki + kontekst Redmine + **jedno** kryterium z poniższej listy. Model per agent określony w `workflow.md` (Step 8c).

---

### Agent 1 — Poprawność

**Fokus:** Czy kod realizuje to, co opisuje zagadnienie w Redmine? Czy spełnia kryteria akceptacji? Czy nie ma brakujących przypadków edge-case wymienionych w opisie?

**Przykłady severity:**
- CRITICAL: Funkcja nie implementuje kluczowego wymagania z opisu zagadnienia
- WARNING: Brakuje obsługi przypadku opisanego w kryteriach akceptacji
- INFO: Implementacja działa, ale odbiega od sugestii z opisu zagadnienia

**Nie oceniaj:** błędów logicznych niezwiązanych z wymaganiami, bezpieczeństwa, wydajności, stylu kodu.

---

### Agent 2 — Błędy logiczne

**Fokus:** Błędy off-by-one, null/undefined dereferencja, race conditions, brakująca obsługa błędów, niepoprawne warunki brzegowe, błędy w przepływie sterowania.

**Przykłady severity:**
- CRITICAL: Możliwy NullPointerException / undefined access w production flow
- WARNING: Off-by-one w pętli iterującej po kolekcji
- INFO: Brakująca obsługa błędu w ścieżce pomocniczej (nie-krytycznej)

**Nie oceniaj:** zgodności z wymaganiami, bezpieczeństwa, wydajności, stylu kodu.

---

### Agent 3 — Bezpieczeństwo

**Fokus:** SQL injection, XSS, hardcoded credentials/secrets, brak walidacji inputów od użytkownika, nieautoryzowany dostęp do danych, CSRF, insecure deserialization.

**Przykłady severity:**
- CRITICAL: SQL query budowany przez konkatenację z inputem użytkownika
- WARNING: Brak sanityzacji outputu w miejscu renderowanym w HTML
- INFO: Hardcoded URL do środowiska testowego (nie credentials)

**Nie oceniaj:** poprawności logiki, błędów logicznych, wydajności, stylu kodu.

---

### Agent 4 — Wydajność

**Fokus:** Zapytania N+1 (pętla z query w środku), zbędne pętle O(n²), brak indeksów dla filtrowanych kolumn, nadmierne zużycie pamięci, zbędne operacje I/O w pętlach.

**Przykłady severity:**
- CRITICAL: Query wewnątrz pętli iterującej po wszystkich rekordach (N+1)
- WARNING: Brak indeksu na kolumnie używanej w WHERE/ORDER BY w dużej tabeli
- INFO: Zbędne pobranie pełnego obiektu gdy wystarczy jedno pole

**Nie oceniaj:** poprawności logiki, błędów logicznych, bezpieczeństwa, stylu kodu.

---

### Agent 5 — Jakość kodu

**Fokus:** Czytelność, konwencje nazewnictwa projektu, naruszenia DRY, martwy kod, nadmierna złożoność cyklomatyczna, zbyt długie funkcje, niejasne nazwy zmiennych.

**Przykłady severity:**
- CRITICAL: Zduplikowana logika biznesowa w 3+ miejscach (DRY violation z ryzykiem desynchronizacji)
- WARNING: Funkcja przekracza 100 linii, brak podziału na mniejsze jednostki
- INFO: Nazwa zmiennej nie oddaje semantyki

**Nie oceniaj:** poprawności logiki, błędów logicznych, bezpieczeństwa, wydajności.

---

### Werdykt NEEDS DISCUSSION

Stosuj gdy problem jest realny, ale **nie można go rozstrzygnąć bez rozmowy z zespołem** — czyli samo naprawienie kodu przez dewelopera nie wystarczy.

**Kiedy użyć:**
- Problem architektoniczny (np. wybrane podejście koliduje z kierunkiem systemu)
- Sprzeczność z wymaganiami, której nie da się rozstrzygnąć z samego kodu (np. niejasne kryteria akceptacji)
- Decyzja projektowa budząca poważne wątpliwości co do intencji (np. celowe obejście zabezpieczenia?)
- Brakująca specyfikacja dla ważnego edge-case

**Jak oznaczać:** Agent zgłasza finding jako `CRITICAL` z adnotacją `[NEEDS DISCUSSION]` w opisie. Główny model (nie agent) podejmuje decyzję o werdykcie końcowym na etapie tworzenia raportu (Step 9).

**Czym NIE jest NEEDS DISCUSSION:** Zwykły błąd logiczny, problem bezpieczeństwa lub zła jakość kodu — te idą jako CRITICAL/WARNING bez adnotacji.

---

> Każdy agent może otrzymać dodatkowy blok reguł zebranych w Step 8a (z trafnych skilli załadowanych w kontekście). Traktuj je jako rozszerzenie swojego kryterium — zgłaszaj naruszenia tych reguł w ramach swojego zakresu. Wybrane skille i ich przypisanie do agentów są logowane przed uruchomieniem agentów (patrz `workflow.md` Step 8a i 8c).

---

## Format findings (Step 8c)

Dla każdego znalezionego problemu zapisz:

- **Severity:** `CRITICAL` / `WARNING` / `INFO`
- **Plik i linia**
- **Krótki opis problemu**
- **Sugestia naprawy**
- **Confidence:** (tylko CRITICAL/WARNING, uzupełniane w Step 8e — tymczasowo zostaw puste)

---

## Confidence scoring (Step 8e)

Dla każdego finding o severity **CRITICAL** lub **WARNING** uruchom równoległego agenta Haiku, który ocenia pewność od 0 do 100.

**Skala:**
- **90–100:** Pewny błąd — jednoznaczna przyczyna, dowód w kodzie, brak alternatywnej interpretacji
- **70–89:** Prawdopodobny problem — wymaga kontekstu, który może być poza diff
- **50–69:** Możliwy problem — może być celowy design lub pre-existing stan
- **0–49:** Wątpliwy — prawdopodobnie false positive, brak dowodu w kodzie

**Próg:** Zachowaj tylko findings z confidence **>= 80**.

Findings o severity **INFO** przechodzą bez scoringu (zawsze zachowane).

### Weryfikacja double-throw w bloku catch

Gdy finding dotyczy potencjalnego rzucenia wyjątku wewnątrz bloku `catch` (swallowing oryginału): sprawdź czy oryginalny wyjątek i wtórny wyjątek produkują **identyczny komunikat**. Jeśli tak — strata diagnostyczna jest minimalna i finding należy odrzucić (lub zdegradować do INFO). Finding jest zasadny tylko gdy wtórny wyjątek niesie inny komunikat i tym samym ukrywa rzeczywistą przyczynę błędu.

---

## Szablon raportu CR (Step 9)

Utwórz `.ai/tasks/{ISSUE_ID}/cr.md` (katalog już istnieje po `mkdir -p` z Step 9 w `workflow.md`).

> **Re-CR:** Przy ponownym review nie nadpisuj pliku — appenduj sekcję `## Re-CR #{N}: {data}` na końcu. Format sekcji Re-CR opisany w `references/re-review.md` (Step R4).

```markdown
# Code Review: #{ISSUE_ID} — {subject}

**Data:** {YYYY-MM-DD}
**Branch:** feature/{ISSUE_ID}
**Zakres:** {N} commit(ów): {DIFF_BASE}..{DIFF_HEAD}
**Reviewer:** AI (Claude Code)
**Redmine:** https://redmine.evolpe.net/issues/{ISSUE_ID}

## Kontekst zagadnienia

{Krótkie podsumowanie wymagań z opisu Redmine}

## Zmienione pliki

{Lista plików ze statystykami +/- linii}

## Znalezione problemy

### CRITICAL
- [ ] `{plik}:{linia}` — **[Kategoria] Opis** — szczegóły → sugestia *(tylko ścieżka równoległa: [confidence: XX])*

### WARNING
- [ ] `{plik}:{linia}` — **[Kategoria] Opis** — szczegóły → sugestia *(tylko ścieżka równoległa: [confidence: XX])*

### INFO
- `{plik}:{linia}` — {opis}

## Podsumowanie

{Ogólna ocena: czy kod jest gotowy do merge? Co trzeba poprawić?}

**Werdykt:** APPROVED / CHANGES REQUESTED / NEEDS DISCUSSION

Definicje werdyktu:
- **APPROVED** — brak CRITICAL i WARNING (ewentualnie tylko INFO); kod gotowy do merge
- **CHANGES REQUESTED** — co najmniej jedno CRITICAL lub WARNING; wymagane poprawki przed merge
- **NEEDS DISCUSSION** — znaleziono problemy architektoniczne lub niejasności wymagające rozmowy z zespołem (np. wątpliwości co do projektu, sprzeczność z wymaganiami której nie można rozstrzygnąć z kodu)

## Odrzucone (confidence < 80)

*(Sekcję pomiń jeśli lista jest pusta lub review był na ścieżce sekwencyjnej)*

| Finding | Agent | Confidence | Powód odrzucenia |
|---|---|---|---|
| {opis} | Agent {N} — {nazwa} | {score} | {powód} |
```

---

## Inline FIXME komentarze (Step 10)

Dla każdego finding o severity **CRITICAL** lub **WARNING** — dodaj komentarz w pliku źródłowym **nad** problematyczną linią.

**NIE** dodawaj FIXME dla findings o severity INFO.

Składnia komentarza wg rozszerzenia pliku:

| Rozszerzenia | Składnia |
|---|---|
| `.js` `.ts` `.jsx` `.tsx` `.vue` `.php` `.java` `.cs` `.go` `.swift` `.kt` `.rs` | `// FIXME - AI CR - {opis}` |
| `.py` `.rb` `.sh` `.yaml` `.yml` | `# FIXME - AI CR - {opis}` |
| `.html` `.xml` `.twig` `.tpl` | `<!-- FIXME - AI CR - {opis} -->` |
| `.css` `.scss` `.less` | `/* FIXME - AI CR - {opis} */` |
| `.sql` | `-- FIXME - AI CR - {opis}` |
| `.json` | *(pomiń — format nie obsługuje komentarzy; finding CRITICAL/WARNING nadal trafia do raportu CR, ale bez FIXME w pliku)* |

Jeśli deweloper nie zgadza się z findingiem, może dodać własny komentarz bezpośrednio za lub pod komentarzem FIXME — obsługa tego scenariusza opisana w `references/re-review.md` (Step R2).
