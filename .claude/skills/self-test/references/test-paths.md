# Test Paths — Self Test

## 7 kroków procedury

---

## Step 1 — Ustal kontekst (ISSUE_ID) {#step-1}

Sprawdź czy jesteś wewnątrz repozytorium git:

```bash
git rev-parse --is-inside-work-tree
```

**Ustal ISSUE_ID:**

1. Jeśli wywołano jako slash command z argumentem (`/self-test 184819`):
   - Użyj podanego numeru jako `ISSUE_ID`

2. Jeśli brak argumentu — wyciągnij z nazwy aktualnego brancha:
   ```bash
   git branch --show-current
   ```
   - Pattern `feature/{ID}` lub `feature/{ID}-*` — użyj `{ID}` jako `ISSUE_ID`
   - Jeśli branch nie pasuje — zapytaj usera: „Nie mogę ustalić numeru zagadnienia z nazwy brancha. Podaj numer zagadnienia."

**Ustal DEFAULT_BRANCH:**

```bash
git symbolic-ref refs/remotes/origin/HEAD 2>/dev/null | sed 's|refs/remotes/origin/||'
```

Jeśli zawiedzie:
```bash
git branch -r | grep -E 'origin/(main|develop|master)$' | head -1 | sed 's|.*origin/||'
```

---

## Step 2 — Analiza zmian {#step-2}

Pobierz diff:

```bash
git diff origin/{DEFAULT_BRANCH}...HEAD --name-only
git diff origin/{DEFAULT_BRANCH}...HEAD --stat
git diff origin/{DEFAULT_BRANCH}...HEAD
```

**Wczytaj plan** (jeśli istnieje):

```
.ai/tasks/{ISSUE_ID}/plan.md
```

Jeśli plan istnieje — wyciągnij z niego:
- Kryteria akceptacji → każde kryterium stanie się ścieżką testową
- Moduły/widoki wskazane do implementacji → punkty do sprawdzenia w regresji

**Zidentyfikuj z diffu:**

- Zmienione moduły / widoki / endpointy
- Zmienione formularze lub pola
- Zmienione zapytania DB / API
- Zmienione pliki JS/CSS (frontend) → podstawa do Chrome snippetów
- Powiązane moduły które mogą być dotknięte zmianami (regresja)

---

## Step 3 — Generuj ścieżki testowe {#step-3}

Generuj ścieżki wg czterech typów:

### Happy path

Główny scenariusz użycia feature'a, krok po kroku. Dla każdego zmienionego widoku/akcji:

```
1. Otwórz [moduł] → [widok]
2. Wykonaj [akcję] (np. wypełnij formularz, kliknij przycisk)
3. Sprawdź: [oczekiwany rezultat]
```

Ścieżka powinna być konkretna — podaj URL jeśli znany z diffu, nazwy pól jeśli widoczne w kodzie.

### Edge cases

Przypadki brzegowe dla każdej zmienionej funkcjonalności:

- **Puste dane** — co się dzieje gdy pole jest puste? Pojawia się walidacja?
- **Dane graniczne** — maksymalna długość tekstów, zera, wartości ujemne
- **Znaki specjalne** — apostrofy, cudzysłowy, HTML w polach tekstowych
- **Brak uprawnień** — czy feature działa poprawnie gdy user nie ma dostępu?
- **Brakujące powiązane rekordy** — czy aplikacja obsługuje brak powiązanego obiektu?

### Regresja

Na podstawie diffu — jakie istniejące funkcjonalności mogą być dotknięte zmianami?

- Dla każdego zmienionego modelu/klasy — sprawdź inne widoki/akcje które go używają
- Dla zmienionych szablonów — sprawdź czy inne miejsca używające tego szablonu działają
- Dla zmienionych metod współdzielonych — sprawdź inne wywołujące je miejsca

### Ścieżki z kryteriów akceptacji

Jeśli plan zawiera kryteria akceptacji — każde kryterium przekształć w ścieżkę testową:

```
Kryterium: "User powinien móc edytować adres email"
→ Ścieżka: Otwórz profil usera → kliknij Edytuj → zmień email → zapisz → sprawdź że nowy email jest widoczny
```

---

## Step 4 — Chrome console snippety {#step-4}

**Generuj snippety tylko gdy zmiana dotyczy frontendu** (JS, Vue, Smarty/Twig widoki, formularze, CSS layout).

Jeśli diff nie zawiera plików frontend — pomiń ten krok i odnotuj w raporcie: *(pominięto — brak zmian frontend)*.

**Co snippety powinny sprawdzać:**

- Obecność elementów DOM — czy nowe pole/przycisk/sekcja renderuje się?
- Wartości pól formularza — czy pole ma właściwą domyślną wartość?
- Obecność klas CSS — czy element ma właściwe klasy po akcji?
- Wywołania API — czy request jest wysyłany z właściwymi parametrami?

**Format snippetu:**

```js
// [Co sprawdza ten snippet]
document.querySelector('[selektor]')
  ? console.log('OK: [opis co jest ok]')
  : console.error('FAIL: [opis co jest nie tak]');
```

Snippet powinien być samowyjaśniający — komentarz opisuje co weryfikuje, wynik console.log/error jasno mówi czy jest OK czy FAIL.

---

## Step 5 — Zapisz raport {#step-5}

Utwórz katalog jeśli nie istnieje:

```bash
mkdir -p .ai/tasks/{ISSUE_ID}
```

Zapisz raport do `.ai/tasks/{ISSUE_ID}/self-test.md`.

**Ważne:** Nadpisuj istniejący plik — nie appenduj (chyba że krok 7 dodaje wyniki testu w przeglądarce — wtedy aktualizuj sekcję wyników).

---

## Step 6 — Zaproponuj test w Chrome {#step-6}

Po zapisaniu raportu zapytaj usera:

```
Raport z ścieżkami testowymi gotowy: .ai/tasks/{ISSUE_ID}/self-test.md

Znaleziono {N} ścieżek testowych ({X} happy path, {Y} edge cases, {Z} regresja).

Czy chcesz żebym przetestował te ścieżki w przeglądarce?
Jeśli tak — uruchom `/chrome` żeby podłączyć rozszerzenie Chrome, a następnie wróć tutaj.
```

Jeśli user nie chce lub nie odpowie — zakończ. Raport z samymi ścieżkami jest wystarczający.

---

## Step 7 — Test w przeglądarce (opcjonalny) {#step-7}

**Warunek:** Wykonaj tylko jeśli user uruchomił `/chrome` i potwierdził chęć testu.

Dla każdej ścieżki testowej z raportu:

1. Nawiguj do odpowiedniego URL-a
2. Wykonaj kroki ścieżki (kliknięcia, wypełnianie formularzy)
3. Zweryfikuj oczekiwany rezultat

**Przy logowaniu / CAPTCHA:** Zatrzymaj się i poproś usera o pomoc:
```
Wymagane logowanie (lub CAPTCHA) — zaloguj się ręcznie i daj mi znać gdy jesteś gotowy.
```

**Przy błędzie / nieoczekiwanym zachowaniu:**
- Odnotuj jako FAIL z opisem co się stało
- Zrób screenshot jeśli możliwe

**Aktualizuj raport** po każdej ścieżce — dopisz status OK/FAIL do sekcji wyników.

---

## Format raportu

Zapisz do `.ai/tasks/{ISSUE_ID}/self-test.md`:

````markdown
# Self Test: #{ISSUE_ID} — {subject}

**Data:** {YYYY-MM-DD}
**Branch:** {branch_name}

## Happy path

1. Otwórz [moduł] → [widok]
   - Wykonaj: [akcja]
   - Sprawdź: [oczekiwany rezultat]

2. ...

## Edge cases

3. [Opis edge case]
   - Wykonaj: [akcja]
   - Sprawdź: [oczekiwany rezultat]

## Regresja

5. [Moduł/widok] → sprawdź czy [funkcjonalność] działa poprawnie

## Chrome console snippety

*(pominięto — brak zmian frontend)*

lub:

```js
// Sprawdź czy pole [nazwa_pola] renderuje się
document.querySelector('#nazwa_pola')
  ? console.log('OK: pole renderuje się')
  : console.error('FAIL: brak pola #nazwa_pola');
```

## Wyniki testu w przeglądarce

*(sekcja opcjonalna — wypełniana po uruchomieniu `/chrome`)*

| # | Ścieżka | Status | Uwagi |
|---|---------|--------|-------|
| 1 | Happy path #1 | OK / FAIL | opis jeśli FAIL |
| 2 | Edge case #1 | OK / FAIL | |

## Checklist

- [ ] Happy path przetestowany
- [ ] Edge cases sprawdzone
- [ ] Regresja OK
- [ ] Chrome snippety wykonane (jeśli dotyczy)
````

---

## Zasady generowania — podsumowanie

| Typ ścieżki | Źródło | Priorytet |
|-------------|--------|-----------|
| Happy path | diff — zmienione widoki/akcje | Zawsze |
| Edge cases | diff — formularze, walidacja, pola | Zawsze |
| Regresja | diff — zmienione klasy/szablony współdzielone | Gdy widoczne w diffie |
| Z kryteriów akceptacji | plan.md | Gdy plan istnieje |
| Chrome snippety | diff — pliki JS/CSS/szablony | Tylko dla frontendu |
