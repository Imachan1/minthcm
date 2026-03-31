# Plan: #184906 — Widełki płacowe - błędy pola Name i Waluta

**Redmine:** https://redmine.evolpe.net/issues/184906
**Sprint:** Aktualne prace
**Plan date:** 2026-03-18

## Requirements understanding

Moduł Salary Ranges ma dwa niezależne błędy:

1. Pole `position_name` (nazwa powiązanej Pozycji) nie jest prawidłowo rozwiązywane — przez to rekord po zapisie dostaje nazwę `—` zamiast `<nazwa pozycji> - <data od> - <data do>`, a przekierowanie do widoku szczegółowego nie działa poprawnie.

2. Pole waluty wyświetla wartość `-99` zamiast listy wyboru walut — dzieje się tak przez błędną konfigurację w vardefs (zły typ pola i ustawiony domyślny `-99`).

## Acceptance criteria

- Po wybraniu pozycji w formularzu i zapisie rekord ma nazwę w formacie `<nazwa pozycji> - <data od> - <data do>`
- Po zapisie użytkownik jest przekierowany do widoku szczegółowego rekordu
- Pole waluty wyświetla się jako lista rozwijana z walutami do wyboru (nie pokazuje `-99`)

## Implementation approach

### BUG1 — Niezgodność nazwy linka w `position_name`

W `legacy/modules/SalaryRanges/vardefs.php` pole `position_name` ma `'link' => 'position'` (singularis), ale zdefiniowany link nosi nazwę `'positions'` (pluralis). To sprawia, że SugarCRM nie jest w stanie rozwiązać relacji przy zapisie.

**Fix:** zmienić `'link' => 'position'` na `'link' => 'positions'`

Wzorzec z działającego modułu `Appraisals`:
- `legacy/modules/Appraisals/vardefs.php` — identyczna konfiguracja z `'link' => 'positions'`

### BUG2 — Błędna konfiguracja `currency_id`

Obecna konfiguracja pola `currency_id`:
- `'type' => 'currency_id'` — nieprawidłowy typ (powinno być `'id'`)
- `'default' => '-99'` — powoduje wyświetlanie wartości `-99`

**Fix:** zmienić typ na `'id'`, usunąć `'default' => '-99'`.

Wzorzec z modułu `Recruitments`:
- `legacy/modules/Recruitments/vardefs.php` — `currency_id` z typem `'id'` i bez problematycznego domyślnego

Po zmianie vardefs wymagany jest Quick Repair and Rebuild.

## Files to modify / create

| File | Action | Description of change |
|------|--------|-----------------------|
| `legacy/modules/SalaryRanges/vardefs.php` | MODIFY | BUG1: fix `'link' => 'position'` → `'link' => 'positions'`; BUG2: zmienić `'type' => 'currency_id'` → `'type' => 'id'`, usunąć `'default' => '-99'` w `currency_id` |
| `legacy/modules/SalaryRanges/metadata/recordviewdefs.php` | MODIFY | Dodać pole `position_name` do layoutu widoku rekordu |
| `legacy/modules/SalaryRanges/SalaryRanges.php` | MODIFY | BUG3: uproszczono generowanie nazwy (usunięto zbędną konwersję przez `$timedate`), `return parent::save($check_notify)` |
| `legacy/modules/Positions/Positions.php` | MODIFY | BUG3: `$result = parent::save()` + `return $result` po `pushFeed()` |
| `legacy/modules/Recruitments/Recruitments.php` | MODIFY | BUG3: `$result = parent::save()` + `return $result` po post-save logic |
| `legacy/modules/Comments/Comments.php` | MODIFY | BUG3: `return parent::save()` |
| `legacy/modules/KReports/KReport.php` | MODIFY | BUG3: `return parent::save()` |
| `legacy/modules/PDFTemplates/PDFTemplates.php` | MODIFY | BUG3: `$result = parent::save()` + `return $result` po rebuild |
| `legacy/modules/Favorites/Favorites.php` | MODIFY | BUG3: `$result = parent::save()` + `return $result` po ES hooks |
| `legacy/modules/Project/Project.php` | MODIFY | BUG3: `$return_id = parent::save()` (scalone dwie linie) |
| `legacy/modules/AM_ProjectTemplates/AM_ProjectTemplates.php` | MODIFY | BUG3: `$return_id = parent::save($check_notify)` (usunięto hardcoded `true`) |
| `legacy/modules/AOP_Case_Updates/AOP_Case_Updates.php` | MODIFY | BUG3: `$result = parent::save()` + `return $result` zamiast `$this->id` |
| `legacy/modules/OAuth2Clients/OAuth2Clients.php` | MODIFY | BUG3: dodano brakujący argument `$check_notify` |
| `legacy/modules/OAuthTokens/OAuthToken.php` | MODIFY | BUG3: dodano brakujący argument `$check_notify` |

## Implementation plan

- [x] **[CHECKPOINT]** Weryfikacja przed zmianami: odtworzyć oba błędy lokalnie (utworzyć rekord SalaryRange, sprawdzić nazwę i wartość waluty)
  - Uwaga implementacyjna: w środowisku CLI bez uruchomionej instancji aplikacji wykonano weryfikację kodową zamiast pełnego odtworzenia UI; błędy potwierdzono przez inspekcję `legacy/modules/SalaryRanges/vardefs.php` i porównanie z działającymi wzorcami w `Appraisals` oraz `Recruitments`.
- [x] Poprawka BUG1 w `legacy/modules/SalaryRanges/vardefs.php`:
  - zmienić `'link' => 'position'` na `'link' => 'positions'` w definicji pola `position_name`
- [x] Poprawka BUG2 w `legacy/modules/SalaryRanges/vardefs.php`:
  - zmienić `'type' => 'currency_id'` na `'type' => 'id'` w polu `currency_id`
  - usunąć linię `'default' => '-99'`
- [x] Dodać `position_name` do `legacy/modules/SalaryRanges/metadata/recordviewdefs.php`
- [x] Poprawka `SalaryRanges::save()` — uproszczenie generowania nazwy + `return parent::save($check_notify)`
- [x] Poprawka `Positions::save()` — `$result = parent::save()` + `return $result`
- [x] BUG3 — poprawka `save()` return w 10 kolejnych modułach (patrz sekcja BUG3 poniżej)
- [~] Quick Repair and Rebuild (Admin > Repair > Quick Repair and Rebuild)
- [ ] **[CHECKPOINT]** Weryfikacja po zmianach:
  - Utwórz nowy rekord SalaryRange, wybierz pozycję i daty — po zapisie nazwa powinna być `<pozycja> - <data od> - <data do>`
  - Sprawdź, że po zapisie następuje przekierowanie do widoku szczegółowego
  - Sprawdź, że pole waluty wyświetla listę wyboru (nie `-99`)

## BUG3 — `save()` nie zwraca ID z `parent::save()` (dodatkowa poprawka)

Odkryto, że w wielu modułach `save()` nadpisuje metodę bez zwracania wyniku `parent::save()`. Powoduje to, że Doctrine `legacySave` dostaje `null` zamiast ID, a Vue nie przekierowuje do DetailView po zapisie. Positions i SalaryRanges były już naprawione w commit `923e963be4`.

Naprawiono 10 dodatkowych modułów:

| # | Plik | Zastosowana poprawka |
|---|------|---------------------|
| 1 | `legacy/modules/Recruitments/Recruitments.php:82` | `$result = parent::save()` + `return $result` po post-save logic |
| 2 | `legacy/modules/Comments/Comments.php:41` | `return parent::save()` |
| 3 | `legacy/modules/KReports/KReport.php:466` | `return parent::save()` |
| 4 | `legacy/modules/PDFTemplates/PDFTemplates.php:56` | `$result = parent::save()` + `return $result` po rebuild |
| 5 | `legacy/modules/Favorites/Favorites.php:246` | `$result = parent::save()` + `return $result` po ES hooks |
| 6 | `legacy/modules/Project/Project.php:348` | `$return_id = parent::save()` (scalone dwie linie) |
| 7 | `legacy/modules/AM_ProjectTemplates/AM_ProjectTemplates.php:66` | `$return_id = parent::save($check_notify)` (usunięto hardcoded `true`) |
| 8 | `legacy/modules/AOP_Case_Updates/AOP_Case_Updates.php:115,128` | `$result = parent::save()` + `return $result` zamiast `$this->id` |
| 9 | `legacy/modules/OAuth2Clients/OAuth2Clients.php:112` | dodano brakujący argument `$check_notify` |
| 10 | `legacy/modules/OAuthTokens/OAuthToken.php:149` | dodano brakujący argument `$check_notify` |

## Risks and notes

- Zmiana `'link'` w istniejącym polu relate może wpłynąć na rekordy już zapisane w bazie z pustą `position_name` — ich nazwy nie zostaną automatycznie naprawione (tylko nowe i edytowane rekordy dostaną poprawną nazwę).
- Quick Repair jest wymagany po zmianie vardefs — bez niego encja Doctrine i cache metadanych nie zostaną odświeżone.
