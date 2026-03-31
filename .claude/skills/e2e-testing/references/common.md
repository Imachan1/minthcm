# Struktura `tests/` i konwencje ogólne

## Lokalizacja testów w repo projektu

Testy E2E żyją w katalogu `tests/` w głównym repo projektu — **nie** w osobnym repozytorium.

```
projekt-klienta/                        # repo projektu (np. fork MintHCM)
├── .claude/                            # skille, manifest, CLAUDE.md
│   ├── CLAUDE.md
│   ├── skills-manifest.yaml
│   └── skills/
│       ├── e2e-testing/                # ← ten skill (common)
│       ├── minthcm-e2e/               # ← skill systemowy E2E
│       ├── minthcm/                   # ← skill wdrożeniowy
│       └── ...
├── legacy/                             # kod systemu (MintHCM)
├── api/
├── vue/
│
└── tests/                              # ← TESTY E2E
    ├── playwright.config.js
    ├── package.json
    ├── .env                            # NIE commituj!
    ├── .env.example
    │
    ├── shared/                         # wspólne dla wszystkich modułów
    │   ├── pages/
    │   │   └── LoginPage.js
    │   └── helpers/
    │       ├── api/                    # wywołania REST API (setup/teardown)
    │       │   └── candidatesApi.js
    │       ├── fixtures/               # dane testowe z faker (jeden plik per moduł)
    │       │   └── candidateFixtures.js
    │       └── utils/                  # funkcje pomocnicze
    │           └── dateUtils.js
    │
    ├── modules/                        # testy per moduł CRM
    │   ├── candidates/
    │   │   ├── pages/
    │   │   │   └── CreateCandidatePage.js
    │   │   └── tests/
    │   │       ├── create-candidate.spec.js
    │   │       └── add-phone-field.spec.js
    │   ├── contracts/
    │   │   ├── pages/
    │   │   │   └── CreateContractPage.js
    │   │   └── tests/
    │   │       └── create-contract.spec.js
    │   └── employees/
    │       ├── pages/
    │       │   └── EmployeeListPage.js
    │       └── tests/
    │           └── employee-list.spec.js
    │
    └── workflows/                      # testy cross-modułowe
        └── tests/
            ├── candidate-to-employee.spec.js
            └── recruitment-pipeline.spec.js
```

## Co trafia gdzie

| Typ testu | Lokalizacja | Przykład |
|:----------|:------------|:--------|
| Test jednego modułu | `modules/<moduł>/tests/` | Tworzenie kandydata |
| Test jednego pola/funkcji | `modules/<moduł>/tests/` | Dodanie pola telefonu |
| Mechanizm na kilku modułach | `workflows/tests/` | Kandydat → umowa → pracownik |
| Login, nawigacja | `shared/pages/` | LoginPage.js |
| Wywołania API | `shared/helpers/api/` | candidatesApi.js |
| Dane testowe / fabryki | `shared/helpers/fixtures/` | candidateFixtures.js |
| Funkcje pomocnicze | `shared/helpers/utils/` | dateUtils.js |

## Ścieżki importów

```javascript
// Z modules/candidates/tests/create-candidate.spec.js:
const { LoginPage } = require('../../shared/pages/LoginPage');
const { CreateCandidatePage } = require('../pages/CreateCandidatePage');
const { generateCandidate } = require('../../shared/helpers/fixtures/candidateFixtures');
const { formatDate } = require('../../shared/helpers/utils/dateUtils');

// Z workflows/tests/candidate-to-employee.spec.js:
const { LoginPage } = require('../../shared/pages/LoginPage');
const { CreateCandidatePage } = require('../../modules/candidates/pages/CreateCandidatePage');
const { EmployeeListPage } = require('../../modules/employees/pages/EmployeeListPage');
const { createCandidateViaApi } = require('../../shared/helpers/api/candidatesApi');
```

## Konwencje nazewnictwa

### Pliki

- Page Objects: `NazwaStronyPage.js` (PascalCase + sufiks `Page`)
- Pliki testowe: `nazwa-funkcji.spec.js` (kebab-case)
- Fixtures: `modulFixtures.js` (camelCase)
- API helpers: `modulApi.js` (camelCase)
- Utils: `nazwaUtils.js` (camelCase)

### `const` vs `let`

Preferuj `const` — używaj dla wszystkich danych, które nie zmieniają się po przypisaniu:

```javascript
// ✅ const — wartość nie zmienia się
const candidatePage = new CreateCandidatePage(page);
const data = generateCandidate();
const expectedUrl = /DetailView/;

// ✅ let — wartość jest dynamiczna lub nadpisywana
let timestamp = Date.now();
let uniqueEmail = `test.${faker.string.numeric(4)}@example.com`;
```

Zasada: `let` tylko gdy wartość zawiera `Date.now()`, losowe dane generowane w miejscu, lub jest nadpisywana w dalszej części testu.

### Nazewnictwo `test.describe` i `test`

Ciągi w `test.describe()` i `test()` powinny być krótkie — maksymalnie **3–5 słów**, po polsku, z numerem TC:

```javascript
// ✅ Dobrze
test.describe('Tworzenie kandydata', () => {
  test('TC-01: Poprawne dane', async ({ page }) => { ... });
  test('TC-02: Brak nazwiska', async ({ page }) => { ... });
  test('TC-03: Duplikat emaila', async ({ page }) => { ... });
});

// ❌ Źle — za długie
test.describe('Testowanie formularza tworzenia nowego kandydata w module Candidates', () => {
  test('TC-01: Poprawne utworzenie kandydata z wszystkimi wymaganymi polami', async ({ page }) => { ... });
});
```

## Zmienne środowiskowe

Plik `.env` w `tests/` (nie commituj, dodaj do `.gitignore`):

```env
# Projekt
CONTRAIN_BASE_URL=https://adres-aplikacji.pl
CONTRAIN_ADMIN_LOGIN=nazwa_uzytkownika
CONTRAIN_ADMIN_PASSWORD=haslo
```

Konwencja: `NAZWAPROJEKTU_ROLA_POLE` (np. `CONTRAIN_ADMIN_LOGIN`).

Plik `.env.example` commituj — z pustymi wartościami jako dokumentacja wymaganych zmiennych.

## Git workflow

```bash
git add tests/modules/candidates/pages/CreateCandidatePage.js
git add tests/modules/candidates/tests/create-candidate.spec.js
git commit -m "test: testy tworzenia kandydata"
```

Konwencja commitów dla testów: `test: opis` (nie `feat:` ani `fix:`).
