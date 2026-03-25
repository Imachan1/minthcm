# MintHCM — specyfika testowania (wspólne dla wszystkich wersji)

## OBOWIĄZKOWE: odkrywanie selektorów na żywej instancji

Przed napisaniem jakiegokolwiek POM w MintHCM **musisz sprawdzić DOM na żywej instancji**. Nie zakładaj selektorów na podstawie nazw pól z US — MintHCM generuje ID i klasy dynamicznie, a pola custom mogą mieć suffix `_c` lub zupełnie nieintuicyjne nazwy.

**Jak sprawdzić selektory:**
1. Otwórz instancję → przejdź do formularza (EditView)
2. W DevTools (F12) znajdź iframe z formularzem: `document.querySelectorAll('iframe')` — sprawdź `src` każdego
3. Wejdź w kontekst iframe w DevTools i zbadaj pola
4. Dla dropdown/enum — sprawdź `<select>` i jego `<option value="...">` — wartości mogą się różnić od etykiet widocznych na UI
5. Dla pól relacyjnych — sprawdź czy jest input QS + przycisk popup (strzałka)

Dopiero po zebraniu prawdziwych selektorów pisz POM.

---

## Formularze w iframe

Formularze EditView w MintHCM są osadzone w **legacy iframe** (`legacy/index.php`). Wszystkie lokatory pól formularza muszą przechodzić przez `frameLocator`:

```javascript
class CreateCandidatePage {
  constructor(page) {
    this.page = page;

    // Iframe — formularze EditView
    this.iframeLocator = page.frameLocator('iframe').first();

    // Lokatory pól — WEWNĄTRZ iframe
    this.lastNameInput = this.iframeLocator.locator('#last_name');
    this.firstNameInput = this.iframeLocator.locator('#first_name');
    this.saveButton = this.iframeLocator.locator('#SAVE').first();

    // Walidacja — WEWNĄTRZ iframe
    this.validationMessage = this.iframeLocator.locator('.validation-message');

    // Toast messages — POZA iframe (w głównym dokumencie)
    this.errorMessage = this.page.locator('.mint-status-box-error');
    this.successMessage = this.page.locator('.mint-status-box-success');
  }
}
```

### Zasada: co jest w iframe, co jest poza

| Element | Gdzie | Lokator |
|---------|-------|---------|
| Pola formularza (input, select, textarea) | W iframe | `this.iframeLocator.locator(...)` |
| Przycisk SAVE | W iframe | `this.iframeLocator.locator('#SAVE')` |
| Komunikaty walidacji formularza | W iframe | `this.iframeLocator.locator('.validation-message')` |
| Toast success/error (mint-status-box) | Poza iframe | `this.page.locator(...)` |
| Menu nawigacyjne | Poza iframe | `this.page.locator(...)` |
| URL (po zapisie → DetailView) | Poza iframe | `expect(page).toHaveURL(...)` |

### Który iframe?

W MintHCM są dwa warianty:
- `page.frameLocator('iframe').first()` — najczęściej poprawny (legacy EditView)
- `page.frameLocator('iframe').nth(1)` — w niektórych widokach (np. gdy jest dodatkowy iframe w nagłówku)

**Zawsze zweryfikuj w DevTools** który iframe zawiera Twój formularz. Użyj:
```javascript
document.querySelectorAll('iframe')
```
i sprawdź `src` każdego iframe.

## Vue.js — wypełnianie pól

MintHCM używa Vue.js. Ma to konsekwencje:

### `fill()` zamiast `type()`

Zawsze używaj `fill()` — triggeruje eventy Vue (input, change). `type()` wpisuje znak po znaku i może nie triggerować walidacji.

```javascript
// ✅ Dobrze
await this.lastNameInput.fill(data.lastName);

// ❌ Źle
await this.lastNameInput.type(data.lastName);
```

### Daty — Tab po wypełnieniu

Pola datowe wymagają naciśnięcia `Tab` po wpisaniu wartości, żeby Vue przetworzył zmianę:

```javascript
await this.birthdateInput.fill('01.01.1990');
await this.birthdateInput.press('Tab');
```

### Select / dropdown

Dla standardowych selectów HTML:
```javascript
await this.stateSelect.selectOption('mazowieckie');
```

Dla custom dropdownów Vue (np. z autocomplete):
```javascript
await this.departmentInput.fill('HR');
await this.page.waitForTimeout(500); // czekaj na dropdown
await this.iframeLocator.locator('.dropdown-item').first().click();
```

## Pole telefonu z auto-prefiksem

Pole `#phone_mobile` w MintHCM automatycznie wstawia `+48` po kliknięciu:

```javascript
async fillPhone(number) {
  await this.phoneInput.click();
  await this.page.waitForTimeout(500); // czekaj na auto-prefix
  const current = await this.phoneInput.inputValue();
  await this.phoneInput.fill(current + number);
}
```

W fixture `phone` powinien zawierać **tylko cyfry bez prefiksu** (np. `'123456789'`).

## Nawigacja — URL pattern

MintHCM używa hash-based routingu:

```javascript
// Nawigacja do EditView
await this.page.goto(`${process.env.BASE_URL}/#/modules/Candidates/EditView`);

// Czekanie na DetailView po zapisie
await this.page.waitForURL(/DetailView/, { timeout: 15000 });

// Asercja
await expect(page).toHaveURL(/DetailView/);
await expect(page).toHaveURL(/#\/modules\/Candidates\/DetailView/);
```

## Walidacja formularza

Komunikaty błędów walidacji to `div.required.validation-message` wewnątrz iframe:

```javascript
// Walidacja — formularz nie został wysłany
await expect(this.validationMessage).toBeVisible({ timeout: 8000 });
await expect(page).toHaveURL(/EditView/, { timeout: 5000 }); // nadal na EditView
```

## waitFor — po nawigacji do formularza

Po `goto()` zawsze czekaj na widoczność kluczowego elementu w iframe:

```javascript
async goto() {
  await this.page.goto(`${process.env.BASE_URL}/#/modules/Candidates/EditView`);
  await this.iframeLocator
    .locator('#last_name')
    .waitFor({ state: 'visible', timeout: 15000 });
}
```

Timeout 15000ms — formularze w iframe ładują się wolniej niż zwykłe strony.
