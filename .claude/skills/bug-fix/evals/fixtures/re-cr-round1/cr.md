# Code Review — #888001 Przykładowe zagadnienie do re-CR

**Data:** 2026-03-01
**Autor kodu:** Jan Kowalski (`jan.kowalski`)
**Reviewer:** AI (GitHub Copilot / Claude Sonnet)
**Branch:** `feature/888001`
**Zakres diff:** `abc123~1..def456`

---

## Werdykt: CHANGES REQUESTED

---

## Findings

### CRITICAL

#### CR-1 | `api/utils/ExampleService.php` | `process()` | Brak walidacji wejścia

Metoda `process()` nie waliduje że parametr `$count` jest liczbą dodatnią.
Dla `$count = -1` metoda wykona pętlę w nieskończoność.

**Naprawa:** Dodaj walidację na początku metody:
```php
if ($count <= 0) {
    throw new InvalidArgumentException("count must be positive, got: $count");
}
```

**Confidence:** 95

---

### WARNING

#### CR-3 | `api/utils/ExampleService.php` | `loadData()` | Zbędne zapytanie DB w pętli

Metoda `loadData()` wykonuje `SELECT * FROM items WHERE parent_id = :id` wewnątrz pętli `foreach`.
Dla 100 elementów = 100 zapytań.

**Naprawa:** Pobierz dane jednym zapytaniem przed pętlą, używając `WHERE parent_id IN (...)`.

**Confidence:** 88

---

### INFO

#### CR-5 | `api/utils/ExampleService.php` | Niekonsekwentne nazewnictwo zmiennych

Zmienna `$result_data` używa snake_case zamiast camelCase.

---

## Checklist

- [x] CR-2 WARNING: Brak logowania błędów w catch block ✅
- [ ] **CR-1** CRITICAL: Brak walidacji parametru $count w process()
- [ ] **CR-3** WARNING: N+1 zapytań w loadData()

---

## Re-CR #1: 2026-03-05 14:30

### Weryfikacja poprzedniego CR

| # | Finding | Status | Komentarz |
|---|---------|--------|-----------|
| CR-1 | `api/utils/ExampleService.php:process()` — Brak walidacji | ❌ UNFIXED | Walidacja dodana częściowo, brak obsługi case $count = 0 |
| CR-3 | `api/utils/ExampleService.php:loadData()` — N+1 queries | ✅ FIXED | Zmieniono na batch query z IN (...) |

Podsumowanie: 1/2 poprawione.

### Nowe problemy

#### CRITICAL

- [ ] **CR-1b** CRITICAL: `api/utils/ExampleService.php:process()` — Walidacja pomija $count = 0 (zero też powinno być nieprawidłowe)

#### INFO

- `api/utils/ExampleService.php:25` — Duplikat sprawdzenia null zaraz po poprawce CR-1

### Podsumowanie Re-CR

Poprawki są częściowe. CR-1 wymaga uzupełnienia o walidację zera.

**Werdykt:** CHANGES REQUESTED
