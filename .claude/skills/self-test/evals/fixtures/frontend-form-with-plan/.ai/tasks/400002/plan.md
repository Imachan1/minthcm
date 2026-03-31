# Plan: #400002 — Dodaj pole Telefon do formularza edycji kontaktu

## Opis

Użytkownicy zgłaszają brak pola telefonu w formularzu edycji kontaktu. Pole istnieje w bazie (`phone_work`), ale nie jest wyświetlane w EditView. Dodać pole z walidacją formatu.

## Kryteria akceptacji

1. Pole „Telefon" pojawia się w formularzu edycji kontaktu (EditView)
2. Pole jest wypełnione wartością z bazy danych jeśli kontakt już ma telefon
3. Walidacja JS sprawdza format numeru przed wysłaniem formularza
4. Przy błędnym formacie wyświetla się komunikat „Nieprawidłowy format numeru telefonu"
5. Pole jest opcjonalne — formularz zapisuje się bez problemu gdy telefon jest pusty
6. Wartość telefonu jest zapisywana do bazy po kliknięciu Zapisz

## Zakres implementacji

- `modules/Contacts/views/EditView.php` — dodanie pola `phone_work`
- `include/js/contacts.js` — walidacja `validateContactForm()`

## Poza zakresem

- Walidacja po stronie PHP (backend)
- Formatowanie numeru przy zapisie
- Pole telefon komórkowy (`phone_mobile`)
