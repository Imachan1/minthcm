# Konfiguracja Playwright

## Instalacja zależności

```bash
cd tests/
npm init -y
npm install --save-dev @playwright/test @faker-js/faker dotenv
npx playwright install chromium
```

## playwright.config.js

```javascript
const { defineConfig, devices } = require('@playwright/test');
require('dotenv').config();

module.exports = defineConfig({
  testDir: '.',
  testMatch: [
    'modules/**/tests/*.spec.js',
    'workflows/tests/*.spec.js',
  ],
  fullyParallel: false,
  retries: 1,
  reporter: 'html',
  use: {
    baseURL: process.env.CONTRAIN_BASE_URL,  // zmień prefix na nazwę projektu
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    ...devices['Desktop Chrome'],
  },
});
```

Kluczowe:
- `testDir: '.'` — Playwright szuka testów od korzenia `tests/`
- `testMatch` — skanuje tylko `modules/**/tests/` i `workflows/tests/`
- `fullyParallel: false` — testy CRM zazwyczaj wymagają sekwencyjnego wykonania (sesja, stan)
- `retries: 1` — jeden retry przy pierwszym niepowodzeniu
- `trace: 'on-first-retry'` — trace tylko przy retry (oszczędza zasoby)

## .gitignore (w tests/)

```
node_modules/
.env
test-results/
playwright-report/
blob-report/
```

## Uruchamianie testów

```bash
cd tests/

# Wszystkie testy
npx playwright test

# Jeden moduł
npx playwright test modules/candidates/

# Jeden plik
npx playwright test modules/candidates/tests/create-candidate.spec.js

# Tylko workflows
npx playwright test workflows/

# Z przeglądarką (headed) — do debugowania
npx playwright test --headed

# Jeden test po nazwie
npx playwright test -g "TC-01"

# Raport HTML
npx playwright show-report
```

## Debugowanie

```bash
# UI mode — interaktywny debugger
npx playwright test --ui

# Debug mode — otwiera przeglądarkę z inspektorem
npx playwright test --debug

# Headed + slowmo — widoczna przeglądarka, spowolniona
npx playwright test --headed -- --slow-mo=500
```

## Timeouty

Domyślne timeouty dla testów CRM (formularze ładują się wolniej):

```javascript
// W playwright.config.js
use: {
  actionTimeout: 10000,    // timeout per akcję (klik, fill)
  navigationTimeout: 30000, // timeout na nawigację
},
timeout: 60000,             // timeout per test
```

W testach — explicit timeouty w asercjach:

```javascript
await expect(page).toHaveURL(/DetailView/, { timeout: 15000 });
await expect(element).toBeVisible({ timeout: 8000 });
```
