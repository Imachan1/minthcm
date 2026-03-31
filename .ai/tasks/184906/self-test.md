# Self Test: #184906 — Widełki płacowe - błędy pola Name i Waluta

**Data:** 2026-03-19
**Branch:** feature/184906

---

## Happy path

### BUG1 + BUG2 — Nowy rekord SalaryRange

1. Otwórz moduł **Salary Ranges** → kliknij **Utwórz** (lub przejdź do `/index.php?module=SalaryRanges&action=index`)
   - Sprawdź: pole **Waluta** wyświetla się jako lista rozwijana (dropdown z walutami), **nie** pokazuje wartości `-99`

2. W formularzu wypełnij:
   - Pole **Pozycja** — wybierz dowolną pozycję z relate-picka
   - Pole **Data od** — np. `2026-01-01`
   - Pole **Data do** — np. `2026-12-31`
   - Kliknij **Zapisz**
   - Sprawdź: rekord ma nazwę w formacie `<nazwa pozycji> - 2026-01-01 - 2026-12-31` (nie `—` ani `--- - -`)
   - Sprawdź: po zapisie następuje przekierowanie do widoku szczegółowego rekordu (nie wraca do listy ani nie zostaje na formularzu)

3. W widoku szczegółowym rekordu:
   - Sprawdź: pole **Nazwa pozycji** (`position_name`) jest widoczne w sekcji Basic i wyświetla prawidłową nazwę pozycji

### BUG2 — Edycja istniejącego rekordu

4. Otwórz dowolny istniejący rekord SalaryRange → kliknij **Edytuj**
   - Sprawdź: pole **Waluta** wyświetla dropdown — jeśli wcześniej wartość była `-99`, teraz powinna wyświetlić pustą/domyślną opcję lub `-`
   - Zmień walutę na dowolną (np. PLN/EUR) → **Zapisz**
   - Sprawdź: waluta zapisuje się poprawnie i jest widoczna po powrocie do DetailView

---

## Edge cases

5. **Brak wyboru pozycji** — utwórz rekord SalaryRange bez wybrania pozycji
   - Sprawdź: rekord zapisuje się (nazwa może być pusta lub w formacie ` - <data> - <data>`)
   - Sprawdź: nie ma błędu 500, przekierowanie po zapisie działa

6. **Brak dat** — wypełnij tylko pozycję, pozostaw `start_date` i `end_date` puste
   - Sprawdź: rekord zapisuje się bez błędu
   - Sprawdź: nazwa rekordu to `<nazwa pozycji> - - ` (lub podobny graceful fallback, nie PHP fatal)

7. **Edycja nazwy pozycji** — jeśli powiązana Pozycja zmieni swoją nazwę:
   - Edytuj rekord SalaryRange (bez zmiany pozycji) → Zapisz
   - Sprawdź: nazwa rekordu SalaryRange odświeża się do aktualnej nazwy pozycji

---

## Regresja

### BUG3 — `save()` return w innych modułach

8. **Recruitments** — utwórz lub edytuj rekord w module **Rekrutacje**
   - Wykonaj: wypełnij formularz → Zapisz
   - Sprawdź: po zapisie następuje przekierowanie do DetailView (nie zostaje na EditView)

9. **Positions** — utwórz lub edytuj rekord w module **Stanowiska**
   - Sprawdź: po zapisie przekierowanie do DetailView działa poprawnie

10. **Comments** — dodaj komentarz do dowolnego rekordu (np. w module Rekrutacje, jeśli ma panel komentarzy)
    - Sprawdź: komentarz zapisuje się i jest widoczny, brak błędów JS/console

11. **KReports** — otwórz dowolny istniejący raport w **KReports** → edytuj nazwę → Zapisz
    - Sprawdź: przekierowanie po zapisie działa, raport jest widoczny

12. **PDFTemplates** — otwórz moduł **PDF Templates** → edytuj szablon → Zapisz
    - Sprawdź: przekierowanie do DetailView po zapisie działa

13. **Favorites** — oznacz dowolny rekord jako ulubiony (kliknij gwiazdkę)
    - Sprawdź: operacja działa bez błędów console

14. **Project** — utwórz lub edytuj projekt w module **Project**
    - Sprawdź: po zapisie przekierowanie do DetailView działa

---

## Chrome console snippety

```js
// Sprawdź czy pole waluta renderuje się jako dropdown (nie input z wartością -99)
(function() {
  const el = document.querySelector('[data-field-name="currency_id"] select, [data-field-name="currency_id"] .v-select');
  if (el) {
    const val = el.value || el.innerText;
    val && val.trim() === '-99'
      ? console.error('FAIL: pole waluta pokazuje wartość -99')
      : console.log('OK: pole waluta nie pokazuje -99, wartość:', val || '(brak wartości — oczekiwane dla nowego rekordu)');
  } else {
    console.warn('INFO: element currency_id nie znaleziony — sprawdź czy jesteś na formularzu SalaryRange');
  }
})();
```

```js
// Sprawdź czy pole position_name jest widoczne w DetailView
(function() {
  const el = document.querySelector('[data-field-name="position_name"]');
  el
    ? console.log('OK: pole position_name renderuje się, wartość:', el.innerText || el.value)
    : console.error('FAIL: brak pola position_name na widoku');
})();
```

```js
// Sprawdź nazwę rekordu po zapisie (format: "<pozycja> - <data> - <data>")
(function() {
  const nameEl = document.querySelector('[data-field-name="name"]');
  if (!nameEl) { console.warn('INFO: pole name nie znalezione'); return; }
  const val = nameEl.innerText || nameEl.value || '';
  const pattern = /^.+ - \d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}$/;
  pattern.test(val.trim())
    ? console.log('OK: nazwa rekordu w poprawnym formacie:', val)
    : console.error('FAIL: nazwa rekordu nie pasuje do wzorca "<pozycja> - YYYY-MM-DD - YYYY-MM-DD", aktualna wartość:', val);
})();
```

---

## Wyniki testu w przeglądarce

*(sekcja opcjonalna — wypełniana po uruchomieniu `/chrome`)*

| # | Ścieżka | Status | Uwagi |
|---|---------|--------|-------|
| 1 | Pole Waluta jako dropdown | ✅ OK | Dropdown bez wartości -99 |
| 2 | Nowy rekord — nazwa po zapisie + przekierowanie | ✅ OK | Nazwa: "test2131 - 2026-01-01 - 2026-12-31", przekierowanie do DetailView |
| 3 | Pole position_name w DetailView | ✅ OK | Widoczne jako "Position: test2131" w sekcji Basic |
| 4 | Edycja rekordu — waluta | ✅ OK | Wybrano EUR, zapisano poprawnie, widoczne w DetailView |
| 5 | Edge: brak pozycji | ✅ OK | Walidacja front-end: "This field is required", brak błędu 500 |
| 6 | Edge: brak dat | — | pominięto (niższy priorytet) |
| 7 | Edge: zmiana nazwy pozycji | — | pominięto (niższy priorytet) |
| 8 | Regresja: Recruitments | ✅ OK | EditView → DetailView po zapisie |
| 9 | Regresja: Positions | ✅ OK | EditView → DetailView po zapisie |
| 10 | Regresja: Comments | — | pominięto (brak dostępnego widoku UI) |
| 11 | Regresja: KReports | ✅ OK | EditView → DetailView po zapisie |
| 12 | Regresja: PDFTemplates | ✅ OK | EditView → DetailView po zapisie |
| 13 | Regresja: Favorites | ✅ OK | Oznaczono jako ulubiony bez błędów console |
| 14 | Regresja: Project | ✅ OK | EditView → DetailView po zapisie (nowy rekord) |

---

## Checklist

- [x] Quick Repair and Rebuild wykonany (Admin > Repair > Quick Repair and Rebuild)
- [x] Happy path przetestowany (BUG1, BUG2)
- [x] Edge cases sprawdzone (kluczowe)
- [x] Regresja OK (BUG3 — Recruitments, Positions, KReports, PDFTemplates, Favorites, Project)
- [x] Chrome snippety wykonane
