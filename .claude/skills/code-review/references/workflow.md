# Workflow Code Review — 13 kroków

## Spis treści

- [Step 1 — Parsuj numer zagadnienia](#step-1)
- [Step 2 — Pobierz kontekst z Redmine](#step-2)
- [Step 3 — Walidacja typu zagadnienia](#step-3)
- [Step 4 — Pobierz najnowsze zmiany](#step-4)
- [Step 5 — Znajdź i przełącz na feature branch](#step-5)
- [Step 6 — Ustal zakres diff](#step-6)
  - [Step 6b — Zidentyfikuj autora kodu](#step-6b) *(po 6a)*
- [Step 7 — Przeprowadź code review](#step-7)
- [Step 8 — Napisz raport CR](#step-8)
- [Step 9 — Dodaj inline FIXME komentarze](#step-9)
- [Step 10 — Zapytaj usera o commit](#step-10)
- [Step 11 — Commit](#step-11)
- [Step 12 — Squash i merge do DEFAULT_BRANCH (tylko APPROVED)](#step-12)
- [Step 13 — Utwórz zagadnienie Task/Bug w Redmine](#step-13)
- [Zakończenie](#zakończenie)
- [Re-CR](#re-cr) → `references/re-review.md`

---

## Step 1 — Parsuj numer zagadnienia

Najpierw sprawdź czy jesteś wewnątrz repozytorium git:

```bash
git rev-parse --is-inside-work-tree
```

Jeśli polecenie zwróci błąd — zatrzymaj się: „Nie jestem wewnątrz repozytorium git. Otwórz terminal w katalogu projektu."

Wyciągnij numer zagadnienia:

**Jeśli wywołano jako slash command** (`/code-review`):
- Sprawdź `$ARGUMENTS`
- Jeśli to liczba (np. `184819`) — użyj bezpośrednio
- Jeśli to URL (np. `https://redmine.evolpe.net/issues/184819`) — wyciągnij numer z path

**Jeśli wywołano automatycznie z kontekstu rozmowy** (`$ARGUMENTS` jest pusty):
- Wyciągnij numer z ostatniej wiadomości usera
- Szukaj liczby poprzedzonej `#`, słowem "zagadnienie", "issue", "CR", lub samodzielnej liczby wyglądającej jak ID

**Jeśli nie da się ustalić numeru** — zatrzymaj się i zapytaj: „Podaj numer zagadnienia Redmine lub URL."

Zapamiętaj wyciągnięty numer jako `ISSUE_ID`.

---

## Step 2 — Pobierz kontekst z Redmine

→ Szczegóły w `references/redmine-integration.md` (sekcja "Pobieranie zagadnienia")
→ Jeśli brak Redmine MCP — patrz sekcja "Tryb manualny — Step 2" w tym samym pliku

---

## Step 3 — Walidacja typu zagadnienia

→ Szczegóły w `references/redmine-integration.md` (sekcja "Walidacja trackera")

---

## Step 4 — Pobierz najnowsze zmiany

```bash
git fetch --all
```

Jeśli `git fetch` zakończy się błędem autoryzacji (SSH key, credentials) — poinformuj usera:
```
git fetch --all nie powiódł się (błąd autoryzacji). Możliwe przyczyny:
  - brak klucza SSH lub wygasłe credentials
  - brak dostępu do remote

Możesz kontynuować bez fetch — review zostanie wykonany na lokalnym stanie brancha.
Czy kontynuować bez fetch?
```
Jeśli user potwierdzi — kontynuuj. Jeśli odmówi — zakończ.

**Następnie uruchom skrypt zbierający dane o gałęziach:**

```bash
bash .claude/skills/code-review/scripts/collect_branch_info.sh {ISSUE_ID}
```

Skrypt zwraca JSON z polami:
- `default_branch` — wykryta gałąź bazowa (może być pusta jeśli nie znaleziono)
- `current_branch` — aktualny branch
- `feature_branch` — `feature/{ISSUE_ID}` jeśli istnieje, lub `""` jeśli nie
- `alternate_branches` — lista branchy zawierających `{ISSUE_ID}` (gdy brak dokładnego dopasowania)
- `is_clean` — czy working tree jest czysty
- `dirty_count` — liczba niezacommitowanych plików
- `behind_count` — ile commitów feature branch jest za `origin/{DEFAULT_BRANCH}`
- `remote_ahead_count` — ile commitów `origin/feature/{ISSUE_ID}` ma więcej niż lokalny branch (czy trzeba `git pull --rebase`)

Zapamiętaj `default_branch` jako `DEFAULT_BRANCH`. Jeśli pole jest puste — zapytaj usera: „Nie mogę ustalić domyślnej gałęzi. Jak nazywa się gałąź bazowa (np. `main`, `develop`, `master`)?"

---

## Step 5 — Znajdź i przełącz na feature branch

Użyj danych z `collect_branch_info.sh` (Step 4):

**Jeśli `is_clean` = false** — zatrzymaj się i poinformuj usera:
```
Working tree ma niezacommitowane zmiany ({dirty_count} plik(ów)). Przed przełączeniem na feature branch
zapisz lub schowaj swoje zmiany:
  git stash        (odłóż zmiany tymczasowo)
  git commit ...   (zacommituj zmiany)
```
Czekaj na potwierdzenie usera że zmiany są zabezpieczone, zanim przejdziesz dalej.

**Jeśli `feature_branch` jest puste:**
- Jeśli `alternate_branches` jest niepuste — wyświetl listę i zapytaj usera którego użyć
- Jeśli `alternate_branches` też puste — zatrzymaj się: „Nie znaleziono brancha dla zagadnienia #{ISSUE_ID}. Upewnij się, że feature branch istnieje."

**Jeśli `feature_branch` = `feature/{ISSUE_ID}`:**
- Jeśli `current_branch` = `feature/{ISSUE_ID}` — kontynuuj (już jesteś na właściwym branchu)
- Jeśli nie — przełącz się:
  - Lokalnie: `git checkout feature/{ISSUE_ID}`
  - Tylko zdalnie: `git checkout -b feature/{ISSUE_ID} origin/feature/{ISSUE_ID}`

**Po checkout — zaktualizuj branch do wersji zdalnej:**

Jeśli `remote_ahead_count` > 0 — wykonaj:
```bash
git pull --rebase origin feature/{ISSUE_ID}
```
Jeśli `git pull --rebase` zakończy się konfliktem — zatrzymaj się i poinformuj usera:
```
git pull --rebase nie powiódł się — konflikt z origin/feature/{ISSUE_ID}.
Rozwiąż konflikty ręcznie i wróć do review.
```

**Jeśli `behind_count` > 0** — poinformuj usera (nie blokuj):
```
Uwaga: feature/{ISSUE_ID} jest {behind_count} commit(ów) za origin/{DEFAULT_BRANCH}.
Branch może mieć konflikty przy merge — rozważ rebase przed review:
  git rebase origin/{DEFAULT_BRANCH}
Kontynuuję review na aktualnym stanie brancha.
```

---

## Step 6 — Ustal zakres diff

### 6a. Zbierz commity i ustal zakres

Uruchom skrypt (na feature branchu po Step 5):

```bash
bash .claude/skills/code-review/scripts/collect_commit_scope.sh {ISSUE_ID}
```

Skrypt zwraca JSON z polami:
- `is_re_review` — czy wykryto poprzednie commity CR (`#BUG|`)
- `last_cr_hash` — hash ostatniego commita CR (tylko gdy `is_re_review` = true, inaczej pusty string)
- `feature_commits` — lista `{hash, message}` commitów feature do przeglądu
- `cr_commits` — lista commitów CR (poprzednie code review)
- `feature_count` — liczba commitów feature
- `diff_base` — `{oldest_hash}~1`
- `diff_head` — `{newest_hash}`
- `error` (opcjonalnie) — opis błędu gdy brak commitów

Jeśli JSON zawiera `error`:
- `no_commits` → zatrzymaj się: „Nie znaleziono commitów odwołujących się do #{ISSUE_ID}. Upewnij się, że commity zawierają `ref #{ISSUE_ID}` w wiadomości."
- `no_new_commits` → zatrzymaj się: „Brak nowych zmian do przeglądu od ostatniego CR."

Zapamiętaj `diff_base` jako `DIFF_BASE`, `diff_head` jako `DIFF_HEAD` oraz `last_cr_hash` jako `LAST_CR_COMMIT` (używany w re-review.md Step R2).

### 6b. Zidentyfikuj autora kodu

```bash
bash .claude/skills/code-review/scripts/get_author_info.sh {ISSUE_ID} {DIFF_BASE} {DIFF_HEAD}
```

Skrypt zwraca JSON z polami:
- `author_email` — pełny email (np. `aleksander.bak@evolpe.pl`)
- `author_name` — pełne imię i nazwisko z gita
- `author_login` — część przed `@` (np. `aleksander.bak`)
- `is_multiple_authors` — czy w zakresie commitowało więcej niż jedna osoba
- `all_authors` — lista `{email, name, login}` wszystkich autorów (gdy `is_multiple_authors` = true)

Jeśli JSON zawiera `error: no_feature_commits` — zatrzymaj się i poinformuj usera.

Zapamiętaj `author_login` jako `AUTHOR_LOGIN` — potrzebny w Step 11 do formatu commita.
Jeśli `is_multiple_authors` = true — zanotuj wszystkich autorów z `all_authors` do późniejszego użycia.

Pytanie o to, do kogo przypisać zagadnienie CR, jest zadawane dopiero w Step 13 — tuż przed jego utworzeniem, kiedy wiadomo już czy w ogóle powstaną jakieś findings.

### 6c. Potwierdź zakres z userem

```
Znaleziono {feature_count} commit(ów) powiązanych z #{ISSUE_ID}:

{hash1} {message1}
{hash2} {message2}
...

Czy code review ma dotyczyć właśnie tych commitów?
```

Czekaj na potwierdzenie. Jeśli user wskaże inne commity — dostosuj zakres i ustaw `DIFF_BASE`/`DIFF_HEAD` ręcznie.

### 6d. Routing re-review

Jeśli `is_re_review` = true (skrypt wykrył commity CR `#BUG|` na branchu) — **przejdź do `references/re-review.md` Step R0** zamiast kontynuować Step 6e i Step 7.

`LAST_CR_COMMIT` jest dostępny od Step 6a — re-review.md używa go jako zakresu diffu (`LAST_CR_COMMIT..HEAD`).

### 6e. Pokaż statystyki (tylko normalny flow — pomiń jeśli `is_re_review` = true)

```bash
git diff {DIFF_BASE}..{DIFF_HEAD} --stat
```

---

## Step 7 — Przeprowadź code review

### 7a. Zbierz materiał

Uruchom skrypt zbierający statystyki diffu:

```bash
bash .claude/skills/code-review/scripts/collect_diff_stats.sh {DIFF_BASE} {DIFF_HEAD}
```

Skrypt zwraca JSON z polami:
- `total_lines` — łączna liczba zmienionych linii
- `review_mode` — `sequential` (<80), `parallel` (80–499), `chunked` (≥500)
- `reviewable_count` / `vendor_count` / `binary_count` — liczby plików wg kategorii
- `files[]` — per plik: `path`, `diff_lines`, `file_lines`, `is_binary`, `is_vendor`, `context_strategy`

Pole `context_strategy` per plik przyjmuje wartości:
- `full_file` — odczytaj pełną aktualną wersję pliku
- `extended_diff` — użyj `git diff -U30 {DIFF_BASE}..{DIFF_HEAD} -- {plik}`
- `skip` — pomiń (vendor, generated, binarny)

Jeśli `error` = `empty_diff` — zatrzymaj się: „Brak zmian do przeglądu."

**Po odczytaniu JSON:**

Pobierz właściwy diff do przekazania agentom:
```bash
git diff {DIFF_BASE}..{DIFF_HEAD}
```

Dla każdego pliku z `context_strategy` = `full_file` — odczytaj pełną wersję.
Dla każdego pliku z `context_strategy` = `extended_diff` — pobierz rozszerzony diff:
```bash
git diff -U30 {DIFF_BASE}..{DIFF_HEAD} -- {plik}
```

**Jeśli `vendor_count` > 0** — odnotuj: „Pominięto {vendor_count} plik(ów) vendor/generated."

**Jeśli `binary_count` > 0** — poinformuj usera:
```
Pominięto {binary_count} plik(ów) binarnych (brak możliwości analizy treści):
  - {plik1}
  - {plik2}
```
Jeśli plik binarny wydaje się nieoczekiwany w kontekście zagadnienia (np. skompilowany `.class` w repo PHP) — odnotuj jako finding INFO.

**Pusty opis zagadnienia:** Jeśli opis lub kryteria akceptacji w Redmine są puste — poinformuj agentów, że Agent 1 (Poprawność) ma ograniczony kontekst wymagań. Agent 1 wtedy skupia się wyłącznie na spójności z tytułem zagadnienia i ogólnych konwencjach projektu zamiast na zgodności z kryteriami akceptacji.

**Skille:** Przejrzyj skille załadowane w kontekście rozmowy (pomiń `code-review`). Na podstawie diffa i kontekstu zagadnienia z Redmine zdecyduj, które są trafne. Dla każdego wybranego skilla — **odczytaj jego `SKILL.md`** narzędziem Read (ścieżka: `.claude/skills/{nazwa-skilla}/SKILL.md`) i wyciągnij z niego kluczowe reguły. Przekaż te reguły agentom review jako dodatkowy kontekst obok baseline kryteriów.

Jeśli jakiś skill został wybrany, **wypisz podsumowanie**:

```
📋 Użyte skille:

| Skill | Powód wyboru |
|---|---|
| {nazwa-skilla} | {krótkie uzasadnienie na podstawie diffa/zagadnienia} |
```

**Code Review Overrides:** Po odczytaniu SKILL.md trafnych skilli, sprawdź czy którykolwiek z nich zawiera sekcję **„## Code Review Overrides"**. Jeśli tak:

1. Przeczytaj sekcję overrides — każda podsekcja (### nagłówek) to jedna kategoria z **wzorcem ścieżek** i **instrukcjami review**
2. Zidentyfikuj pliki z diffa pasujące do wzorców
3. **Zastosuj instrukcje z overrides dokładnie tak, jak je opisuje skill systemowy** — override definiuje jak reviewować pasujące pliki. Może to być np.:
   - Wydzielenie plików do osobnego subagenta z własnym zakresem sprawdzeń
   - Dodatkowe reguły przekazane standardowym 5 agentom
   - Inna konfiguracja agentów lub priorytetów
   - Inne podejście — skill systemowy jest autorytetem
4. Findings z overrides włącz do wspólnej listy findings w Step 7d (scalanie przebiegają normalnie)
5. Jeśli override wydziela pliki do osobnego review — pliki standardowe przechodzą normalnie przez Steps 7b–7f. Jeśli **wszystkie** pliki pasują do overrides i override je wydziela — pomiń Steps 7b–7f i przejdź do Step 7d

Poinformuj usera o zastosowanych overrides:

```
🔀 Code Review Overrides (z {nazwa-skilla}):

| Kategoria | Pliki | Tryb review |
|---|---|---|
| {nazwa kategorii} | {N} plik(ów) | {krótki opis trybu z overrides} |

Pozostałe pliki ({M}): standardowy review 5 agentów.
```

Jeśli żaden skill nie definiuje overrides — kontynuuj normalnie (wszystkie pliki trafiają do standardowego review).

### 7b. Heurystyka rozmiaru

Użyj `review_mode` z `collect_diff_stats.sh` (Step 7a):

- **`sequential`** (total_lines < 80) → pomiń agentów, przeprowadź review sekwencyjnie (oceń 5 kryteriów jedno po drugim wg `references/review-criteria.md`, uwzględniając reguły ze skilli (7a)), przejdź do Step 7f.
- **`parallel`** (80–499 linii) → kontynuuj do 7c.
- **`chunked`** (≥500 linii) → przejdź do 7b-bis.

### 7b-bis. Bardzo duże diffy (>500 linii)

Jeśli łączna liczba zmienionych linii przekracza **500**, podziel diff na chunki po plikach:

1. Zgrupuj zmienione pliki tematycznie (np. modele razem, widoki razem, testy razem) — maksymalnie **3 grupy**
2. Poinformuj usera: „Diff jest duży ({N} linii) — review może potrwać dłużej."
3. Dla każdej grupy uruchom osobny zestaw 5 agentów zgodnie z Step 7c, przekazując im tylko diff plików z danej grupy (nie cały diff). Grupy przetwarzaj **sekwencyjnie** (jedna po drugiej), nie wszystkie naraz — łączna liczba równoległych agentów nie może przekroczyć 5 w danym momencie.
4. Zbierz findings ze wszystkich grup i scal je łącznie w Step 7d — duplikaty między grupami usuwaj według tych samych reguł co duplikaty między agentami

---

### 7c. Uruchom 5 równoległych agentów review

Jeśli w 7a wybrano jakiekolwiek skille, **wypisz mapowanie** przed uruchomieniem agentów:

```
🤖 Przypisanie skilli do agentów review:

Każdy z 5 agentów otrzyma reguły z:
- {nazwa-skilla} → reguły: {lista kluczowych reguł przekazanych}

Mapowanie agent → dodatkowy kontekst:
- Agent 1 (Poprawność) — {skille trafne dla wymagań modułowych}
- Agent 2 (Błędy logiczne) — {skille trafne dla architektury/logiki}
- Agent 3 (Bezpieczeństwo) — {skille trafne dla walidacji/bezpieczeństwa}
- Agent 4 (Wydajność) — {skille trafne dla wydajności/indeksów}
- Agent 5 (Jakość kodu) — {skille trafne dla konwencji/DRY}
```

Uruchom jednocześnie 5 niezależnych agentów. Każdy agent dostaje:
- Pełny diff (`git diff {DIFF_BASE}..{DIFF_HEAD}`)
- Kontekst plików (pełne wersje lub rozszerzony diff wg reguły z Step 7a)
- Kontekst zagadnienia z Redmine — **zróżnicowany per agent:**
  - Agent 1 (Poprawność): pełny kontekst — tytuł, opis, kryteria akceptacji
  - Agenci 2–5: tylko tytuł zagadnienia (opis i kryteria akceptacji niepotrzebne do oceny kodu)
- **Jedno** kryterium z `references/review-criteria.md` (sekcja "Kryteria review — baseline")

Przypisanie modeli:
- Agent 1 (Sonnet) → Agent 1 — Poprawność
- Agent 2 (Sonnet) → Agent 2 — Błędy logiczne
- Agent 3 (Sonnet) → Agent 3 — Bezpieczeństwo
- Agent 4 (Sonnet) → Agent 4 — Wydajność
- Agent 5 (Sonnet) → Agent 5 — Jakość kodu

Każdy agent zwraca findings w formacie z sekcji "Format findings" w `references/review-criteria.md`.

> Reguły z trafnych skilli (zebrane w 7a) uzupełniają baseline kryteria agentów — nie zastępują ich.

### 7d. Zbierz i scal wyniki

Połącz findings ze wszystkich 5 agentów w jedną listę. Zduplikowane findings (ten sam plik + linia zgłoszona przez kilka agentów) — zachowaj jeden, wybierz wyższe severity i notuj oba agenty.

**Scalanie confidence dla duplikatów:** Jeśli ten sam finding pojawia się u 2 lub więcej agentów i żaden z nich nie przekracza progu 80, zachowaj go z `max(confidence)` spośród wszystkich zgłoszeń. Powtórzenie przez niezależnych agentów jest samo w sobie sygnałem, że problem jest realny.

### 7e. Confidence scoring

Dla każdego finding o severity **CRITICAL** lub **WARNING** uruchom równoległego agenta Haiku — maksymalnie **10 agentów jednocześnie**. Jeśli findings jest więcej niż 10, przetwarzaj w batchach po 10. Każdy agent Haiku:
- Otrzymuje:
  - treść finding (plik, linia, opis, sugestia)
  - diff pliku z findingiem — dobierz kontekst do rozmiaru pliku:
    - plik ≤ 300 linii: `git diff {DIFF_BASE}..{DIFF_HEAD} -- {plik}` (pełny diff pliku)
    - plik > 300 linii: `git diff -U5 {DIFF_BASE}..{DIFF_HEAD} -- {plik}` (zredukowany kontekst diffa)
  - fragment pliku wokół problematycznej linii (±30 linii): `sed -n '{start},{end}p' {plik}`
- Ocenia pewność wg rubric z `references/review-criteria.md` (sekcja "Confidence scoring")
- Zwraca: score (0–100) + uzasadnienie

**Weryfikacja po scoringu:** Jeśli chcesz zweryfikować konkretny finding w kodzie, używaj `grep` lub `git diff -- {plik}` zamiast `Read` całego pliku. `Read` pełnego pliku jest uzasadniony tylko gdy grep nie daje wystarczającego kontekstu (np. potrzeba zrozumienia szerokiego otoczenia funkcji). Unikaj szczególnie `Read` dużych plików (>200 linii) jeśli grep już zwrócił pasujące fragmenty.

Findings o severity **INFO** przechodzą bez scoringu.

### 7f. Filtruj i finalizuj

**Ścieżka sekwencyjna (z 7b, <80 linii):** Brak confidence scoringu — wszystkie findings przechodzą. Posortuj: CRITICAL → WARNING → INFO. Sekcja "Odrzucone" w raporcie jest pomijana.

**Ścieżka równoległa (z 7e, >=80 linii):**
- Zachowaj findings z confidence >= progu (patrz `references/review-criteria.md` sekcja "Confidence scoring") oraz wszystkie INFO
- Odrzucone findings (poniżej progu) zachowaj osobno — będą w sekcji "Odrzucone" w raporcie
- Posortuj finalne findings: CRITICAL → WARNING → INFO, w obrębie severity malejąco po confidence

---

## Step 8 — Napisz raport CR

→ Szczegóły w `references/review-criteria.md` (sekcja "Szablon raportu CR")

Ścieżka raportu: `.ai/tasks/{ISSUE_ID}/cr.md`

Upewnij się że katalog istnieje: `mkdir -p .ai/tasks/{ISSUE_ID}`

---

## Step 9 — Dodaj inline FIXME komentarze

→ Szczegóły w `references/review-criteria.md` (sekcja "Inline FIXME komentarze")

---

## Step 10 — Zapytaj usera o commit

Wyświetl podsumowanie i poczekaj na zgodę:

```
Code review zakończony dla #{ISSUE_ID}.

Raport: .ai/tasks/{ISSUE_ID}/cr.md
Znaleziono: {X} CRITICAL, {Y} WARNING, {Z} INFO
[Odrzucone (confidence < 80): {N} findings]  ← tylko dla ścieżki równoległej (>=80 linii)
Dodano FIXME komentarzy: {N}
Werdykt: {APPROVED / CHANGES REQUESTED / NEEDS DISCUSSION}

[Jeśli NEEDS DISCUSSION: "Znaleziono problemy wymagające rozmowy z zespołem — nie zostanie utworzone zagadnienie w Redmine. Raport CR i komentarze FIXME zostaną zacommitowane normalnie."]

Czy mogę zacommitować raport CR i komentarze FIXME?
```

User może mieć dodatkowe uwagi — wprowadź poprawki jeśli poprosi. Jeśli odmówi commita — zakończ.

---

## Step 11 — Commit

Po uzyskaniu zgody:

1. Użyj `AUTHOR_LOGIN` z Step 6b (np. `aleksander.bak`)

2. Sprawdź czy `.ai/tasks/` nie jest w `.gitignore`:
   ```bash
   git check-ignore -v .ai/tasks/
   ```
   Jeśli jest ignorowany — poinformuj usera:
   ```
   Katalog .ai/tasks/ jest wykluczony przez .gitignore — raport CR nie zostanie zacommitowany.
   Dodaj wyjątek do .gitignore:
     !.ai/tasks/
   lub zmień konfigurację projektu. Poczekam na potwierdzenie.
   ```
   Czekaj na potwierdzenie usera przed kontynuacją.

3. Dodaj pliki — **tylko** raport CR i pliki z dodanymi komentarzami FIXME:
   ```bash
   git add .ai/tasks/{ISSUE_ID}/cr.md
   git add {plik1} {plik2} ...  # pliki które dostały komentarze FIXME w Step 9
   ```
   Nie używaj `git add -u` ani `git add .` — mogłoby to wciągnąć do commitu niezwiązane zmiany z working tree.

4. Utwórz commit w formacie: `ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}`

   Gdzie `{ISSUE_SUBJECT}` to tytuł zagadnienia z Redmine (z Step 2).

   ```bash
   git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}"
   ```

   **Ważne:** NIE dodawaj `Co-Authored-By` ani żadnych dodatkowych linii do wiadomości commita.

5. Wyświetl hash commita. Zapytaj usera czy chce zrobić push:

   ```bash
   git push origin feature/{ISSUE_ID}
   ```

   Jeśli branch nie ma jeszcze remote tracking (nowy branch):
   ```bash
   git push -u origin feature/{ISSUE_ID}
   ```

   Jeśli push jest odrzucony z powodu branch protection rules (np. "protected branch", "must use merge request") — poinformuj usera:
   ```
   Push odrzucony — branch jest chroniony. Aby wysłać commit CR musisz:
     1. Otworzyć Merge Request / Pull Request z brancha feature/{ISSUE_ID}
     lub
     2. Poprosić administratora o tymczasowe wyłączenie ochrony brancha.
   ```
   Zakończ bez push — commit jest lokalnie gotowy.

---

## Step 12 — Squash i merge do DEFAULT_BRANCH (tylko APPROVED)

Wykonaj ten krok **tylko jeśli werdykt to APPROVED** (brak CRITICAL i WARNING). Jeśli werdykt to CHANGES REQUESTED lub NEEDS DISCUSSION — pomiń i przejdź do Step 13.

### 12-1. Propozycja squash

Sprawdź czy squash jest możliwy:

```bash
git log --oneline --merges origin/{DEFAULT_BRANCH}..HEAD
```

Jeśli są merge commity → poinformuj usera że squash niemożliwy (merge commity w historii) i przejdź do 12-2.

Jeśli jest tylko 1 commit na branchu → pomiń squash, przejdź do 12-2.

W pozostałych przypadkach — **zaproponuj squash i CZEKAJ na potwierdzenie usera**:

```
CR zakończony: APPROVED. Propozycja: squash {N} commitów do jednego.

Commity do squash:
{lista commitów od początku brancha do HEAD}

Wiadomość po squash: "ref #{ISSUE_ID} {ISSUE_SUBJECT}"
(bez #BUG, #FIX — czysty commit feature)

Czy wykonać squash? (operacja nieodwracalna lokalnie)
```

Po potwierdzeniu — sekwencja squash (bez interactive rebase):

```bash
SQUASH_BASE=$(git merge-base origin/{DEFAULT_BRANCH} HEAD)
git reset --soft $SQUASH_BASE
git add .ai/tasks/{ISSUE_ID}/cr.md  # upewnij się że cr.md jest w staged
git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT}"
```

**Ważne:** po `git reset --soft` sprawdź staged files (`git status`) i upewnij się że `.ai/tasks/{ISSUE_ID}/cr.md` jest zaindeksowany przed commitem.

### 12-2. Propozycja merge do DEFAULT_BRANCH

**CZEKAJ na potwierdzenie usera** przed wykonaniem merge:

```
Squash gotowy (lub pominięty). Czy wykonać merge do {DEFAULT_BRANCH}?

  git checkout {DEFAULT_BRANCH}
  git pull origin {DEFAULT_BRANCH}
  git merge feature/{ISSUE_ID} --no-ff -m "Merge feature/{ISSUE_ID} into {DEFAULT_BRANCH}"
```

Po potwierdzeniu:

```bash
git checkout {DEFAULT_BRANCH}
git pull origin {DEFAULT_BRANCH}
git merge feature/{ISSUE_ID} --no-ff -m "Merge feature/{ISSUE_ID} into {DEFAULT_BRANCH}"
```

Jeśli merge conflict → poinformuj usera i przerwij:

```
Merge zakończył się konfliktem. Rozwiąż konflikty ręcznie:
  git status        (sprawdź które pliki mają konflikty)
  git mergetool     (opcjonalnie — narzędzie do mergowania)
  git add {pliki}   (po rozwiązaniu konfliktów)
  git commit        (finalizacja merge)
```

Po udanym merge zapytaj o push:

```bash
git push origin {DEFAULT_BRANCH}
```

---

## Step 13 — Utwórz zagadnienie Task/Bug w Redmine

Przed utworzeniem zagadnienia wykonaj lookup użytkownika i potwierdź assignee:
→ `references/redmine-integration.md` (sekcja "Lookup użytkownika w Redmine — Step 13")

→ Następnie szczegóły tworzenia w `references/redmine-integration.md` (sekcja "Tworzenie zagadnienia CR")

W opisie zagadnienia Redmine umieść link do raportu CR: `.ai/tasks/{ISSUE_ID}/cr.md`

---

## Zakończenie

Po zakończeniu wyświetl podsumowanie końcowe.

**Ścieżka CHANGES REQUESTED** (były CRITICAL lub WARNING):

```
✅ Code review zakończony dla #{ISSUE_ID}.

Raport: .ai/tasks/{ISSUE_ID}/cr.md
Commit CR: {commit_hash}
Zagadnienie Redmine: #{new_issue_id} — CR BUG: {ISSUE_SUBJECT}

Znaleziono: {X} CRITICAL, {Y} WARNING, {Z} INFO
Dodano FIXME komentarzy: {N}
```

**Ścieżka APPROVED** (brak CRITICAL i WARNING):

```
✅ Code review zakończony dla #{ISSUE_ID}.

Raport: .ai/tasks/{ISSUE_ID}/cr.md
Commit CR: {commit_hash}
[Squash: {squash_commit_hash}]  ← tylko jeśli wykonano squash
[Merge: feature/{ISSUE_ID} → {DEFAULT_BRANCH}]  ← tylko jeśli wykonano merge

Werdykt: APPROVED — brak krytycznych ani ostrzegawczych problemów.
[Opcjonalnie: znaleziono {Z} INFO — drobne sugestie bez blokowania merge]
Nie utworzono zagadnienia w Redmine.
```

**Ścieżka NEEDS DISCUSSION** (wątpliwości architektoniczne lub sprzeczność z wymaganiami):

```
✅ Code review zakończony dla #{ISSUE_ID}.

Raport: .ai/tasks/{ISSUE_ID}/cr.md
Commit CR: {commit_hash}

Werdykt: NEEDS DISCUSSION — znaleziono problemy wymagające rozmowy z zespołem.
[Opis kwestii do omówienia]
Nie utworzono zagadnienia w Redmine — wymagana rozmowa przed podjęciem działań.
```

Ścieżka NEEDS DISCUSSION nie tworzy zagadnienia w Redmine (Step 13 jest pomijany). Raport CR i FIXME są commitowane normalnie (Steps 10–11).

---

## Re-CR

Jeśli w Step 6d (po potwierdzeniu zakresu w Step 6c) wykryto commity CR na branchu (`#BUG|`), przejdź do pełnej procedury re-CR:

→ `references/re-review.md`

Procedura re-CR obejmuje: weryfikację poprzednich findings, review nowych zmian, aktualizację raportu (append sekcji Re-CR), ustalenie werdyktu. Przy werdykcie APPROVED: squash commitów + merge do {DEFAULT_BRANCH} (po potwierdzeniu usera).
