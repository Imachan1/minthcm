# Workflow Self Code Review — 7 kroków

## Spis treści

- [Step 1 — Ustal kontekst (ISSUE_ID, BASE_BRANCH, zakres)](#step-1)
- [Step 2 — Wczytaj plan](#step-2)
- [Step 3 — Pobierz diff](#step-3)
- [Step 3b — Heurystyka rozmiaru diffa](#step-3b)
- [Step 4 — Przeprowadź review](#step-4)
- [Step 5 — Zapisz raport](#step-5)
- [Step 6 — Podsumowanie](#step-6)
- [Step 7 — Decyzja i dalsze kroki](#step-7)

---

## Step 1 — Ustal kontekst (ISSUE_ID, BASE_BRANCH, zakres) {#step-1}

Najpierw sprawdź czy jesteś wewnątrz repozytorium git:

```bash
git rev-parse --is-inside-work-tree
```

Jeśli polecenie zwróci błąd — zatrzymaj się: „Nie jestem wewnątrz repozytorium git. Otwórz terminal w katalogu projektu."

**Ustal ISSUE_ID:**

1. Jeśli wywołano jako slash command z argumentem (`/self-code-review 184819`):
   - Użyj podanego numeru jako `ISSUE_ID`

2. Jeśli brak argumentu — wyciągnij z nazwy aktualnego brancha:
   ```bash
   git branch --show-current
   ```
   - Pattern `feature/{ID}` lub `feature/{ID}-*` — użyj `{ID}` jako `ISSUE_ID`
   - Jeśli branch nie pasuje do wzorca — zapytaj usera: „Nie mogę ustalić numeru zagadnienia z nazwy brancha (`{branch_name}`). Podaj numer zagadnienia."

**Sprawdź czy jesteś na właściwym branchu:**

Pobierz aktualny branch:

```bash
git branch --show-current
```

Jeśli aktualny branch to `feature/{ISSUE_ID}` lub `feature/{ISSUE_ID}-*` — jesteś na właściwym miejscu, kontynuuj.

Jeśli aktualny branch nie odpowiada `ISSUE_ID` — sprawdź czy istnieje właściwy branch:

```bash
git branch --list "feature/{ISSUE_ID}*"
```

Jeśli branch istnieje lokalnie — zapytaj:

```
Jesteś na branchu `{current_branch}`, ale zagadnienie to #{ISSUE_ID}.
Znalazłem branch `feature/{ISSUE_ID}`. Czy przełączyć? [T/n]
```

Jeśli user potwierdzi — wykonaj `git checkout feature/{ISSUE_ID}`.
Jeśli user odmówi — kontynuuj na aktualnym branchu (zakres diff będzie względem aktualnego brancha).

Jeśli branch nie istnieje lokalnie — poinformuj: „Nie znalazłem lokalnego brancha `feature/{ISSUE_ID}`. Kontynuuję na aktualnym branchu `{current_branch}`."

**Ustal BASE_BRANCH:**

Najpierw spróbuj wyciągnąć z reflogu — skąd faktycznie powstał branch:

```bash
git reflog show --format="%gs" $(git branch --show-current) | grep "branch: Created from" | head -1
```

Wynik w stylu `branch: Created from develop` — użyj `develop` jako `BASE_BRANCH`.

Jeśli reflog nie dał wyniku — fallback przez `origin/HEAD`:

```bash
git symbolic-ref refs/remotes/origin/HEAD 2>/dev/null | sed 's|refs/remotes/origin/||'
```

Jeśli i to zawiedzie — szukaj po nazwach:

```bash
git branch -r | grep -E 'origin/(main|develop|master)$' | head -1 | sed 's|.*origin/||'
```

Jeśli nadal brak — zapytaj usera: „Jak nazywa się gałąź bazowa (np. `main`, `develop`, `master`)?"

**Potwierdź zakres z userem:**

Pobierz listę commitów na feature branchu:

```bash
git log origin/{BASE_BRANCH}..HEAD --oneline
```

Sprawdź czy są niezacommitowane zmiany w working tree:

```bash
git status --porcelain
```

Wyświetl podsumowanie i zapytaj o potwierdzenie:

```
Znalazłem base branch: `{BASE_BRANCH}`

Commity na tym branchu ({N}):
  {hash} {message}
  {hash} {message}
  ...

{Jeśli są zmiany w working tree:}
Niezacommitowane zmiany w working tree ({M} plików):
  - {lista plików ze statusu}

Czy wziąć wszystkie powyższe zmiany do review? [T/n]
```

Jeśli user odpowie `n` lub poda własny zakres — użyj wskazanego przez niego zakresu.

---

## Step 2 — Wczytaj plan {#step-2}

Sprawdź czy istnieje plan dla zagadnienia:

```
.ai/tasks/{ISSUE_ID}/plan.md
```

**Jeśli plan istnieje:** Wczytaj go. Wyciągnij:
- Listę kroków implementacji (do weryfikacji kompletności)
- Listę plików wskazanych w planie (do sprawdzenia czy zostały zmodyfikowane)
- Kryteria akceptacji (jeśli są w planie)

**Jeśli plan NIE istnieje:** Kontynuuj bez niego. Obszar 1 (kompletność vs plan) w review zostanie pominięty. Poinformuj usera: „Brak planu w `.ai/tasks/{ISSUE_ID}/plan.md` — pominę weryfikację kompletności względem planu."

---

## Step 3 — Pobierz diff {#step-3}

**Pobierz diff** (zacommitowane zmiany + working tree względem gałęzi bazowej):

```bash
git diff origin/{BASE_BRANCH}
```

Pobierz też listę zmienionych plików:

```bash
git diff origin/{BASE_BRANCH} --name-only
git diff origin/{BASE_BRANCH} --stat
```

**Filtruj pliki vendor/generated:**

Pomiń w review:
- Katalogi: `vendor/`, `node_modules/`, `bower_components/`
- Pliki lock: `composer.lock`, `package-lock.json`, `yarn.lock`
- Pliki minified: `*.min.js`, `*.min.css`
- Pliki z nagłówkiem `// This file is auto-generated` lub podobnym

Jeśli odfiltrowano pliki — odnotuj: „Pominięto {N} plik(ów) vendor/generated."

**Jeśli diff jest pusty** po filtrowaniu — zatrzymaj się: „Brak zmian do przeglądu na branchu `{branch}` względem `origin/{BASE_BRANCH}`."

---

### Step 3b — Heurystyka rozmiaru diffa {#step-3b}

Na podstawie wyniku `git diff origin/{BASE_BRANCH} --stat` (pobranego w Step 3) policz łączną liczbę zmienionych linii (dodane + usunięte) i ustal tryb review:

| Rozmiar diffa | Tryb |
|---|---|
| < 150 linii | **SEQUENTIAL** — czytaj pełne pliki dla kontekstu (dotychczasowe zachowanie) |
| 150–600 linii | **FOCUSED** — czytaj tylko diff, nie pełne wersje plików |
| > 600 linii | **CHUNKED** — podziel pliki na grupy tematyczne |

**Tryb FOCUSED:**

Poinformuj usera: „Diff jest średni ({N} linii) — review skupi się na samych zmianach, bez czytania pełnego kontekstu plików."

Nie czytaj pełnych wersji plików — operuj tylko na difie. Odnotuj to w raporcie.

**Tryb CHUNKED:**

1. Zgrupuj zmienione pliki tematycznie (np. modele, widoki, logika, testy) — maksymalnie **3 grupy**
2. Poinformuj usera: „Diff jest duży ({N} linii) — review będzie podzielony na {K} grupy tematyczne przetwarzane sekwencyjnie."
3. Dla każdej grupy wykonaj Step 4 (pełny pass przez 5 obszarów) sekwencyjnie — jedna grupa po drugiej
4. Po przetworzeniu wszystkich grup scal findings przed Step 5 (deduplikuj po pliku:linii)

---

## Step 4 — Przeprowadź review {#step-4}

→ Szczegóły w `references/review-checklist.md`

Review jest **sekwencyjny** — przejdź przez 5 obszarów jeden po drugim. Nie uruchamiaj równoległych agentów.

- Tryb **SEQUENTIAL/FOCUSED**: single-pass przez wszystkie zmienione pliki
- Tryb **CHUNKED**: wykonaj pass przez 5 obszarów oddzielnie dla każdej grupy plików (sekwencyjnie)

---

## Step 5 — Zapisz raport {#step-5}

Utwórz katalog jeśli nie istnieje:

```bash
mkdir -p .ai/tasks/{ISSUE_ID}
```

Zapisz raport do `.ai/tasks/{ISSUE_ID}/self-cr.md`.

**Ważne:** Nadpisuj istniejący plik — nie appenduj. Przy ponownym uruchomieniu self-CR stary raport jest zastępowany nowym.

→ Format raportu w `references/review-checklist.md` (sekcja "Szablon raportu")

---

## Step 6 — Podsumowanie {#step-6}

Wyświetl wyniki w terminalu:

```
Self Code Review zakończony dla #{ISSUE_ID}.

Raport: .ai/tasks/{ISSUE_ID}/self-cr.md
Zakres: {N} plików, {M} linii zmian

Znalezione problemy:
  CRITICAL: {X}
  WARNING:  {Y}
  INFO:     {Z}

Status: READY FOR CR / NEEDS FIXES
```

Jeśli są CRITICAL lub WARNING — wylistuj je zwięźle:
```
Problemy wymagające naprawy:
  [CRITICAL] plik:linia — Opis
  [WARNING]  plik:linia — Opis
```

---

## Step 7 — Decyzja i dalsze kroki {#step-7}

**NEEDS FIXES** (są CRITICAL lub WARNING):
```
Znaleziono {X} problemów wymagających naprawy przed CR.
Napraw wskazane problemy i uruchom `/self-code-review` ponownie.
```

**READY FOR CR** (brak CRITICAL i WARNING):
```
Kod wygląda dobrze — brak krytycznych ani ostrzegawczych problemów.

Sugerowane kolejne kroki:
  1. /self-test  — wygeneruj ścieżki do testu manualnego
  2. /code-review {ISSUE_ID}  — prześlij do formalnego Code Review
```
