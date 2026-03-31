# Pisanie testów — scenariusze, asercje, struktura

## Struktura pliku spec

```javascript
const { test, expect } = require('@playwright/test');
const { LoginPage } = require('../../shared/pages/LoginPage');
const { CreateCandidatePage } = require('../pages/CreateCandidatePage');
const { generateCandidate } = require('../../shared/helpers/fixtures/candidateFixtures');

test.describe('Tworzenie kandydata', () => {

  test.beforeEach(async ({ page }) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login(
      process.env.CONTRAIN_ADMIN_LOGIN,
      process.env.CONTRAIN_ADMIN_PASSWORD
    );
    await expect(page).not.toHaveURL(/#\/auth\/login/, { timeout: 15000 });
  });

  test('TC-01: Poprawne dane', async ({ page }) => {
    const candidatePage = new CreateCandidatePage(page);
    const data = generateCandidate();
    await candidatePage.goto();
    await candidatePage.fillForm(data);
    await candidatePage.save();
    await expect(page).toHaveURL(/DetailView/, { timeout: 15000 });
  });

  test('TC-02: Brak nazwiska', async ({ page }) => {
    const candidatePage = new CreateCandidatePage(page);
    const data = generateCandidate({ lastName: '' });
    await candidatePage.goto();
    await candidatePage.fillForm(data);
    await candidatePage.save();
    await expect(candidatePage.validationMessage).toBeVisible({ timeout: 8000 });
    await expect(page).toHaveURL(/EditView/, { timeout: 5000 });
  });

});
```

## Kolejność w pliku spec

1. Importy (require)
2. `test.describe` z krótką nazwą
3. `test.beforeEach` — logowanie
4. Testy w kolejności: happy path → walidacja → edge cases
5. `test.afterEach` — cleanup (jeśli potrzebny)

## Scenariusze — co testować

Dla każdego formularza/modułu planuj minimum 3 scenariusze:

### Happy path (TC-01)
- Wypełnij formularz poprawnymi danymi
- Zapisz
- Asercja: przekierowanie do DetailView, dane widoczne

### Walidacja (TC-02, TC-03...)
- Pomiń wymagane pole → komunikat walidacji
- Wpisz niepoprawne dane → komunikat błędu
- Po błędzie: dane nie znikają, URL nadal EditView

### Edge cases (TC-04+)
- Duplikat (np. ten sam email)
- Maksymalna długość pola
- Znaki specjalne w polach tekstowych
- Puste opcjonalne pola

## Asercje — dobre praktyki

```javascript
// ✅ URL po zapisie
await expect(page).toHaveURL(/DetailView/, { timeout: 15000 });

// ✅ Walidacja — formularz nie został wysłany
await expect(candidatePage.validationMessage).toBeVisible({ timeout: 8000 });
await expect(page).toHaveURL(/EditView/, { timeout: 5000 });

// ✅ Tekst w elemencie
await expect(candidatePage.successMessage).toContainText('zapisano');

// ✅ Dane w DetailView
await expect(page.locator('#detail_last_name')).toHaveText(data.lastName);
```

### Timeouty w asercjach

Zawsze podawaj explicit timeout — formularze CRM ładują się wolniej:

```javascript
// ✅ Dobrze — explicit timeout
await expect(page).toHaveURL(/DetailView/, { timeout: 15000 });

// ❌ Źle — domyślny timeout (5s) może być za krótki
await expect(page).toHaveURL(/DetailView/);
```

## beforeEach — logowanie

Każdy plik spec powinien mieć `beforeEach` z logowaniem:

```javascript
test.beforeEach(async ({ page }) => {
  const loginPage = new LoginPage(page);
  await loginPage.goto();
  await loginPage.login(
    process.env.CONTRAIN_ADMIN_LOGIN,
    process.env.CONTRAIN_ADMIN_PASSWORD
  );
  await expect(page).not.toHaveURL(/#\/auth\/login/, { timeout: 15000 });
});
```

Nigdy nie hardkoduj danych logowania — zawsze z `.env`.

## afterEach — cleanup

Jeśli test tworzy dane, rozważ cleanup:

```javascript
test.afterEach(async ({ request }) => {
  if (createdRecordId) {
    await deleteCandidateViaApi(request, createdRecordId);
  }
});
```

Preferuj cleanup przez API (szybszy, niezależny od UI).

## Przed pisaniem testów — sprawdź User Story

Przed pisaniem testów dla nowego modułu **zawsze sprawdź kontekst w Redmine** (przez MCP):
- Przeczytaj **treść User Story** — co dokładnie ma robić moduł/funkcjonalność
- Sprawdź **kryteria akceptacji** — to jest Twoja checklista tego co musi działać
- Jakie pola ma formularz i jakie mają właściwości (typ, wymagalność, walidacja)
- Jakie są reguły walidacji i automatyzacji
- Czy są zależności między modułami

Kryteria akceptacji z US to **podstawa scenariuszy testowych** — każde kryterium powinno mieć odpowiadający mu test. Dopiero po przeczytaniu US dobierz odpowiednią checklistę (`module-test-checklist.md` lub `field-test-checklist.md`) i uzupełnij o elementy, których US nie pokrywa a checklist wymaga.
