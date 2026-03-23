# Tryb CR — Naprawa bugów po code review

Używaj tego pliku gdy **TRYB = `cr`** — naprawiasz bugi znalezione przez skill `code-review`.

Zakłada się że istnieje:
- `.ai/tasks/{ISSUE_ID}/cr.md` — raport code review z checkboxami CRITICAL/WARNING
- Komentarze `// FIXME - AI CR - ...` w plikach źródłowych (dla każdego CRITICAL i WARNING)

---

## Spis treści

- [Step C1 — Przygotuj środowisko](#step-c1)
- [Step C2 — Wczytaj bugi do naprawy](#step-c2)
- [Step C3 — Napraw każdy bug](#step-c3)
- [Step C4 — Commit](#step-c4)
- [Zakończenie](#zakończenie)

---

## Step C1 — Przygotuj środowisko

### C1a. Sprawdź repozytorium git

```bash
git rev-parse --is-inside-work-tree
```

Jeśli błąd → zatrzymaj się: „Nie jestem wewnątrz repozytorium git. Otwórz terminal w katalogu projektu."

### C1b. Sprawdź working tree

```bash
git status --porcelain
```

Jeśli są niezacommitowane zmiany → poinformuj usera i poczekaj na potwierdzenie:
```
Working tree ma niezacommitowane zmiany. Przed naprawą zabezpiecz swoje zmiany:
  git stash        (odłóż tymczasowo)
  git commit ...   (zacommituj)
```

### C1c. Checkout branch

```bash
git branch -a | grep "feature/{ISSUE_ID}"
```

Jeśli branch istnieje lokalnie → `git checkout feature/{ISSUE_ID}`
Jeśli tylko zdalnie → `git checkout -b feature/{ISSUE_ID} origin/feature/{ISSUE_ID}`
Jeśli brak → zatrzymaj się: „Nie znaleziono brancha feature/{ISSUE_ID}."

### C1d. Pobierz aktualny tytuł zagadnienia

Sprawdź czy masz `redmine_request`. Jeśli tak:
```
redmine_request: GET /issues/{ISSUE_ID}.json
```
Zapamiętaj `ISSUE_SUBJECT` (tytuł zagadnienia) — będzie potrzebny w commicie.

Jeśli brak MCP → wyciągnij tytuł z nagłówka `cr.md`: pierwsza linia ma format `# Code Review — #{ISSUE_ID} {ISSUE_SUBJECT}` — tekst PO numerze issue to tytuł.

---

## Step C2 — Wczytaj bugi do naprawy

### C2a. Wczytaj raport CR

```bash
test -f .ai/tasks/{ISSUE_ID}/cr.md && echo "exists" || echo "missing"
```

Jeśli brak → zatrzymaj się: „Nie znaleziono raportu CR: `.ai/tasks/{ISSUE_ID}/cr.md`. Czy code review był wykonany tym skillem?"

Przeczytaj cały plik `.ai/tasks/{ISSUE_ID}/cr.md`.

### C2b. Ustal aktywną sekcję

**Wielokrotne rundy Re-CR:** Sprawdź czy w pliku istnieją sekcje `## Re-CR #`:

- Jeśli **brak sekcji Re-CR** → parsuj checkboxy `[ ]` z oryginalnych sekcji CRITICAL i WARNING

- Jeśli **istnieje N sekcji Re-CR** → zbuduj listę bugów z DWÓCH źródeł w **ostatniej** sekcji Re-CR:
  1. **Nowe findings** — `[ ]` checkboxy z sekcji `#### CRITICAL` i `#### WARNING` pod ostatnim `## Re-CR #`
  2. **Stare UNFIXED** — z tabeli weryfikacyjnej (wiersz z `❌ UNFIXED`): odszukaj ten finding w oryginalnych sekcjach Findings (tam jest opis + sugestia naprawy)

  Zaznaczone `- [x]` ORAZ findings z `✅ FIXED` w tabeli weryfikacyjnej pomijaj — już naprawione.

Zapamiętaj `AKTYWNA_RUNDA` (np. „oryginalny CR" lub „Re-CR #2").

### C2c. Zbuduj listę bugów do naprawy

Dla każdego niezaznaczonego checkboxa wyciągnij:
- **BUG_ID** — identyfikator (np. `CR-1`)
- **SEVERITY** — CRITICAL lub WARNING
- **FILE_PATH** — ścieżka do pliku
- **LINE_CONTEXT** — opis problemu i sugestia naprawy

Wypisz podsumowanie:
```
Bugi do naprawy z {AKTYWNA_RUNDA}:

CRITICAL ({X}):
  - CR-1: api/utils/CyclicRecordsSaver.php — Brak walidacji górna granica repeat_count
  - CR-2: api/utils/CyclicRecordsSaver.php — Brak early-exit w *ByUntil

WARNING ({Y}):
  - CR-3: api/utils/CyclicRecordsSaver.php — N+1 get_linked_beans() w pętli
  ...
```

Jeśli lista jest pusta → poinformuj usera: „Wszystkie bugi z CR są już zaznaczone jako naprawione. Nic do naprawy."

---

## Step C3 — Napraw każdy bug

Przetwarzaj bugi kolejno: najpierw CRITICAL, potem WARNING (w kolejności z raportu).

### C3a. Dla każdego buga:

**1. Zlokalizuj FIXME w kodzie**

```bash
grep -rn "FIXME - AI CR" {FILE_PATH}
```

Szukaj komentarza pasującego do opisu buga (plik + opis). Komentarz ma format:
```
// FIXME - AI CR - {opis problemu}
```
lub w innych językach: `# FIXME - AI CR - ...`, `{# FIXME - AI CR - ... #}` itp.

Jeśli komentarz FIXME nie istnieje (możliwe że był wcześniej ręcznie usunięty) → znajdź lokalizację w sekcji Findings z `cr.md` — szukaj wiersza zawierającego `| {FILE_PATH} |` lub `| {BUG_ID} |`. Kontynuuj na podstawie opisu z cr.md.

**2. Przeczytaj kontekst**

Przeczytaj pełny plik (lub przynajmniej sekcję wokół problemu). Zrozum:
- Co jest problemem
- Jaka jest sugerowana naprawa (z cr.md)
- Jak naprawa wpływa na resztę kodu

**2b. Sprawdź czy developer odpowiedział na FIXME**

Po zlokalizowaniu komentarza FIXME sprawdź czy bezpośrednio po nim (w tej samej linii lub w linii poniżej) pojawił się komentarz **dodany przez dewelopera** — nie będący częścią oryginalnego FIXME.

Sygnały odpowiedzi dewelopera: nowy komentarz zaraz po FIXME, zawierający np. „DEVELOPER:", „NOTE:", „TODO:", lub dowolny komentarz wyjaśniający dlaczego nie naprawiono.

**Jeśli developer odpowiedział** → zatrzymaj się i zapytaj usera:
```
Finding {BUG_ID} ({SEVERITY}) ma odpowiedź dewelopera:

  FIXME: {treść oryginalnego FIXME}
  Odpowiedź dewelopera: "{treść komentarza dewelopera}"

Jak postąpić?
- Zaakceptuj wyjaśnienie → pominę naprawę tego buga (oznaczymy jako zaakceptowane bez poprawy)
- Odrzuć wyjaśnienie → naprawię bug mimo odpowiedzi dewelopera
```

Na podstawie decyzji usera:
- **Akceptacja** → zaznacz `- [ ]` jako `- [x]` z adnotacją `← ZAAKCEPTOWANE BEZ POPRAWY ({data}): "{odpowiedź}"`, usuń z kodu **oba komentarze** (FIXME + odpowiedź), przejdź do kolejnego buga
- **Odrzucenie** → usuń z kodu tylko komentarz odpowiedzi dewelopera (FIXME zostaje do naprawy), kontynuuj normalną naprawę tego buga

**3. Oceń czy naprawa jest jednoznaczna**

Jeśli sugestia z cr.md jest konkretna i jednoznaczna → implementuj bezpośrednio.

Jeśli naprawa jest niejasna, wymaga decyzji projektowej lub masz wątpliwości → **zatrzymaj się i zapytaj usera**:
```
Bug {BUG_ID} ({SEVERITY}): {opis}
Plik: {FILE_PATH}

Sugestia z CR: {sugestia}

Mam wątpliwości jak to zaimplementować: {opis wątpliwości}
Jak chcesz żebym to naprawił?
```

**4. Zastosuj odpowiedni skill**

Jeśli w Kroku 1 (SKILL.md) wybrałeś `minthcm-project` lub inny skill — stosuj jego zasady podczas tej naprawy.

**5. Zaimplementuj naprawę**

Edytuj plik. Usuń komentarz FIXME po naprawieniu.

**6. Zaznacz checkbox w cr.md**

Zmień `- [ ]` na `- [x]` dla tego buga w pliku `.ai/tasks/{ISSUE_ID}/cr.md`.

Jeśli finding miał adnotację `← UNFIXED w Re-CR #N` → usuń tę adnotację podczas zaznaczania.

**7. Poinformuj o postępie**

```
✅ CR-1 NAPRAWIONY: Dodano walidację repeat_count w validate() [api/utils/CyclicRecordsSaver.php]
```

### C3b. Uwagi do naprawy

**Nie naprawiaj INFO** — findings oznaczone jako INFO to sugestie, nie bugi wymagające naprawy. Pomiń je chyba że user wyraźnie prosi o ich naprawę.

**Zmiany zależne** — jeśli naprawa jednego buga wpływa na inny (np. CR-1 i CR-2 dotyczą tej samej metody) — napraw je razem, zaznacz oba.

**Nie refaktoryzuj poza zakresem** — naprawiasz konkretne bugi z listy, nie przeprowadzasz generalnego refaktoringu.

---

## Step C4 — Commit

Po naprawieniu wszystkich bugów (lub zakończeniu sesji naprawy, jeśli user chce commitować etapami):

### C4a. Podsumowanie i pytanie o commit

**Zawsze czekaj na zgodę usera przed wykonaniem commita** — nie commituj automatycznie nawet jeśli wszystkie naprawy są gotowe. To jest punkt kontrolny gdzie user może jeszcze coś zmienić.

```
Naprawa bugów zakończona dla #{ISSUE_ID}.

Naprawiono ({AKTYWNA_RUNDA}):
  ✅ CR-1 CRITICAL: Dodano walidację repeat_count
  ✅ CR-2 CRITICAL: Dodano early-exit w *ByUntil metodach
  ✅ CR-3 WARNING: Zoptymalizowano get_linked_beans() (poza pętlą)

Zaakceptowane bez poprawy (na życzenie usera):
  ⚪ CR-X WARNING: {opis} — odpowiedź dewelopera: "{treść}"   ← tylko jeśli były akceptacje

Pominięto (INFO — nie wymagają naprawy):
  ℹ️  CR-7: Powtarzany boilerplate try/finally (DRY)

Pliki zmienione:
  - api/utils/CyclicRecordsSaver.php
  - .ai/tasks/{ISSUE_ID}/cr.md (zaktualizowane checkboxy)

Czy mogę zacommitować te zmiany?
```

### C4b. Sprawdź .gitignore

```bash
git check-ignore -v .ai/tasks/
```

Jeśli katalog `.ai/tasks/` jest wykluczony → poinformuj usera jak dodać wyjątek:
```
Katalog .ai/tasks/ jest wykluczony przez .gitignore — zaktualizowany cr.md nie zostanie zacommitowany.
Dodaj wyjątek: !.ai/tasks/
```
Czekaj na potwierdzenie przed kontynuacją.

### C4c. Wykonaj commit

Dodaj **tylko zmienione pliki** (pliki z naprawami + cr.md):

```bash
git add {pliki_z_naprawami}
git add .ai/tasks/{ISSUE_ID}/cr.md
git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT}"
```

**Ważne:** Nie dodawaj `#BUG|{login}` do wiadomości commita — ten format jest zarezerwowany dla commitów code review i służy do detekcji re-CR.

Wyświetl hash commita. Zapytaj czy chce push:

```bash
git push origin feature/{ISSUE_ID}
```

---

## Zakończenie

```
✅ Bug-fix CR zakończony dla #{ISSUE_ID}.

Naprawiono: {X} CRITICAL, {Y} WARNING
Commit: {commit_hash}

Kolejny krok: poproś reviewera o re-CR.
Skill code-review wykryje nowy commit i przeprowadzi re-review zmian.
```

**Jeśli nie wszystkie bugi zostały naprawione** (user postanowił naprawić tylko część):

```
⚠️  Naprawiono częściowo: {X}/{TOTAL} bugów.
Pozostałe do naprawy: {lista nienap rawonych}

Commit: {commit_hash}
Możesz kontynuować naprawę w kolejnej sesji lub poprosić o re-CR z aktualnym stanem.
```
