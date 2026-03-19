---
name: merge-to-release
version: 1.0.0
description: Skill do mergowania gotowych zagadnien (US/Epic/Spike) do galezi release. Uzywaj gdy user prosi o merge do release, wlaczenie do wydania, zmergowanie feature brancha, lub podaje numer zagadnienia Redmine w kontekscie mergowania. Skill przeglada zmiany pod katem naruszen architektonicznych i zakazanych slow w kodzie, merguje do release i aktualizuje Redmine. Trigger: merge, release, wydanie, wlacz do release, zmerguj.
argument-hint: <numer_zagadnienia> <branch_release> (np. 184819 release/4.3.0)
---

# Merge to Release

Skill do przegladania i mergowania gotowych zagadnien do galezi release z aktualizacja Redmine.

---

## Kiedy uzywac

- User prosi o "merge do release", "wlacz do wydania", "zmerguj feature"
- User podaje numer zagadnienia i branch release
- User chce przejrzec i zmergowac gotowe US/Epic/Spike

## Kiedy nie uzywac

- Merge do develop (inna procedura)
- Hotfixy (inna sciezka branchowania)
- Code review bez mergowania (uzyj skilla `code-review`)

---

## Wymagania

- Narzedzie **Redmine MCP** (`redmine_request`) -- zalecane; bez niego skill dziala w trybie manualnym
- Repozytorium **git** z feature branchem i branchem release

### Dane wejsciowe

Skill przyjmuje dwa argumenty:
1. **Numer zagadnienia Redmine** (ISSUE_ID)
2. **Branch release** (np. `release/4.3.0`)

Jesli argumenty nie sa podane w `$ARGUMENTS`, zapytaj usera.

---

## Procedura (9 krokow)

### Step 1 -- Parsuj dane wejsciowe

Wyciagnij z `$ARGUMENTS` lub z kontekstu rozmowy:
- `ISSUE_ID` -- numer zagadnienia Redmine
- `RELEASE_BRANCH` -- branch release (np. `release/4.3.0`)
- `RELEASE_VERSION` -- wersja z nazwy brancha (np. `4.3.0`)

Jesli brakuje ktorejkolwiek wartosci -- zapytaj usera.

### Step 2 -- Pobierz dane z Redmine

Przeczytaj `references/redmine-integration.md` i wykonaj krok pobierania zagadnienia.

Pobierz:
- subject, tracker, status, children (podzagadnienia)
- Zweryfikuj tracker (dozwolone: US=18, US Bug=19, Epic=22, Spike=23)

Wyswietl podsumowanie:
```
Zagadnienie #{ISSUE_ID}: {subject}
Tracker: {tracker} | Status: {status}
Podzagadnienia: {liczba}
```

### Step 3 -- Fetch i checkout

```bash
git fetch --all
```

Sprawdz czy istnieje branch `feature/{ISSUE_ID}`. Jesli nie -- zapytaj usera o nazwe brancha.

**Nie rób checkout feature brancha** -- zostajemy na aktualnym uzywanym branchu. Diff robimy zdalnie.

### Step 4 -- Przeglad zmian

Wykonaj diff feature brancha wzgledem release:

```bash
git log origin/{RELEASE_BRANCH}..origin/feature/{ISSUE_ID} --oneline
git diff origin/{RELEASE_BRANCH}...origin/feature/{ISSUE_ID}
```

Przejrzyj zmiany pod katem:

#### 4a. Zakazane slowa w kodzie

Przeszukaj diff pod katem **polskich slow i zakazanych fraz** w kodzie zrodlowym (nie w FIXME komentarzach z CR, nie w plikach `.ai/`).

Zakazane wzorce (case-insensitive):
- Polskie slowa w komentarzach (`//`, `/* */`, `#`) i w stringach/identyfikatorach -- np. `TODO`, `FIXME` po polsku, polskie opisy w kodzie
- `eVolpe`, `evolpe` (case-insensitive) -- w jakimkolwiek kontekscie w kodzie

**Wylaczenia** -- NIE zglaszaj:
- Pliki `.ai/tasks/**` (raporty CR, plany)
- Komentarze FIXME z code review (`// FIXME [CR]` itp.)
- Pliki tlumaczen/jezykowe (`lang/`, `language/`, `.lang.php`)
- Zmiany w plikach konfiguracyjnych Redmine/CI

#### 4b. Narusenia architektoniczne

Przejrzyj diff pod katem:
- Modyfikacje plikow core zamiast `custom/` (legacy i api)
- Niezgodnosc ze struktura modulow
- Brakujace/nieodpowiednie wzorce (np. bezposrednie zapytania SQL zamiast ORM w api/)

Uzywaj wiedzy z CLAUDE.md i zaladowanych skilli (`minthcm-core`, `minthcm-project`, `coding-standards`) jesli sa dostepne w kontekscie.

### Step 5 -- Raport z przegladu

Wyswietl raport:

```
## Przeglad zmian dla #{ISSUE_ID}: {subject}

### Commity ({liczba})
{lista commitow --oneline}

### Pliki zmienione ({liczba})
{lista plikow}

### Zakazane slowa
{lista znalezionych problemow lub "Brak"}

### Uwagi architektoniczne
{lista uwag lub "Brak"}
```

Jesli sa problemy -- zapytaj:
```
Znaleziono problemy w kodzie. Co chcesz zrobic?
(m) Kontynuuj merge mimo problemow
(b) Zglos buga w Redmine i przerwij merge
(n) Przerwij bez zglaszania
```

Jesli nie ma problemow:
```
Brak uwag do zmian. Kontynuowac merge feature/{ISSUE_ID} -> {RELEASE_BRANCH}?
(t/n)
```

**Czekaj na odpowiedz usera.** Nie kontynuuj bez potwierdzenia.

### Step 5b -- Zgloszenie buga w Redmine (opcjonalne)

Jesli user wybral opcje (b) w Step 5:

1. **Znajdz przypisana osobe** -- z listy podzagnien (children) pobierz podzagadnienie z prefixem `BUG:` i sprawdz kto jest do niego przypisany (`assigned_to`). Jesli nie ma podzagadnienia BUG, uzyj assigned_to z zagadnienia glownego.

```
redmine_request: GET /issues/{BUG_CHILD_ID}.json
```

2. **Utworz podzagadnienie** typu Task (tracker_id: 24) z prefixem `BUG:` i kategorią Bug (category_id: 813):

```
redmine_request: POST /issues.json
```

```json
{
  "issue": {
    "project_id": {PROJECT_ID},
    "tracker_id": 24,
    "subject": "BUG: {opis problemu}",
    "parent_issue_id": {ISSUE_ID},
    "assigned_to_id": {ASSIGNED_TO_ID},
    "category_id": 813,
    "description": "{opis problemu z raportu przegladu -- uwagi architektoniczne lub zakazane slowa}"
  }
}
```

- `tracker_id: 24` -- Task (Redmine wymaga Task jako podzagadnienie US/Epic)
- `category_id: 813` -- Bug (kategoria w projekcie MintHCM - Development)
- `assigned_to_id` -- osoba przypisana do podzagadnienia BUG z implementacji

3. Wyswietl potwierdzenie:
```
Utworzono podzagadnienie #{NEW_ID}: BUG: {opis}
Przypisano do: {imie nazwisko}
Link: https://redmine.evolpe.net/issues/{NEW_ID}
```

4. Zakoncz procedure (nie kontynuuj do Step 6).

### Step 6 -- Merge

Po potwierdzeniu usera:

```bash
git checkout {RELEASE_BRANCH}
git pull origin {RELEASE_BRANCH}
git merge --no-ff origin/feature/{ISSUE_ID} -m "Merge feature/{ISSUE_ID} #{ISSUE_ID} {ISSUE_SUBJECT}"
```

Jesli sa konflikty:
1. Wyswietl liste plikow z konfliktami
2. Zaproponuj rozwiazanie lub zapytaj usera
3. Po rozwiazaniu -- kontynuuj merge

### Step 7 -- Push

Zapytaj usera:
```
Merge wykonany lokalnie. Czy wykonac push do origin/{RELEASE_BRANCH}?
(t/n)
```

Jesli tak:
```bash
git push origin {RELEASE_BRANCH}
```

### Step 8 -- Aktualizacja Redmine

Przeczytaj `references/redmine-integration.md` i wykonaj kroki aktualizacji.

Po pushu (lub jesli user zdecydowal nie pushowac -- zapytaj czy mimo to aktualizowac Redmine):

#### 8a. Aktualizacja zagadnienia glownego

```
redmine_request: PUT /issues/{ISSUE_ID}.json
```

```json
{
  "issue": {
    "status_id": 3,
    "release_id": {RELEASE_ID}
  }
}
```

- `status_id: 3` -- Rozwiazane
- `release_id` -- ID release z pluginu Redmine Releases (pole "Release" na formularzu zagadnienia)

**Aby uzyskac `RELEASE_ID`** -- przeczytaj `references/redmine-integration.md` sekcja "Mapowanie release_id".

#### 8b. Aktualizacja podzagnien

Dla kazdego podzagadnienia (child) z Step 2:

```
redmine_request: PUT /issues/{CHILD_ID}.json
```

```json
{
  "issue": {
    "status_id": 5
  }
}
```

- `status_id: 5` -- Zrealizowane

### Step 9 -- Podsumowanie

Wyswietl podsumowanie:

```
## Gotowe

- Merge: feature/{ISSUE_ID} -> {RELEASE_BRANCH} ✓
- Push: {tak/nie}
- Redmine #{ISSUE_ID}: Release={RELEASE_VERSION}, Status=Rozwiazane ✓
- Podzagadnienia ({liczba}): Status=Zrealizowane ✓
```

---

## Routing do references

| Kiedy | Czytaj |
|---|---|
| Steps 2, 8 (Redmine API calls) | `references/redmine-integration.md` |
| Steps 2, 8 bez Redmine MCP | `references/redmine-integration.md` (sekcje "Tryb manualny") |

---

## Oczekiwany sposob pracy

- **Potwierdzaj z userem** w kluczowych momentach: merge (Step 5), push (Step 7)
- **Nie merguj bez zgody** -- zawsze czekaj na odpowiedz
- **Nie pushuj bez zgody** -- zawsze pytaj
- **Zakazane slowa** -- przegladaj tylko kod zrodlowy, nie pliki tlumaczen ani raporty CR
