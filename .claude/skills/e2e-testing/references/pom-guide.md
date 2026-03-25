# Page Object Model (POM)

## ZASADA ZEROWA: nie zgaduj selektorów — sprawdź na instancji

**NIGDY nie zakładaj** jakie ID, klasy CSS czy wartości mają pola na formularzu. Nawet jeśli User Story podaje nazwę pola (np. "Birthdate"), nie zakładaj że selektor to `#birthdate` — może to być `#date_of_birth`, `#Birthdate_c`, `input[name="birthdate"]` lub cokolwiek innego.

**Przed napisaniem jakiegokolwiek POM:**
1. Otwórz instancję w przeglądarce (lub przez Claude in Chrome)
2. Przejdź do formularza, który testujesz
3. Zbadaj DOM w DevTools (F12 → Elements) i znajdź **rzeczywiste** selektory
4. Dla pól typu dropdown/enum — sprawdź rzeczywiste `value` w `<option>` (mogą się różnić od nazw widocznych na UI)
5. Dopiero potem pisz POM z prawdziwymi selektorami

To dotyczy **każdego nowego pola i modułu** — nie ma wyjątków. US opisuje co pole robi, nie jak jest zaimplementowane w DOM.

---

## Struktura klasy Page Object

```javascript
const { expect } = require('@playwright/test');

class CreateCandidatePage {
  constructor(page) {
    this.page = page;

    // Lokatory pól formularza
    this.lastNameInput = page.locator('#last_name');
    this.firstNameInput = page.locator('#first_name');
    this.emailInput = page.locator('#email1');

    // Przyciski
    this.saveButton = page.locator('#SAVE');

    // Komunikaty
    this.validationMessage = page.locator('.validation-message');
    this.successMessage = page.locator('.success-message');
    this.errorMessage = page.locator('.error-message');
  }

  async goto() {
    await this.page.goto(
      `${process.env.CONTRAIN_BASE_URL}/path/to/create/form`
    );
    await this.lastNameInput.waitFor({ state: 'visible', timeout: 15000 });
  }

  async fillForm(data) {
    await this.lastNameInput.fill(data.lastName);
    await this.firstNameInput.fill(data.firstName);
    if (data.email) {
      await this.emailInput.fill(data.email);
    }
  }

  async save() {
    await this.saveButton.click();
  }
}

module.exports = { CreateCandidatePage };
```

> **Uwaga:** Ten przykład nie zawiera iframe — to generyczny wzorzec. Dla MintHCM (iframe) zobacz skill `minthcm-e2e`.

## Zasady POM

### Konstruktor — tylko lokatory
W konstruktorze definiuj `this.page` i lokatory. Nie wykonuj żadnych akcji (nawigacja, kliknięcia).

### Lokatory — konkretne selektory
Priorytet selektorów (od najlepszego):
1. `#id` — gdy unikalny
2. `[data-testid="..."]` — jeśli dostępny
3. `.klasa` z kontekstem — np. `.modal .save-button`
4. `text=Tekst` — dla przycisków z unikalnym tekstem
5. Selektory CSS złożone — jako ostateczność

**Sprawdź selektor w DevTools** zanim go użyjesz w POM. Nie zgaduj.

### Metody — akcje użytkownika
Każda metoda POM to jedna akcja lub logiczna grupa akcji:
- `goto()` — nawigacja + waitFor
- `fillForm(data)` — wypełnienie formularza
- `save()` — kliknięcie save
- `getValidationError()` — pobranie treści błędu

### Strict mode violation
Gdy selektor trafia w wiele elementów, Playwright rzuca `strict mode violation`. Rozwiązania:
- Zawęź selektor: `select#id` zamiast `#id`
- Użyj `.first()` gdy celowo chcesz pierwszy element
- Dodaj kontekst rodzica: `page.locator('.modal').locator('#save')`

### Export
Zawsze eksportuj przez `module.exports`:
```javascript
module.exports = { CreateCandidatePage };
```

## Kiedy Page Object, kiedy nie

| Sytuacja | Decyzja |
|----------|---------|
| Formularz z wieloma polami | Osobny POM |
| Strona logowania | `shared/pages/LoginPage.js` |
| Lista rekordów z filtrowaniem | Osobny POM |
| Prosty modal z 1-2 polami | Można inline w teście |
| Komponent współdzielony (np. sidebar) | `shared/pages/` |

## Lokalizacja POM

- POM specyficzny dla modułu → `modules/<moduł>/pages/`
- POM współdzielony (login, nawigacja, sidebar) → `shared/pages/`

## Platforma a POM

Różne systemy CRM mają różne wzorce UI. Specyfikę platformy (iframe w MintHCM, Angular w SpiceCRM) opisuje **skill systemowy E2E** (np. `minthcm-e2e`, `spicecrm-e2e`). POM musi uwzględniać te wzorce.

Typowe różnice między platformami:
- **Formularze w iframe** — wymaga `page.frameLocator()` przed lokatorami
- **Frameworki JS** — Vue/Angular mogą wymagać specyficznych triggerów po wypełnieniu pól
- **Toasty / komunikaty** — różne selektory i lokalizacja (w iframe vs poza)

Zawsze sprawdź skill systemowy E2E przed pisaniem POM dla nowego modułu.
