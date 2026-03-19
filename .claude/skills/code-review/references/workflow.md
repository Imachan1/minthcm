# Workflow Code Review — 13 kroków

## Spis treści

- [Step 1 — Parsuj numer zagadnienia](#step-1)
- [Step 2 — Pobierz kontekst z Redmine](#step-2)
- [Step 3 — Walidacja typu zagadnienia](#step-3)
- [Step 4 — Pobierz najnowsze zmiany](#step-4)
- [Step 5 — Znajdź i przełącz na feature branch](#step-5)
- [Step 6 — Zidentyfikuj autora kodu](#step-6)
- [Step 7 — Ustal zakres diff](#step-7)
- [Step 8 — Przeprowadź code review](#step-8)
- [Step 9 — Napisz raport CR](#step-9)
- [Step 10 — Dodaj inline FIXME komentarze](#step-10)
- [Step 11 — Zapytaj usera o commit](#step-11)
- [Step 12 — Commit](#step-12)
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

**Ustal domyślną gałąź bazową** (używana w Step 6 i Step 5 do sprawdzenia aktualności):

```bash
git symbolic-ref refs/remotes/origin/HEAD 2>/dev/null | sed 's|refs/remotes/origin/||'
```

Jeśli polecenie zwróci wynik (np. `main`, `develop`, `master`) — zapamiętaj jako `DEFAULT_BRANCH`.
Jeśli polecenie zawiedzie (brak `origin/HEAD`) — sprawdź kolejno:

```bash
git branch -r | grep -E 'origin/(main|develop|master)$' | head -1 | sed 's|.*origin/||'
```

Jeśli nadal brak — zapytaj usera: „Nie mogę ustalić domyślnej gałęzi. Jak nazywa się gałąź bazowa (np. `main`, `develop`, `master`)?" i użyj podanej wartości jako `DEFAULT_BRANCH`.

---

## Step 5 — Znajdź i przełącz na feature branch

Najpierw sprawdź czy working tree jest czysty:

```bash
git status --porcelain
```

Jeśli są niezacommitowane zmiany — zatrzymaj się i poinformuj usera:
```
Working tree ma niezacommitowane zmiany. Przed przełączeniem na feature branch
zapisz lub schowaj swoje zmiany:
  git stash        (odłóż zmiany tymczasowo)
  git commit ...   (zacommituj zmiany)
```
Czekaj na potwierdzenie usera że zmiany są zabezpieczone, zanim przejdziesz dalej.

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

**Sprawdź czy branch jest aktualny relative do `DEFAULT_BRANCH`:**

```bash
git rev-list --count HEAD..origin/{DEFAULT_BRANCH}
```

Jeśli wynik > 0 — poinformuj usera (nie blokuj, ale zaznacz):
```
Uwaga: feature/{ISSUE_ID} jest {N} commit(ów) za origin/{DEFAULT_BRANCH}.
Branch może mieć konflikty przy merge — rozważ rebase przed review:
  git rebase origin/{DEFAULT_BRANCH}
Kontynuuję review na aktualnym stanie brancha.
```

---

## Step 6 — Zidentyfikuj autora kodu (assignee CR)

Pobierz autora ostatniego commita (pomijając commity CR z wzorcem `#BUG|`):

```bash
git log HEAD --invert-grep --grep="#BUG|" -1 --format="%ae %an"
```

Wyciągnij:
- **AUTHOR_EMAIL** — pełny email (np. `aleksander.bak@evolpe.pl`)
- **AUTHOR_LOGIN** — część przed `@` (np. `aleksander.bak`)
- **AUTHOR_NAME** — pełne imię i nazwisko z gita

**Wielu autorów na branchu:** Sprawdź czy commity feature na branchu mają więcej niż jednego autora:

```bash
git log origin/{DEFAULT_BRANCH}..HEAD --invert-grep --grep="#BUG|" --format="%ae" | sort -u
```

*(Na tym etapie zakres diff nie jest jeszcze ustalony — używamy pełnego zakresu brancha. Dokładne zawężenie do zakresu diff nastąpi w Step 7.)*

Jeśli więcej niż jeden email — wyświetl listę i zapytaj usera:
```
Na branchu commitowało kilka osób:
  - {email1} ({name1})
  - {email2} ({name2})

Do kogo przypisać zagadnienie CR z poprawkami?
```

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
git log --oneline --no-merges --grep="ref #{ISSUE_ID}"
```

Wydziel:
- **Commity feature** — BEZ `#BUG|` w wiadomości
- **Commity CR** — Z `#BUG|` w wiadomości (wcześniejsze code review)

Jeśli brak commitów z `ref #{ISSUE_ID}` — zatrzymaj się: „Nie znaleziono commitów odwołujących się do #{ISSUE_ID}. Upewnij się, że commity zawierają `ref #{ISSUE_ID}` w wiadomości."

### 7b. Sprawdź czy to re-review

Jeśli istnieją commity CR (`#BUG|`) — to jest re-review. Uwzględnij tylko commity feature **nowsze** niż ostatni commit CR.

Jeśli po odfiltrowaniu nie ma commitów feature — zatrzymaj się: „Brak nowych zmian do przeglądu od ostatniego CR."

**Routing re-review:** Jeśli to re-review, po ustaleniu zakresu diff (Steps 7c–7e) **przejdź do `references/re-review.md` Step R0** zamiast kontynuować Step 8. Pełna procedura re-CR jest tam opisana.

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

Dla każdego zmienionego pliku tekstowego — przeczytaj pełną aktualną wersję (nie tylko diff) aby mieć kontekst.

**Pliki vendor/generated:** Przed review odfiltruj pliki, które nie powinny być ręcznie reviewowane:
- Katalogi: `vendor/`, `node_modules/`, `bower_components/`, `composer.lock`, `package-lock.json`, `yarn.lock`
- Pliki auto-generowane: `*.min.js`, `*.min.css`, pliki z nagłówkiem `// This file is auto-generated` lub podobnym
- Jeśli te pliki są w diffie — pomiń je w review i odnotuj: „Pominięto {N} plik(ów) vendor/generated."
- Wyjątek: jeśli plik `composer.lock` / `package-lock.json` zawiera nieoczekiwaną zmianę zależności — odnotuj jako finding INFO.

**Pliki binarne:** Jeśli diff zawiera pliki binarne (obrazy, PDF, skompilowane pliki itp.) — pomiń je w review i poinformuj usera:
```
Pominięto {N} plik(ów) binarnych (brak możliwości analizy treści):
  - {plik1}
  - {plik2}
```
Jeśli plik binarny wydaje się nieoczekiwany w kontekście zagadnienia (np. skompilowany plik `.class` w repozytorium PHP) — odnotuj to jako finding INFO.

**Pusty opis zagadnienia:** Jeśli opis lub kryteria akceptacji w Redmine są puste — poinformuj agentów, że Agent 1 (Poprawność) ma ograniczony kontekst wymagań. Agent 1 wtedy skupia się wyłącznie na spójności z tytułem zagadnienia i ogólnych konwencjach projektu zamiast na zgodności z kryteriami akceptacji.

**Skille:** Przejrzyj skille załadowane w kontekście rozmowy (pomiń `code-review`). Na podstawie diffa i kontekstu zagadnienia z Redmine zdecyduj, które są trafne. Dla każdego wybranego skilla — **odczytaj jego `SKILL.md`** narzędziem Read (ścieżka: `.claude/skills/{nazwa-skilla}/SKILL.md`) i wyciągnij z niego kluczowe reguły. Przekaż te reguły agentom review jako dodatkowy kontekst obok baseline kryteriów.

Jeśli jakiś skill został wybrany, **wypisz podsumowanie**:

```
📋 Użyte skille:

| Skill | Powód wyboru |
|---|---|
| {nazwa-skilla} | {krótkie uzasadnienie na podstawie diffa/zagadnienia} |
```

### 8b. Heurystyka rozmiaru

```bash
git diff {DIFF_BASE}..{DIFF_HEAD} --stat
```

Policz łączną liczbę zmienionych linii (insertions + deletions):

- **Mniej niż 20 linii** → pomiń agentów, przeprowadź review sekwencyjnie (oceń 5 kryteriów jedno po drugim wg `references/review-criteria.md`, uwzględniając reguły ze skilli (8a)), przejdź do Step 8f.
- **20 lub więcej linii** → kontynuuj do 8c.

### 8b-bis. Bardzo duże diffy (>500 linii)

Jeśli łączna liczba zmienionych linii przekracza **500**, podziel diff na chunki po plikach:

1. Zgrupuj zmienione pliki tematycznie (np. modele razem, widoki razem, testy razem) — maksymalnie **3 grupy**
2. Poinformuj usera: „Diff jest duży ({N} linii) — review może potrwać dłużej."
3. Dla każdej grupy uruchom osobny zestaw 5 agentów zgodnie z Step 8c, przekazując im tylko diff plików z danej grupy (nie cały diff). Grupy przetwarzaj **sekwencyjnie** (jedna po drugiej), nie wszystkie naraz — łączna liczba równoległych agentów nie może przekroczyć 5 w danym momencie.
4. Zbierz findings ze wszystkich grup i scal je łącznie w Step 8d — duplikaty między grupami usuwaj według tych samych reguł co duplikaty między agentami

---

### 8c. Uruchom 5 równoległych agentów review

Jeśli w 8a wybrano jakiekolwiek skille, **wypisz mapowanie** przed uruchomieniem agentów:

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

> Reguły z trafnych skilli (zebrane w 8a) uzupełniają baseline kryteria agentów — nie zastępują ich.

### 8d. Zbierz i scal wyniki

Połącz findings ze wszystkich 5 agentów w jedną listę. Zduplikowane findings (ten sam plik + linia zgłoszona przez kilka agentów) — zachowaj jeden, wybierz wyższe severity i notuj oba agenty.

**Scalanie confidence dla duplikatów:** Jeśli ten sam finding pojawia się u 2 lub więcej agentów i żaden z nich nie przekracza progu 80, zachowaj go z `max(confidence)` spośród wszystkich zgłoszeń. Powtórzenie przez niezależnych agentów jest samo w sobie sygnałem, że problem jest realny.

### 8e. Confidence scoring

Dla każdego finding o severity **CRITICAL** lub **WARNING** uruchom równoległego agenta Haiku — maksymalnie **10 agentów jednocześnie**. Jeśli findings jest więcej niż 10, przetwarzaj w batchach po 10. Każdy agent Haiku:
- Otrzymuje: diff + pełny plik + treść finding
- Ocenia pewność wg rubric z `references/review-criteria.md` (sekcja "Confidence scoring")
- Zwraca: score (0–100) + uzasadnienie

Findings o severity **INFO** przechodzą bez scoringu.

### 8f. Filtruj i finalizuj

**Ścieżka sekwencyjna (z 8b, <20 linii):** Brak confidence scoringu — wszystkie findings przechodzą. Posortuj: CRITICAL → WARNING → INFO. Sekcja "Odrzucone" w raporcie jest pomijana.

**Ścieżka równoległa (z 8e, >=20 linii):**
- Zachowaj findings z confidence >= progu (patrz `references/review-criteria.md` sekcja "Confidence scoring") oraz wszystkie INFO
- Odrzucone findings (poniżej progu) zachowaj osobno — będą w sekcji "Odrzucone" w raporcie
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
Znaleziono: {X} CRITICAL, {Y} WARNING, {Z} INFO
[Odrzucone (confidence < 80): {N} findings]  ← tylko dla ścieżki równoległej (>=20 linii)
Dodano FIXME komentarzy: {N}
Werdykt: {APPROVED / CHANGES REQUESTED / NEEDS DISCUSSION}

[Jeśli NEEDS DISCUSSION: "Znaleziono problemy wymagające rozmowy z zespołem — nie zostanie utworzone zagadnienie w Redmine. Raport CR i komentarze FIXME zostaną zacommitowane normalnie."]

Czy mogę zacommitować raport CR i komentarze FIXME?
```

User może mieć dodatkowe uwagi — wprowadź poprawki jeśli poprosi. Jeśli odmówi commita — zakończ.

---

## Step 12 — Commit

Po uzyskaniu zgody:

1. Użyj `AUTHOR_LOGIN` z Step 6 (np. `aleksander.bak`)

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
   git add {plik1} {plik2} ...  # pliki które dostały komentarze FIXME w Step 10
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

## Step 13 — Utwórz zagadnienie Task/Bug w Redmine

→ Szczegóły w `references/redmine-integration.md` (sekcja "Tworzenie zagadnienia CR")

W opisie zagadnienia Redmine umieść link do raportu CR: `.ai/tasks/{ISSUE_ID}/cr.md`

---

## Zakończenie

Po zakończeniu Step 13 wyświetl podsumowanie końcowe.

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

Ścieżka NEEDS DISCUSSION nie tworzy zagadnienia w Redmine (Step 13 jest pomijany). Raport CR i FIXME są commitowane normalnie (Steps 11–12).

---

## Re-CR

Jeśli w Step 7b wykryto commity CR na branchu (`#BUG|`), po ustaleniu zakresu diff przejdź do pełnej procedury re-CR:

→ `references/re-review.md`

Procedura re-CR obejmuje: weryfikację poprzednich findings, review nowych zmian, aktualizację raportu (append sekcji Re-CR), ustalenie werdyktu. Przy werdykcie APPROVED: squash commitów + merge do develop (po potwierdzeniu usera).
