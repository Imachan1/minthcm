# Helpers — fixtures, API, utils

Folder `tests/shared/helpers/` jest podzielony na trzy kategorie.

## fixtures/ — dane testowe z faker

Jeden plik per moduł CRM. Używamy `@faker-js/faker` z polską lokalizacją.

```javascript
// tests/shared/helpers/fixtures/candidateFixtures.js
const { faker } = require('@faker-js/faker/locale/pl');

function generateCandidate(overrides = {}) {
  return {
    lastName: faker.person.lastName(),
    firstName: faker.person.firstName(),
    email: `test.${faker.string.numeric(4)}@example.com`,
    phone: faker.string.numeric(9),
    ...overrides
  };
}

module.exports = { generateCandidate };
```

### Zasady fixtures

- Jeden plik per moduł: `candidateFixtures.js`, `contractFixtures.js`, `employeeFixtures.js`
- Funkcja generująca: `generateNazwaModulu(overrides = {})`
- Zawsze `...overrides` na końcu — pozwala nadpisywać pola w testach
- Email z losowym segmentem: `test.${faker.string.numeric(4)}@example.com`
- Polska lokalizacja: `require('@faker-js/faker/locale/pl')`

### Przykład z wieloma polami

```javascript
function generateContract(overrides = {}) {
  return {
    name: `Umowa-${faker.string.numeric(4)}`,
    startDate: faker.date.future().toLocaleDateString('pl-PL'),
    endDate: faker.date.future({ years: 2 }).toLocaleDateString('pl-PL'),
    type: faker.helpers.arrayElement(['Umowa o pracę', 'B2B', 'Zlecenie']),
    value: faker.number.int({ min: 3000, max: 15000 }),
    ...overrides
  };
}
```

## api/ — setup/teardown danych przez REST

Funkcje do tworzenia i usuwania rekordów przez API. Używane w `beforeEach`/`afterEach` żeby nie polegać na UI.

```javascript
// tests/shared/helpers/api/candidatesApi.js
const BASE_URL = process.env.CONTRAIN_BASE_URL;

async function createCandidateViaApi(request, data) {
  const response = await request.post(`${BASE_URL}/api/v8/modules/Candidates`, {
    data: { attributes: data },
  });
  return response.json();
}

async function deleteCandidateViaApi(request, id) {
  await request.delete(`${BASE_URL}/api/v8/modules/Candidates/${id}`);
}

module.exports = { createCandidateViaApi, deleteCandidateViaApi };
```

### Zasady API helpers

- Jeden plik per moduł: `candidatesApi.js`, `contractsApi.js`
- Funkcje `create*ViaApi` i `delete*ViaApi`
- `request` pochodzi z Playwright fixtures: `test('...', async ({ request }) => { ... })`
- URL bazowy z `.env`

### Kiedy API zamiast UI

| Scenariusz | API czy UI? |
|-----------|-------------|
| Przygotowanie danych przed testem (beforeEach) | API |
| Sprzątanie po teście (afterEach) | API |
| Test samego formularza tworzenia | UI |
| Test listy — potrzebne rekordy w bazie | API do setup, UI do testu |

## utils/ — funkcje pomocnicze

Drobne narzędzia wielokrotnego użytku.

```javascript
// tests/shared/helpers/utils/dateUtils.js
function todayFormatted() {
  const now = new Date();
  const dd = String(now.getDate()).padStart(2, '0');
  const mm = String(now.getMonth() + 1).padStart(2, '0');
  const yyyy = now.getFullYear();
  return `${dd}.${mm}.${yyyy}`;
}

function futureDate(daysFromNow) {
  const date = new Date();
  date.setDate(date.getDate() + daysFromNow);
  const dd = String(date.getDate()).padStart(2, '0');
  const mm = String(date.getMonth() + 1).padStart(2, '0');
  const yyyy = date.getFullYear();
  return `${dd}.${mm}.${yyyy}`;
}

module.exports = { todayFormatted, futureDate };
```

### Typowe utils

- `dateUtils.js` — formatowanie dat w formacie CRM
- `waitUtils.js` — custom wait conditions (jeśli potrzebne)
- `urlUtils.js` — parsowanie URL, wyciąganie ID rekordu z URL
