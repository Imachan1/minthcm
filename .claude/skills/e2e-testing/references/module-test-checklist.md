# Checklist testu modułu

Używaj tej checklisty zawsze gdy testujesz **nowy moduł CRM** — niezależnie od platformy (MintHCM, SuiteCRM, SugarCRM, SpiceCRM, Creatio). Checklist pokrywa pełny zakres testu modułu.

Każda sekcja ma elementy **ZAWSZE** (sprawdzaj bez względu na US) i **JEŚLI US WYMAGA** (sprawdzaj tylko gdy User Story to określa).

> **Uwaga:** Dashlety, import/eksport i inne elementy specyficzne dla platformy znajdziesz w skillu systemowym (np. `minthcm-e2e`). Ta checklist zawiera tylko elementy wspólne dla wszystkich platform.

---

## 1. Początkowe

**ZAWSZE:**
- Moduł jest dostępny w menu nawigacyjnym (górnym lub bocznym — zależy od platformy)
- Moduł otwiera się po kliknięciu w menu
- Nazwa modułu w menu jest poprawna (wielka litera, brak literówek, brak nazw technicznych)
- Liczba mnoga i pojedyncza zastosowana poprawnie (np. w menu bocznym vs nagłówku)
- Po utworzeniu rekordu w module — rekord jest wyszukiwalny przez **Global Search**

**JEŚLI US WYMAGA:**
- Uprawnienia — moduł widoczny tylko dla określonych ról/grup (pozostałe role go nie widzą)
- Kolejność modułu w menu zgodna z US

---

## 2. Tworzenie nowego rekordu / Edycja istniejącego

**ZAWSZE:**
- Formularz tworzenia/edycji się otwiera i jest w pełni załadowany
- Wszystkie pola z US są widoczne na formularzu
- Brak pól technicznych, które nie powinny być widoczne
- Rozmieszczenie pól:
  - Pola pogrupowane wg znaczenia i wzajemnych powiązań
  - Pola wpływające na siebie nawzajem obok siebie (np. Status i Opis statusu)
  - Pola uzupełniające się razem (np. data i miejsce spotkania)
  - Pola o podobnym znaczeniu blisko siebie (np. Tel. biurowy, Tel. komórkowy)
  - Pola opisowe (textarea) rozciągnięte na cały wiersz
  - Pola o podobnej wysokości w jednym wierszu
- **Etykiety (labele):** brak nazw technicznych (np. LBL_NAME, LBL_CURRENCY_ID), poprawna pisownia, wielkie litery gdzie trzeba
- Typ pola zgodny z US:
  - Pola tekstowe przyjmują tekst
  - Pola liczbowe przyjmują liczby (całkowite lub zmiennoprzecinkowe wg US)
  - Pola walutowe przyjmują kwoty z przecinkiem
  - Pola enum/multienum — wartości listy zgodne z US
- Pola **read-only vs edytowalne** — zgodnie z US
- Pola **wymagane** — nie można zapisać rekordu bez ich uzupełnienia
- Pola **relacyjne (relate):**
  - Quick Search (QS) — wpisanie części nazwy podpowiada rekordy
  - Popup (strzałka) — otwiera widok listy, rekord można wybrać
  - Oba mechanizmy testuj niezależnie — mogą się psuć osobno
- Walidacja:
  - Zapis bez wymaganych pól → komunikat walidacji
  - Po błędzie walidacji dane w formularzu nie znikają
  - URL nadal wskazuje widok edycji/tworzenia po błędzie
- Zapis z poprawnymi danymi → przekierowanie do widoku szczegółowego (DetailView)
- Zapisane dane widoczne i poprawne w DetailView

**JEŚLI US WYMAGA:**
- Komunikat sukcesu (toast) po zapisie
- Automatyzacja — pole przelicza się automatycznie po wprowadzeniu wartości w innym polu (sprawdź poprawność wyliczeń)
- Niestandardowe akcje — np. na zapis rekord się przekształca, pole staje się nieedytowalne po zapisie w innym module
- Oznaczenie pól wymaganych (gwiazdka)

---

## 3. Widok szczegółowy (DetailView)

**ZAWSZE:**
- Wszystkie pola z US są widoczne
- Wartości pól zgodne z tym co wpisano przy tworzeniu/edycji
- Rozmieszczenie pól takie samo jak w widoku edycji/tworzenia
- Pola z odpowiednich sekcji są w odpowiednich zakładkach (panelach)
- **Etykiety** — brak nazw technicznych, poprawna pisownia
- Typ pola i relacje wyświetlają się poprawnie
- Ilość znaków — długi tekst nie jest obcięty bez powodu

---

## 4. Audyt (Historia zmian)

**ZAWSZE:**
- Zakładka audytu/historii zmian jest dostępna w DetailView
- Wszystkie pola (poza wyjątkami) są audytowalne — zmiana wartości jest zapisywana w historii
- **Etykiety** w historii zmian — brak nazw technicznych

**WYJĄTKI (NIE audytujemy):**
- Pola zmieniane automatycznie przez przeliczenia z innych pól
- Pola techniczne
- Pola relacyjne
- Pola usunięte

---

## 5. Subpanele

**ZAWSZE:**
- Subpanele widoczne pod widokiem szczegółowym
- Kolumny w subpanelach ustawione domyślnie
- **Etykiety** kolumn — brak nazw technicznych
- Relacje — kliknięcie w link (np. nazwę zamówienia) przenosi do prawidłowego modułu
- Czynności — Utwórz, Wybierz, Zaznacz wszystkie itp. działają
- Sortowanie danych w kolumnach subpanelu działa

**JEŚLI US WYMAGA:**
- Niestandardowe akcje w subpanelu (opisane w US)

---

## 6. QuickCreate

**ZAWSZE:**
- Formularz QuickCreate otwiera się poprawnie
- Liczba pól nie przekracza ~10 (jeśli więcej — zgłoś jako sugestię rezygnacji z QuickCreate)
- Rozmieszczenie pól podobne do pełnego formularza tworzenia
- Pola mają te same właściwości co w pełnym formularzu:
  - Typ pola
  - Wymagalność
  - Relacje
  - Wartości listy
  - Ilość znaków
- Walidacja działa tak samo jak w pełnym formularzu
- **Etykiety** — brak nazw technicznych

---

## 7. Widok listy (ListView)

**ZAWSZE:**
- Lista się ładuje i wyświetla rekordy
- Domyślne kolumny ustawione poprawnie (ostatnie dwie: „Przypisano do" i „Data utworzenia" — standard eVolpe)
- Kolumny najbardziej istotne z punktu biznesowego są widoczne
- **Etykiety** kolumn — brak nazw technicznych
- Nazwy skrócone zastosowane tam gdzie US je definiuje
- Relacje — podświetlony tekst (link) przenosi do powiązanego modułu
- Czynności: zaznaczenie, zaznaczenie wielu, usuwanie, ustawienia kolumn działają
- **Sortowanie** po kolumnach działa
- Paginacja (przewijanie stron) działa
- Kliknięcie w rekord → otwiera DetailView

---

## 8. Filtrowanie

**ZAWSZE:**
- Widok filtrów się otwiera
- Wszystkie nowe/zmodyfikowane pola są dostępne w filtrach (poza: textarea, iframe, image, niestandardowe)
- **Etykiety** — brak nazw technicznych
- Nazwy skrócone zastosowane prawidłowo
- Filtrowanie po przedziałach działa dla pól: data, walutowe, kwotowe
- Akcje: Szukaj, Wyczyść, Zapisz filtr — działają poprawnie

**SPECYFICZNE DLA PLATFORMY (patrz skill systemowy):**
- Pogrupowanie pól po typie (np. inputy z inputami, multienumy z multienumami) — dotyczy SuiteCRM

---

## 9. Masowa aktualizacja (Mass Update)

**ZAWSZE:**
- Widok masowej aktualizacji się otwiera
- Dostępne pola to: Przypisano do, Zespół/Grupa, dropdown, multienum, date/datetime, pola bez specjalnej logiki
- **Etykiety** — brak nazw technicznych
- Nazwy skrócone zastosowane prawidłowo
- Rozmieszczenie pól logiczne i spójne
- Masowa aktualizacja faktycznie zmienia wartości w wybranych rekordach

> **Uwaga:** Masowa aktualizacja nie jest standardem — nie wszystkie pola się tu znajdą.

---

## 10. Raporty

**ZAWSZE:**
- Moduł występuje w raportach podstawowych
- Wszystkie pola (poza technicznymi i niestandardowymi z US) są raportowalne
- **Etykiety** pól w raporcie — brak nazw technicznych
- Język — etykiety w poprawnym języku
- Możliwość utworzenia prostego raportu (utwórz go i sprawdź czy się generuje)

**JEŚLI US WYMAGA:**
- Raporty zaawansowane (KReporter) — moduł dostępny, pola dostępne, labele poprawne
- Utwórz prosty raport zaawansowany i sprawdź generowanie

---

## 11. Popup (search + listview)

**ZAWSZE:**
- Popup otwiera się po kliknięciu strzałki przy polu relacyjnym
- **Search w popupie:**
  - Wyszukiwanie rekordów działa
  - Etykiety poprawne
  - Pola pogrupowane odpowiednio
- **ListView w popupie:**
  - Rekordy widoczne i możliwe do wybrania
  - Etykiety poprawne
  - Sortowanie działa

> **Przypomnienie:** Popup i Quick Search (QS) to dwa niezależne mechanizmy dostępu do pól relacyjnych. Testuj oba — mogą się psuć osobno.

---

## 12. Popupy niestandardowe

**JEŚLI WYSTĘPUJĄ:**
- Treść komunikatu — brak literówek, poprawny język
- Zatwierdzenie działa zgodnie z oczekiwaniem
- Anulowanie działa (zamyka popup, nie wykonuje akcji)

---

## 13. Wyszukiwanie (podsumowanie)

**ZAWSZE:**
- Rekord wyszukiwalny przez **Global Search** (główna wyszukiwarka)
- Rekord wyszukiwalny przez **wyszukiwarkę modułu** (jeśli istnieje)

> **Ważne:** Global Search sprawdza czy rekord jest wyszukiwalny w systemie — nie czy konkretne pole jest searchable. Wyszukiwarka modułu sprawdza szukanie w kontekście danego modułu.

---

## Statusy w logu testu

Każda sekcja po przetestowaniu otrzymuje status:
- **OK** — sekcja przeszła pomyślnie
- **BUG** — wykryto błąd (utwórz zgłoszenie — patrz `test-workflow.md`)
- **SUGESTIA** — propozycja usprawnienia (nie blokuje)
- **NA** — nie dotyczy (sekcja nie ma zastosowania w tym module/projekcie)
