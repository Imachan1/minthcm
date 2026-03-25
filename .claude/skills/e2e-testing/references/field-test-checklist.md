# Checklist testu pola / małego testu

Używaj tej checklisty gdy testujesz **dodanie lub modyfikację pola/pól** w istniejącym module — nie pełny test modułu. Typowy scenariusz: developer dodał nowe pole, zmienił typ pola, dodał walidację.

Ta checklist jest krótsza niż test modułu — nie sprawdzasz subpaneli, QuickCreate ani dashletów. Skupiasz się na samym polu i jego wpływie na istniejące widoki.

---

## Widok tworzenia i edycji (EditView)

**ZAWSZE:**
- Pole jest widoczne na widoku **tworzenia** (CreateView / EditView nowego rekordu)
- Pole jest widoczne na widoku **edycji** (EditView istniejącego rekordu)
- **Etykieta** — brak nazw technicznych (np. LBL_NAME), poprawna pisownia
- Pole jest **read-only lub edytowalne** — zgodnie z US
- Typ pola zgodny z US (tekst, liczba, data, enum, relate itp.)

**JEŚLI US WYMAGA:**
- Pole jest **wymagane** — nie można zapisać rekordu bez uzupełnienia
- Pole ma **walidację** — sprawdź:
  - Wartości graniczne (testy brzegowe) — np. min/max, format, zakres
  - Wartości niepoprawne — komunikat walidacji się pojawia
  - Wartości poprawne — zapis przechodzi
- Pole ma **wartość domyślną** — pojawia się automatycznie przy tworzeniu

---

## Zapis i widok szczegółowy (DetailView)

**ZAWSZE:**
- Wartość wpisana w pole **zapisuje się poprawnie**
- Wartość jest **widoczna w DetailView** i zgodna z tym co wpisano
- Etykieta w DetailView — brak nazw technicznych

---

## Pola relacyjne (jeśli nowe pole jest typu relate)

**ZAWSZE:**
- **Quick Search (QS)** — wpisanie części nazwy podpowiada rekordy
- **Popup (strzałka)** — otwiera widok listy, rekord można wybrać
- Oba mechanizmy testuj niezależnie — mogą się psuć osobno
- Wybrana wartość zapisuje się poprawnie

---

## Audyt (Historia zmian)

**ZAWSZE:**
- Pole jest w historii zmian (audycie)
- Zmiana wartości pola jest odnotowana w historii
- Etykieta w audycie — brak nazw technicznych

**WYJĄTKI (pola NIE w audycie):**
- Pola przeliczane automatycznie
- Pola techniczne
- Pola relacyjne

---

## Raport zaawansowany

**ZAWSZE:**
- Pole jest dostępne w raporcie zaawansowanym (KReporter / raporty)
- Etykieta pola w raporcie — poprawna, brak nazw technicznych
- Można dodać pole do raportu i wygenerować raport

---

## Widok listy (ListView)

**JEŚLI US WYMAGA wyświetlania na liście:**
- Pole jest widoczne jako kolumna na ListView
- Etykieta kolumny — poprawna
- Nazwy skrócone zastosowane (jeśli US je definiuje)

**ZAWSZE (niezależnie czy pole jest na liście):**
- **Filtrowanie** po nowym polu działa (pole jest dostępne w filtrach)
- **Sortowanie** po nowym polu działa (jeśli pole jest na ListView)

---

## Wyszukiwanie

**ZAWSZE:**
- Rekord z uzupełnionym polem jest wyszukiwalny przez **Global Search**
- Rekord jest wyszukiwalny przez **wyszukiwarkę modułu** (jeśli istnieje)

> **Ważne:** Global Search sprawdza wyszukiwalność rekordu, nie pola. Ale po dodaniu pola sprawdź czy rekord nadal się wyszukuje — dodanie pola nie powinno zepsuć wyszukiwania.

---

## Podejście do małego testu

Przy małych testach (dodanie pola/kilku pól) **nie twórz testów automatycznych od zera**. Zamiast tego:

1. **Jeśli istnieje już test automatyczny dla tego modułu** — rozszerz go o nowe pole (np. dodaj wypełnienie pola w fixtures i asercję w spec)
2. **Jeśli nie ma testu automatycznego** — przetestuj manualnie (przez Claude in Chrome) i zapisz log/raport z wynikiem

Szczegóły podejścia → patrz `test-workflow.md`.
