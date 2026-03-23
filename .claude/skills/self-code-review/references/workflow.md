# Workflow Self Code Review — 7 kroków

## Spis treści

- [Step 1 — Ustal kontekst (ISSUE_ID)](#step-1)
- [Step 2 — Wczytaj plan](#step-2)
- [Step 3 — Pobierz diff](#step-3)
- [Step 4 — Przeprowadź review](#step-4)
- [Step 5 — Zapisz raport](#step-5)
- [Step 6 — Podsumowanie](#step-6)
- [Step 7 — Decyzja i dalsze kroki](#step-7)

---

## Step 1 — Ustal kontekst (ISSUE_ID) {#step-1}

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

**Ustal DEFAULT_BRANCH:**

```bash
git symbolic-ref refs/remotes/origin/HEAD 2>/dev/null | sed 's|refs/remotes/origin/||'
```

Jeśli polecenie zawiedzie:
```bash
git branch -r | grep -E 'origin/(main|develop|master)$' | head -1 | sed 's|.*origin/||'
```

Jeśli nadal brak — zapytaj usera: „Jak nazywa się gałąź bazowa (np. `main`, `develop`, `master`)?"

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

Sprawdź czy są niezacommitowane zmiany:

```bash
git status --porcelain
```

Jeśli są niezacommitowane zmiany — odnotuj je informacyjnie (są wliczone w review):
```
Znaleziono niezacommitowane zmiany w working tree — zostaną uwzględnione w review:
  - {lista plików ze statusu}
```

**Pobierz diff** (zacommitowane + niezacommitowane zmiany względem gałęzi bazowej):

```bash
git diff origin/{DEFAULT_BRANCH}
```

Pobierz też listę zmienionych plików:

```bash
git diff origin/{DEFAULT_BRANCH} --name-only
git diff origin/{DEFAULT_BRANCH} --stat
```

**Filtruj pliki vendor/generated:**

Pomiń w review:
- Katalogi: `vendor/`, `node_modules/`, `bower_components/`
- Pliki lock: `composer.lock`, `package-lock.json`, `yarn.lock`
- Pliki minified: `*.min.js`, `*.min.css`
- Pliki z nagłówkiem `// This file is auto-generated` lub podobnym

Jeśli odfiltrowano pliki — odnotuj: „Pominięto {N} plik(ów) vendor/generated."

**Jeśli diff jest pusty** po filtrowaniu — zatrzymaj się: „Brak zmian do przeglądu na branchu `{branch}` względem `origin/{DEFAULT_BRANCH}`."

Dla każdego zmienionego pliku — przeczytaj pełną aktualną wersję, aby mieć kontekst (nie tylko diff).

---

## Step 4 — Przeprowadź review {#step-4}

→ Szczegóły w `references/review-checklist.md`

Review jest **sekwencyjny i single-pass** — przejdź przez 5 obszarów jeden po drugim. Nie uruchamiaj równoległych agentów.

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
