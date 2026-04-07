# Re-CR — Procedura ponownego code review

Używaj tego pliku gdy w Step 6d workflow.md wykryto commity CR (`#BUG|`) na branchu — czyli to jest re-review po wprowadzeniu poprawek przez developera.

> `LAST_CR_COMMIT` — hash ostatniego commita CR — jest dostępny z wyników `collect_commit_scope.sh` (pole `last_cr_hash`) pobranych w Step 6a workflow.md. Upewnij się, że go zapamiętałeś przed wejściem do tej procedury.

## Spis treści

- [Step R0 — Ustal numer rundy Re-CR](#step-r0)
- [Step R1 — Wczytaj poprzedni raport CR](#step-r1)
- [Step R2 — Weryfikuj poprawki](#step-r2)
- [Step R3 — Review nowych zmian](#step-r3)
- [Step R4 — Zapisz sekcję Re-CR w raporcie](#step-r4)
- [Step R5 — Ustal werdykt Re-CR](#step-r5)
- [Step R6 — Ścieżka CHANGES REQUESTED](#step-r6)
- [Step R7 — Ścieżka APPROVED (Happy Path)](#step-r7)
- [Step R8 — Ścieżka NEEDS DISCUSSION](#step-r8)

---

## Step R0 — Ustal numer rundy Re-CR + wczytaj poprzedni raport

Uruchom skrypt (zastępuje ręczne sprawdzenie pliku i parsowanie checkboxów):

```bash
bash .claude/skills/code-review/scripts/load_previous_findings.sh {ISSUE_ID}
```

Skrypt zwraca JSON z polami:
- `recr_round` — numer bieżącej rundy (1 = pierwsza runda po oryginalnym CR)
- `previous_findings_count` — liczba niezatwierdzonych findings z poprzedniej rundy
- `findings` — lista `{index, severity, file, line, description}` niezatwierdzonych findings

Jeśli JSON zawiera `error: cr_not_found` — zatrzymaj się i poinformuj usera:
```
Nie znaleziono raportu CR: .ai/tasks/{ISSUE_ID}/cr.md
Re-review wymaga wcześniejszego CR wykonanego tym skillem.
Możliwe przyczyny:
  - Poprzedni CR był wykonany ręcznie lub innym narzędziem
  - Plik został usunięty lub nie był commitowany

Czy chcesz przeprowadzić pełny CR (nie re-review) dla #{ISSUE_ID}?
```
Jeśli user potwierdzi — wróć do Step 1 workflow.md i wykonaj pełny CR.

Zapamiętaj `recr_round` jako `RECR_ROUND` — używany w nagłówku sekcji w raporcie.

---

## Step R1 — Wczytaj poprzedni raport CR

Skrypt `load_previous_findings.sh` z Step R0 automatycznie:
- wykrywa właściwą rundę (ostatnia sekcja `## Re-CR` lub oryginalny CR)
- parsuje tylko `- [ ]` (niezatwierdzone) checkboxy z CRITICAL i WARNING
- pomija `- [x]` (już zatwierdzone)

Lista `PREVIOUS_FINDINGS` gotowa z pola `findings` w JSON.

Każdy wpis zawiera: `file`, `line`, `severity`, `description`.

---

## Step R2 — Weryfikuj poprawki

**Grupuj findings po pliku** — zamiast czytać każdy plik osobno dla każdego findingu, najpierw zbierz wszystkie findings dotyczące tego samego pliku i pobierz kontekst raz:

Najpierw jednym wywołaniem zbierz strategię kontekstu dla wszystkich plików z `PREVIOUS_FINDINGS`:

```bash
bash .claude/skills/code-review/scripts/collect_diff_stats.sh {LAST_CR_COMMIT} HEAD
```

Użyj pola `context_strategy` per plik z wynikowego JSON (znaczenie takie samo jak w Step 7a workflow.md: `full_file` / `extended_diff` / `skip`).

Dla każdej **unikalnej** ścieżki pliku z `PREVIOUS_FINDINGS`:

1. Pobierz diff dla pliku:
   ```bash
   git diff {LAST_CR_COMMIT}..HEAD -- {plik}
   ```
2. Pobierz kontekst pliku wg `context_strategy` z JSON:
   - `full_file` → przeczytaj pełną wersję (Read tool)
   - `extended_diff` → użyj `git diff -U30 {LAST_CR_COMMIT}..HEAD -- {plik}`
   - `skip` → pomiń
3. Oceń wszystkie findings dotyczące tego pliku naraz: każde **FIXED** lub **UNFIXED**

**FIXED** → zaznacz checkbox w oryginalnym raporcie: zmień `- [ ]` na `- [x]` w odpowiedniej sekcji cr.md

**UNFIXED** → zostaw `- [ ]`, dodaj adnotację na końcu linii: `← UNFIXED w Re-CR #{RECR_ROUND} ({data})`

**Sprawdź też komentarze FIXME:**
- Jeśli komentarze FIXME z poprzedniego CR zostały usunięte → sygnał FIXED (potwierdź z kodem)
- Jeśli komentarze FIXME nadal są w kodzie **bez odpowiedzi dewelopera** → sygnał UNFIXED (chyba że kod wokół nich się zmienił)

**Sprawdź odpowiedź dewelopera na FIXME:**
- Jeśli komentarz FIXME nadal jest w kodzie i bezpośrednio za nim (w tej samej linii lub w linii poniżej) pojawił się nowy komentarz, którego nie było w poprzednim CR → to jest odpowiedź dewelopera (sygnał sporu)
- Detekcja: porównaj linie wokół FIXME z poprzednim CR (diff). Nowy komentarz = tekst dodany przez dewelopera, nie przez skill
- Dla każdego takiego finding: wyodrębnij treść komentarza dewelopera i zapisz jako `DEV_RESPONSE`

**Findings z odpowiedzią dewelopera — decyzja reviewera:**

Dla każdego finding z `DEV_RESPONSE` wyświetl reviewerowi i czekaj na decyzję:

```
Finding z odpowiedzią dewelopera:

  Finding: `{plik}:{linia}` — {opis problemu}
  Odpowiedź dewelopera: "{DEV_RESPONSE}"

  Czy akceptujesz wyjaśnienie dewelopera?
  - Tak → bug oznaczony jako zrealizowany (z komentarzem dewelopera w checkliście)
  - Nie → bug pozostaje UNFIXED
```

Na podstawie decyzji reviewera:
- **Akceptacja** → zmień `- [ ]` na `- [x]` w cr.md, dodaj adnotację: `← ZAAKCEPTOWANE BEZ POPRAWY w Re-CR #{RECR_ROUND} ({data}): "{DEV_RESPONSE}"`. Usuń z kodu **oba komentarze** (FIXME + odpowiedź dewelopera).
- **Odrzucenie** → zostaw `- [ ]`, dodaj adnotację: `← UNFIXED w Re-CR #{RECR_ROUND} ({data})`. FIXME **zostaje w kodzie** (deweloper musi poprawić). Usuń z kodu **tylko odpowiedź dewelopera** (nie FIXME). Bug trafia do sekcji do poprawy.

Po weryfikacji wszystkich findings wyświetl podsumowanie:

```
Weryfikacja poprawek z poprzedniego CR:

✅ FIXED ({N}):
  - `plik:linia` — opis
  ...

✅ ZAAKCEPTOWANE BEZ POPRAWY ({K}):
  - `plik:linia` — opis — Odpowiedź dewelopera: "{DEV_RESPONSE}"
  ...

❌ UNFIXED ({M}):
  - `plik:linia` — opis
  ...

Poprawiono/zaakceptowano {N+K}/{N+K+M} zgłoszonych problemów.
```

---

## Step R3 — Review nowych zmian

Przeprowadź normalny review według Steps 7–7f z `workflow.md`.

Zakres diff dla re-review: `LAST_CR_COMMIT..HEAD` (od ostatniego commita CR do HEAD) — ustalony w Step 6a workflow.md.

Wszystkie zasady z `references/review-criteria.md` (kryteria, agenty, confidence scoring) obowiązują tak samo jak przy pierwszym CR.

**Ważne — kontekst dla agentów:** Przekaż każdemu agentowi listę `PREVIOUS_FINDINGS` (z Step R1/R2) jako dodatkowy kontekst. Każdy agent powinien:
- Nie zgłaszać ponownie FIXED findings jako nowych problemów
- Jeśli znajdzie ten sam problem co UNFIXED finding z poprzedniego CR — odnotować go jako "nadal nienaprawiony" (będzie obsłużony w R4/R5 jako UNFIXED, nie jako nowy finding)
- Skupić się na problemach **nowych** — niezgłoszonych w poprzednim CR

---

## Step R4 — Zapisz sekcję Re-CR w raporcie

**Append** (nie nadpisuj) do istniejącego `.ai/tasks/{ISSUE_ID}/cr.md`:

```markdown
---

## Re-CR #{RECR_ROUND}: {YYYY-MM-DD HH:mm}

### Weryfikacja poprzedniego CR

| # | Finding | Status | Komentarz |
|---|---------|--------|-----------|
| 1 | `plik:linia` — opis | ✅ FIXED | krótki opis co zostało zmienione |
| 2 | `plik:linia` — opis | ✅ ZAAKCEPTOWANE BEZ POPRAWY | Odpowiedź dewelopera: "{DEV_RESPONSE}" |
| 3 | `plik:linia` — opis | ❌ UNFIXED | krótki opis dlaczego nadal problem |

Podsumowanie: {N}/{M} poprawione.

### Nowe problemy

#### CRITICAL
- [ ] `{plik}:{linia}` — **[Kategoria] Opis** — szczegóły → sugestia *(tylko ścieżka równoległa: confidence: XX)*

#### WARNING
- [ ] `{plik}:{linia}` — **[Kategoria] Opis** — szczegóły → sugestia *(tylko ścieżka równoległa: confidence: XX)*

#### INFO
- `{plik}:{linia}` — {opis}

*(Sekcje CRITICAL/WARNING/INFO pomijaj jeśli brak problemów danej kategorii)*

### Podsumowanie Re-CR

{Ogólna ocena: czy poprawki były wystarczające? Co nadal wymaga pracy?}

**Werdykt:** APPROVED / CHANGES REQUESTED / NEEDS DISCUSSION
```

Jeśli brak nowych problemów — zamiast sekcji "Nowe problemy" napisz: `Brak nowych problemów w zreviewowanym zakresie.`

---

## Step R5 — Ustal werdykt Re-CR

**CHANGES REQUESTED** jeśli:
- jakiekolwiek UNFIXED stare bugi (CRITICAL lub WARNING) — ZAAKCEPTOWANE BEZ POPRAWY NIE liczy się jako UNFIXED, LUB
- nowe CRITICAL lub WARNING (z confidence >= 80)

**APPROVED** jeśli:
- wszystkie stare CRITICAL/WARNING rozwiązane (FIXED lub ZAAKCEPTOWANE BEZ POPRAWY), ORAZ
- brak nowych CRITICAL lub WARNING

**NEEDS DISCUSSION** jeśli:
- nowe zmiany wprowadzają problem architektoniczny lub niejasność wymagającą rozmowy z zespołem (analogicznie jak w głównym CR — patrz `references/review-criteria.md` sekcja "Werdykt NEEDS DISCUSSION")
- NEEDS DISCUSSION ma priorytet nad CHANGES REQUESTED — jeśli jednocześnie są UNFIXED bugi i problem architektoniczny, werdykt to NEEDS DISCUSSION

> Squash + merge proponowany tylko przy werdykcie APPROVED, niezależnie od tego która to runda (Re-CR #1, #2, #3...).

---

## Step R6 — Ścieżka CHANGES REQUESTED

### R6a. FIXME komentarze dla nowych findings i obsługa odpowiedzi dewelopera

Dla każdego nowego CRITICAL i WARNING — dodaj inline FIXME według zasad z `references/review-criteria.md` (sekcja "Inline FIXME komentarze").

Nie usuwaj istniejących FIXME komentarzy dla UNFIXED findings z poprzedniego CR.

Dla findings z odpowiedzią dewelopera z Step R2:
- **Zaakceptowane** — usuń z kodu **oba komentarze** (FIXME + odpowiedź dewelopera)
- **Odrzucone** — usuń z kodu **tylko odpowiedź dewelopera** (FIXME zostaje do poprawy)

### R6b. Commit raportu Re-CR

Zapytaj usera o zgodę na commit (analogicznie do Step 10 z workflow.md):

```
Re-CR #{RECR_ROUND} zakończony dla #{ISSUE_ID}.

Werdykt: CHANGES REQUESTED
Poprzednie CR: {N}/{M} poprawione
Zaakceptowane bez poprawy: {K}
Nowe problemy: {X} CRITICAL, {Y} WARNING, {Z} INFO

Czy mogę zacommitować zaktualizowany raport CR i nowe komentarze FIXME?
```

Po potwierdzeniu:
```bash
git add .ai/tasks/{ISSUE_ID}/cr.md
git add {pliki z nowymi komentarzami FIXME}
git add {pliki ze zmienionymi/usuniętymi komentarzami odpowiedzi dewelopera}
git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}"
```

Zapytaj o push.

### R6c. Utwórz nowe zagadnienie Redmine

→ Szczegóły w `references/redmine-integration.md` (sekcja "Tworzenie zagadnienia Re-CR")

Subject: `CR BUG: {ISSUE_SUBJECT}`

Opis zawiera:
- UNFIXED stare bugi (przeniesione z poprzedniego CR) — **ZAAKCEPTOWANE BEZ POPRAWY nie trafiają tutaj** (są rozwiązane)
- Nowe bugi znalezione w Re-CR

---

## Step R7 — Ścieżka APPROVED (Happy Path)

### R7a. Commit raportu Re-CR

Zapytaj usera o zgodę:

```
Re-CR #{RECR_ROUND} zakończony dla #{ISSUE_ID}.

Werdykt: APPROVED ✅
Poprzednie CR: {N}/{N} rozwiązane (wszystkie)
[Zaakceptowane bez poprawy: {K}]  ← tylko jeśli K > 0
Nowe problemy: brak CRITICAL/WARNING

Czy mogę zacommitować zaktualizowany raport CR?
```

Po potwierdzeniu:
```bash
git add .ai/tasks/{ISSUE_ID}/cr.md
git add {pliki z usuniętymi komentarzami FIXME i odpowiedziami dewelopera}  # jeśli były zaakceptowane
git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT}"
```

Przy APPROVED nie dodajemy żadnego sufiksu (`#BUG|`, `#CR|` itp.) — commit jest czystym commitem feature bez oznaczenia bugów.

Zapytaj o push.

### R7b. Propozycja squash

Sprawdź czy squash jest możliwy:

```bash
git log --oneline --merges origin/{DEFAULT_BRANCH}..HEAD
```

Jeśli są merge commity → poinformuj usera że squash niemożliwy (merge commity w historii) i przejdź do R7c.

Jeśli jest tylko 1 commit na branchu → pomiń squash, przejdź do R7c.

W pozostałych przypadkach — **zaproponuj squash i CZEKAJ na potwierdzenie usera**:

```
Re-CR: APPROVED. Propozycja: squash {N} commitów do jednego.

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

### R7c. Propozycja merge do DEFAULT_BRANCH

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

### R7d. Podsumowanie końcowe APPROVED

```
✅ Re-CR #{RECR_ROUND} zakończony — APPROVED

Zagadnienie: #{ISSUE_ID} — {ISSUE_SUBJECT}
Branch: feature/{ISSUE_ID}

Poprawki: {N}/{N} zweryfikowanych problemów rozwiązanych
[w tym zaakceptowane bez poprawy: {K} — "{DEV_RESPONSE}"]  ← tylko jeśli K > 0
Nowe problemy: brak CRITICAL/WARNING

Commit CR: {commit_hash}
[Squash: {squash_commit_hash}]  ← tylko jeśli wykonano squash
[Merge: feature/{ISSUE_ID} → {DEFAULT_BRANCH}]  ← tylko jeśli wykonano merge

Nie utworzono zagadnienia w Redmine (APPROVED).
```

---

## Step R8 — Ścieżka NEEDS DISCUSSION

### R8a. Commit raportu Re-CR

Zapytaj usera o zgodę (analogicznie do R6b):

```
Re-CR #{RECR_ROUND} zakończony dla #{ISSUE_ID}.

Werdykt: NEEDS DISCUSSION
Poprzednie CR: {N}/{M} poprawione
Nowe problemy wymagające rozmowy z zespołem: {opis kwestii}

Czy mogę zacommitować zaktualizowany raport CR?
```

Po potwierdzeniu:
```bash
git add .ai/tasks/{ISSUE_ID}/cr.md
git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT} #BUG|{AUTHOR_LOGIN}"
```

Zapytaj o push.

### R8b. Nie twórz zagadnienia w Redmine

NEEDS DISCUSSION nie generuje zagadnienia Task/Bug — wymagana jest rozmowa z zespołem przed podjęciem działań. Poinformuj usera:
```
Nie utworzono zagadnienia w Redmine — wymagana rozmowa z zespołem przed podjęciem działań.
```

Nie proponuj squash ani merge do {DEFAULT_BRANCH}.

### R8c. Podsumowanie końcowe NEEDS DISCUSSION

```
✅ Re-CR #{RECR_ROUND} zakończony dla #{ISSUE_ID}.

Raport: .ai/tasks/{ISSUE_ID}/cr.md
Commit CR: {commit_hash}

Werdykt: NEEDS DISCUSSION — znaleziono problemy wymagające rozmowy z zespołem.
[Opis kwestii do omówienia]

Poprzednie CR: {N}/{M} poprawione
Nie utworzono zagadnienia w Redmine — wymagana rozmowa przed podjęciem działań.
```
