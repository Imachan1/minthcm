# Workflow Code Review — 13 kroków

## Step 1 — Parsuj numer zagadnienia

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

---

## Step 3 — Walidacja typu zagadnienia

→ Szczegóły w `references/redmine-integration.md` (sekcja "Walidacja trackera")

---

## Step 4 — Pobierz najnowsze zmiany

```bash
git fetch --all
```

---

## Step 5 — Znajdź i przełącz na feature branch

Sprawdź czy branch `feature/{ISSUE_ID}` istnieje:

```bash
git branch -a | grep "feature/{ISSUE_ID}"
```

**Jeśli branch NIE istnieje:**
- Szukaj alternatywnych branchy zawierających `{ISSUE_ID}`:
  ```bash
  git branch -a | grep "{ISSUE_ID}"
  ```
- Jeśli znaleziono — wyświetl listę i zapytaj usera którego użyć
- Jeśli brak — zatrzymaj się: „Nie znaleziono brancha dla zagadnienia #{ISSUE_ID}. Upewnij się, że feature branch istnieje."

**Jeśli branch istnieje:**
- Sprawdź aktualny branch: `git branch --show-current`
- Jeśli już jesteśmy na `feature/{ISSUE_ID}` — kontynuuj
- Jeśli nie — przełącz się:
  - Lokalnie: `git checkout feature/{ISSUE_ID}`
  - Tylko zdalnie: `git checkout -b feature/{ISSUE_ID} origin/feature/{ISSUE_ID}`

---

## Step 6 — Zidentyfikuj autora kodu (assignee CR)

Pobierz autora ostatniego commita (pomijając commity CR z wzorcem `BUG|`):

```bash
git log HEAD --oneline --invert-grep --grep="BUG|" -1 --format="%ae %an"
```

Wyciągnij:
- **AUTHOR_EMAIL** — pełny email (np. `aleksander.bak@evolpe.pl`)
- **AUTHOR_LOGIN** — część przed `@` (np. `aleksander.bak`)
- **AUTHOR_NAME** — pełne imię i nazwisko z gita

Następnie wyszukaj użytkownika w Redmine → patrz `references/redmine-integration.md` (sekcja "Lookup użytkownika").

**Potwierdź z userem:**
```
Autor ostatniego commita: {AUTHOR_NAME} ({AUTHOR_EMAIL})
CR zostanie przypisany do: {ASSIGNEE_NAME} (Redmine #{ASSIGNEE_ID})

Czy to właściwa osoba do zgłoszenia poprawek CR?
```

Czekaj na potwierdzenie. Jeśli user poda inną osobę — wyszukaj ją w Redmine i użyj zamiast.

---

## Step 7 — Ustal zakres diff

### 7a. Znajdź commity powiązane z zagadnieniem

```bash
git log --oneline --grep="ref #{ISSUE_ID}"
```

Wydziel:
- **Commity feature** — BEZ `BUG|` w wiadomości
- **Commity CR** — Z `BUG|` w wiadomości (wcześniejsze code review)

Jeśli brak commitów z `ref #{ISSUE_ID}` — zatrzymaj się: „Nie znaleziono commitów odwołujących się do #{ISSUE_ID}. Upewnij się, że commity zawierają `ref #{ISSUE_ID}` w wiadomości."

### 7b. Sprawdź czy to re-review

Jeśli istnieją commity CR (`BUG|`) — to jest re-review. Uwzględnij tylko commity feature **nowsze** niż ostatni commit CR.

Jeśli po odfiltrowaniu nie ma commitów feature — zatrzymaj się: „Brak nowych zmian do przeglądu od ostatniego CR."

### 7c. Potwierdź zakres z userem

```
Znaleziono {N} commit(ów) powiązanych z #{ISSUE_ID}:

{hash1} {message1}
{hash2} {message2}
...

Czy code review ma dotyczyć właśnie tych commitów?
```

Czekaj na potwierdzenie. Jeśli user wskaże inne commity — dostosuj zakres.

### 7d. Ustaw zakres diff

- **Jeden commit:** `DIFF_BASE={commit}~1`, `DIFF_HEAD={commit}`
- **Wiele commitów:** `DIFF_BASE={najstarszy_commit}~1`, `DIFF_HEAD={najnowszy_commit}`

### 7e. Pokaż statystyki

```bash
git diff {DIFF_BASE}..{DIFF_HEAD} --stat
```

Jeśli diff jest pusty — zatrzymaj się: „Brak zmian do przeglądu."

---

## Step 8 — Przeprowadź code review

### 8a. Zbierz materiał

```bash
git diff {DIFF_BASE}..{DIFF_HEAD} --name-only
git diff {DIFF_BASE}..{DIFF_HEAD}
```

Dla każdego zmienionego pliku — przeczytaj pełną aktualną wersję (nie tylko diff) aby mieć kontekst.

### 8b. Heurystyka rozmiaru

```bash
git diff {DIFF_BASE}..{DIFF_HEAD} --stat
```

Policz łączną liczbę zmienionych linii (insertions + deletions):

- **Mniej niż 20 linii** → pomiń agentów, przeprowadź review sekwencyjnie (oceń 5 kryteriów jedno po drugim wg `references/review-criteria.md`), przejdź do Step 8f.
- **20 lub więcej linii** → kontynuuj do 8c.

### 8c. Uruchom 5 równoległych agentów review

Uruchom jednocześnie 5 niezależnych agentów. Każdy agent dostaje:
- Pełny diff (`git diff {DIFF_BASE}..{DIFF_HEAD}`)
- Pełne wersje zmienionych plików
- Kontekst zagadnienia z Redmine (tytuł, opis, kryteria akceptacji)
- **Jedno** kryterium z `references/review-criteria.md` (sekcja "Kryteria review — baseline")

Przypisanie modeli:
- Agent 1 (Sonnet) → Agent 1 — Poprawność
- Agent 2 (Sonnet) → Agent 2 — Błędy logiczne
- Agent 3 (Sonnet) → Agent 3 — Bezpieczeństwo
- Agent 4 (Sonnet) → Agent 4 — Wydajność
- Agent 5 (Sonnet) → Agent 5 — Jakość kodu

Każdy agent zwraca findings w formacie z sekcji "Format findings" w `references/review-criteria.md`.

> Jeśli projekt ma dedykowany skill review — zamiast 5 agentów baseline uruchom po jednym agencie na każde kryterium z dedykowanego skilla.

### 8d. Zbierz i scal wyniki

Połącz findings ze wszystkich 5 agentów w jedną listę. Zduplikowane findings (ten sam plik + linia zgłoszona przez kilka agentów) — zachowaj jeden, wybierz wyższe severity i notuj oba agenty.

### 8e. Confidence scoring

Dla każdego finding o severity **CRITICAL** lub **WARNING** uruchom równoległego agenta Haiku. Każdy agent Haiku:
- Otrzymuje: diff + pełny plik + treść finding
- Ocenia pewność wg rubric z `references/review-criteria.md` (sekcja "Confidence scoring")
- Zwraca: score (0–100) + uzasadnienie

Findings o severity **INFO** przechodzą bez scoringu.

### 8f. Filtruj i finalizuj

- Zachowaj findings z confidence >= 80 (oraz wszystkie INFO)
- Odrzucone findings (< 80) zachowaj osobno — będą w sekcji "Odrzucone" w raporcie
- Posortuj finalne findings: CRITICAL → WARNING → INFO, w obrębie severity malejąco po confidence

---

## Step 9 — Napisz raport CR

→ Szczegóły w `references/review-criteria.md` (sekcja "Szablon raportu CR")

Ścieżka raportu: `.ai/tasks/{ISSUE_ID}/cr.md`

Upewnij się że katalog istnieje: `mkdir -p .ai/tasks/{ISSUE_ID}`

---

## Step 10 — Dodaj inline FIXME komentarze

→ Szczegóły w `references/review-criteria.md` (sekcja "Inline FIXME komentarze")

---

## Step 11 — Zapytaj usera o commit

Wyświetl podsumowanie i poczekaj na zgodę:

```
Code review zakończony dla #{ISSUE_ID}.

Raport: .ai/tasks/{ISSUE_ID}/cr.md
Znaleziono: {X} CRITICAL, {Y} WARNING, {Z} INFO (po filtracji confidence)
Odrzucone (confidence < 80): {N} findings
Dodano FIXME komentarzy: {N}

Czy mogę zacommitować raport CR i komentarze FIXME?
```

User może mieć dodatkowe uwagi — wprowadź poprawki jeśli poprosi. Jeśli odmówi commita — zakończ.

---

## Step 12 — Commit

Po uzyskaniu zgody:

1. Użyj `AUTHOR_LOGIN` z Step 6 (np. `aleksander.bak`)

2. Dodaj pliki:
   ```bash
   git add .ai/tasks/{ISSUE_ID}/cr.md
   git add -u
   ```

3. Utwórz commit w formacie: `ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}`

   Gdzie `{ISSUE_SUBJECT}` to tytuł zagadnienia z Redmine (z Step 2).

   ```bash
   git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}"
   ```

   **Ważne:** NIE dodawaj `Co-Authored-By` ani żadnych dodatkowych linii do wiadomości commita.

4. Wyświetl hash commita. Zapytaj usera czy chce zrobić push.

---

## Step 13 — Utwórz zagadnienie Task/Bug w Redmine

→ Szczegóły w `references/redmine-integration.md` (sekcja "Tworzenie zagadnienia CR")

W opisie zagadnienia Redmine umieść link do raportu CR: `.ai/tasks/{ISSUE_ID}/cr.md`
