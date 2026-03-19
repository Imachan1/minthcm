# Code Review: #185246 — Dodanie poprawek ze SuiteCRM

**Data:** 2026-03-16
**Branch:** feature/185246
**Zakres:** 7 commit(ów): cbee203a87~1..aae1cc25d9
**Re-review:** a90159a081 (poprawki Dominika)
**Reviewer:** AI (Claude Code)
**Redmine:** https://redmine.evolpe.net/issues/185246

## Kontekst zagadnienia

Backport poprawek z SuiteCRM v7.14.4 → v7.14.8 do warstwy legacy MintHCM.
Zakres wersji:
- v7.14.3→v7.14.4: weryfikacja rozszerzeń plików, emaile przychodzące
- v7.14.4→v7.14.5: API V8, whitespace trimming, email, kalendarz (część pominięta)
- v7.14.5→v7.14.6: (pliki SCSS pominięte)
- v7.14.6→v7.14.7: mailer, oAuth2 Microsoft, Smarty, entryPoint, API, ankiety, instalacja (część pominięta)
- v7.14.7→v7.14.8: (search pominięty)
- Dodatkowo: naprawa vue/@vitejs/plugin-vue po #137578

## Zmienione pliki

203 pliki zmienione, 26154 insertions(+), 20655 deletions(-)

Kluczowe obszary: legacy/data/SugarBean.php, legacy/include/utils.php, legacy/include/utils/file_utils.php,
legacy/modules/InboundEmail/, legacy/modules/OutboundEmailAccounts/, legacy/modules/ExternalOAuthProvider/,
legacy/modules/ExternalOAuthConnection/, legacy/include/SugarPHPMailer.php, legacy/install/,
legacy/Api/V8/, vue/package-lock.json

## Znalezione problemy

### CRITICAL

_(brak)_

### WARNING

- [ ] `legacy/include/HtmlSanitizer.php:130` — **[Bezpieczeństwo] Regex case-sensitive — strip_tags może nie zostać wywołane** — `preg_match('/([a-z]+).../')` bez flagi `i` nie pasuje do tagów HTML z wielkimi literami (`<SCRIPT>`, `<IMG SRC=x>`). Gdy regex nie pasuje, `strip_tags()` w ogóle się nie wywołuje i niesanityzowane HTML trafia dalej. Stara implementacja z `FILTER_SANITIZE_STRIPPED` działała bezwarunkowo. → Dodać flagę `i` do regex lub usunąć warunek `if` i zawsze wywoływać `strip_tags()` [confidence: 92]

- [ ] `legacy/modules/OutboundEmailAccounts/OutboundEmailAccounts.php:452` — **[Bezpieczeństwo] XSS: $_REQUEST['record'] wstrzykiwane do bloku JS bez sanityzacji** — `$record = $_REQUEST['record'] ?? ''` jest interpolowane bezpośrednio do heredoc `<script>` jako `"record=" + '$record' + "&"`. Atakujący może wstrzyknąć `'; alert(document.cookie); var x='` — klasyczny reflected XSS w panelu administracyjnym. → Sanityzować przez `htmlspecialchars($record, ENT_QUOTES, 'UTF-8')` lub walidować format UUID przez `SuiteValidator::isValidId()` [confidence: 95]

- [ ] `legacy/modules/ExternalOAuthConnection/services/OAuthAuthorizationService.php:316` — **[Bezpieczeństwo] XSS: $oauthConnectionName (z DB) nieescapowane w HTML** — `$oauthConnectionName = $oauthConnection->name` (dane wprowadzone przez użytkownika) osadzane bezpośrednio w `<a href="...">$oauthConnectionName</a>` bez `htmlspecialchars()`. Nazwa OAuth connection zawierająca `<script>alert(1)</script>` zostanie wykonana przez przeglądarkę. → Escapować: `htmlspecialchars($oauthConnectionName, ENT_QUOTES, 'UTF-8')` przed użyciem w HTML [confidence: 88]

- [ ] `legacy/modules/UserPreferences/UserPreference.php:289` — **[Logika] Fatal error przy null $GLOBALS['current_user'] w reloadPreferences()** — `!$GLOBALS['current_user']->id` dostępuje `->id` bez sprawdzenia czy obiekt istnieje. Jeśli `$GLOBALS['current_user']` jest null (CLI, cron, instalacja), kod rzuci `Fatal error: Call to a member function id() on null`. Wzorzec powtarza się w liniach 289, 301, 307. → Dodać guard: `if (empty($GLOBALS['current_user']) || ...)` jako pierwszy warunek [confidence: 85]

- [ ] `legacy/modules/OutboundEmailAccounts/js/fields.js:271` — **[Jakość] Runtime error: this.formName i this.name nie istnieją w kontekście** — `configureValidation(this.formName, this.name, required)` — metoda `setRequired` ma parametry `(field, fieldType, formName, required)`, ale używa `this.formName` i `this.name` które nigdy nie są inicjalizowane na obiekcie. Powinno być `configureValidation(formName, field, required)`. Walidacja pól SMTP nigdy się nie skonfiguruje — walidatory nie zostaną zarejestrowane. → Poprawić na `configureValidation(formName, field, required)` [confidence: 95]

- [ ] `legacy/modules/OutboundEmailAccounts/js/auth_type_fields_toggle.js:49` — **[Jakość] TypeError: fieldsPerType.personal nie istnieje — fallback zwróci undefined** — `fieldsPerType[type] || fieldsPerType.personal` — klucz `personal` nie jest zdefiniowany w `fieldsPerType` (są: `basic`, `no_auth`, `oauth`). Gdy `type` ma niespodziewaną wartość, fallback zwraca `undefined`, a następna linia `Object.keys(fieldDisplay)` rzuci `TypeError: Cannot convert undefined or null to object`. → Zmienić fallback na `fieldsPerType['no_auth']` lub dodać wpis `personal` do obiektu [confidence: 85]

- [ ] `legacy/modules/AOW_Actions/actions/actionCreateRecord.php:319` — **[Jakość] Martwy kod: wynik wyrażenia ternarnego nie jest używany** — `$date !== null ? $date->asDB() : gmdate($dformat);` — wyrażenie jest obliczane, ale wynik nie jest przypisany do żadnej zmiennej. Kolejne linie (322–325) używają `$date` w oryginalnej formie (nieprzetworzonej), więc konwersja do formatu DB może być potrzebna. → Przypisać wynik: `$date = $date !== null ? $date->asDB() : gmdate($dformat);` [confidence: 88]

### INFO

- `legacy/include/MassUpdate.php` — W `strpos($newbean->$dynamic_field_name, $parentenum_value) !== 0`: gdy `$parentenum_value` jest pustym stringiem, `strpos()` zawsze zwróci `0`, warunek `!== 0` będzie `false` i wartość pola nie zostanie zresetowana. Edge case przy dynamicenum z pustą wartością nadrzędną.

- `legacy/lib/Search/UI/SearchResultsController.php:152` — `$bean[0]->module_name` bez sprawdzenia `!empty($bean)` przed dostępem. Jeśli `$hitsAsBeans` zawiera moduł z pustą tablicą wyników — Notice: Undefined offset 0.

- `legacy/include/SearchForm/SearchForm2.php` — Usunięcie `$timedate->to_db_date()` przed `getDayStartEndGMT()` w bloku datetime. Przy operatorach `greater_than`/`less_than` wartość może być już po `$db->quote()`, co może być niekompatybilne z oczekiwaniami `getDayStartEndGMT()`.

- `legacy/modules/UserPreferences/UserPreference.php` — W `reloadPreferences()`: `unserialize(base64_decode($row['contents']))` jest wywoływane dwukrotnie na tej samej wartości (dla `$_SESSION` i `$user->user_preferences`). Można przypisać do zmiennej pośredniej.

- `legacy/include/OutboundEmail/OutboundEmail.php:407` — Nowa metoda `getSystemEmail()` używa jednoliiterowych nazw zmiennych `$q`, `$r`, `$a`. Słaba czytelność — zalecane: `$query`, `$result`, `$row`.

- `legacy/modules/SugarFeed/SugarFeed.php` — INFO: dodanie `elseif(!file_exists(...))` guard dla brakującego core handlera jest OK.

## Podsumowanie

Backport jest obszerny (203 pliki, ~47k linii) i w dużej mierze poprawny — zawiera istotne poprawki bezpieczeństwa (unserialize z `allowed_classes => false`, oAuth2 Microsoft, walidacja plików). Znaleziono **3 problemy bezpieczeństwa** (XSS w JS i HTML, regex case-sensitive w sanitizerze) oraz **2 błędy runtime** w nowych plikach JS (OutboundEmailAccounts). Wszystkie 7 WARNING zostały naprawione w commicie a90159a081.

## Re-review: a90159a081

Wszystkie 7 findingów WARNING naprawione poprawnie:
- `HtmlSanitizer.php` — `strip_tags()` bezwarunkowo ✅
- `OAuthAuthorizationService.php` — `htmlspecialchars()` ✅
- `OutboundEmailAccounts.php` — `SuiteValidator::isValidId()` ✅
- `UserPreference.php` — `empty($GLOBALS['current_user'])` guard (3 miejsca) ✅
- `fields.js` — `configureValidation(formName, field, required)` ✅
- `auth_type_fields_toggle.js` — fallback na `fieldsPerType['no_auth']` ✅
- `actionCreateRecord.php` — `$date =` przed ternary ✅

**Werdykt:** APPROVED

## Odrzucone (confidence < 80)

| Finding | Agent | Confidence | Powód odrzucenia |
|---|---|---|---|
| SearchResults.php:177 — odwrócona kolejność isset() | Agent 2 — Błędy logiczne | 35 | isset() nie generuje Notice, logika semantycznie działa poprawnie |
| UserPreference.php:109 — null dereference w getPreference() | Agent 2 — Błędy logiczne | 75 | Wymaga kontekstu — current_user zazwyczaj zalogowany w tym flow |
| Calendar.php — Tasks start=date_due | Agent 2 — Błędy logiczne | 55 | Prawdopodobnie celowy backport z SuiteCRM — wymaga weryfikacji z SuiteCRM |
| InboundEmail.php w diffie (528 linii) | Agent 1 — Poprawność | ~20 | False positive — zmiany z v7.14.6+, nie z pominięcia v7.14.4→v7.14.5 |
| Users/User.php — getCurrentPreference | Agent 1 — Poprawność | 75 | Wymaga weryfikacji — możliwe zmiany z wersji innej niż pominięta |
| EmailMan/tpls/config.tpl w diffie | Agent 1 — Poprawność | 72 | Poniżej progu — zmiany formatowania, wymaga kontekstu właściciela |
| Calendar/Menu.php w diffie | Agent 1 — Poprawność | 60 | Zmiana strukturalna + copyright, origin niejasny ale mało ryzykowny |
| BeanManager.php — dwie instancje beana | Agent 4 — Wydajność | 72 | Poniżej progu — minimalny wpływ w stosunku do kosztu SQL query |
| UserPreference.php — brak cache getPreference() | Agent 4 — Wydajność | 55 | Możliwy celowy design dla separacji danych sesji |
| SearchForm2.php — get_user_array(false) | Agent 4 — Wydajność | 28 | Celowe wyłączenie cache (jawnie `false`) — świeże dane w formularzu |
| Notes/controller.php — przesunięta indentacja | Agent 5 — Jakość kodu | 75 | Syntaktycznie poprawne PHP, nie wpływa na działanie |
| SugarEmailAddress.php — SQL bez cudzysłowów | Agent 5 — Jakość kodu | 15 | False positive — db->quoted() zawiera cudzysłowy, zmiana poprawia duplikację |
