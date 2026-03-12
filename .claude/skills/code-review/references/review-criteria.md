# Kryteria Code Review i Format Raportu

## Kryteria review — baseline (Step 8c, per-agent)

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

> Jeśli w `.claude/skills/` istnieje dedykowany skill do code review dla danego systemu — użyj jego kryteriów zamiast powyższych. Uruchom po jednym agencie na każde kryterium z dedykowanego skilla.

---

## Format findings (Step 8c)

Dla każdego znalezionego problemu zapisz:

- **Severity:** `CRITICAL` / `WARNING` / `INFO`
- **Plik i linia**
- **Krótki opis problemu**
- **Sugestia naprawy**

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

---

## Szablon raportu CR (Step 9)

Upewnij się że katalog `.ai/tasks/{ISSUE_ID}/` istnieje (`mkdir -p .ai/tasks/{ISSUE_ID}`), następnie utwórz `.ai/tasks/{ISSUE_ID}/cr.md`:

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
- [ ] `{plik}:{linia}` — **[Kategoria] Opis** — szczegóły → sugestia [confidence: XX]

### WARNING
- [ ] `{plik}:{linia}` — **[Kategoria] Opis** — szczegóły → sugestia [confidence: XX]

### INFO
- `{plik}:{linia}` — {opis}

## Podsumowanie

{Ogólna ocena: czy kod jest gotowy do merge? Co trzeba poprawić?}

**Werdykt:** APPROVED / CHANGES REQUESTED / NEEDS DISCUSSION

## Odrzucone (confidence < 80)

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
| `.js` `.ts` `.jsx` `.tsx` `.php` `.java` `.cs` `.go` `.swift` `.kt` | `// FIXME - AI CR - {opis}` |
| `.py` `.rb` `.sh` `.yaml` `.yml` | `# FIXME - AI CR - {opis}` |
| `.html` `.xml` `.twig` `.tpl` | `<!-- FIXME - AI CR - {opis} -->` |
| `.css` `.scss` `.less` | `/* FIXME - AI CR - {opis} */` |
| `.sql` | `-- FIXME - AI CR - {opis}` |
