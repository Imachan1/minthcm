# Dashlety — checklist (MintHCM / SuiteCRM)

> **Ten dokument dotyczy wyłącznie platform MintHCM i SuiteCRM.** SugarCRM, SpiceCRM i Creatio mają inne mechanizmy dashboardów — nie stosuj tej checklisty do nich.

Dashlety to widżety na stronie głównej (landing page / dashboard) systemu. Testujemy je jako część testu nowego modułu.

---

## Dodawanie dashletu

- Dashlet dodajemy z pozycji landing page (ikona domku), w działaniach klikając "Dashlet"
- Nowy moduł powinien mieć dashlet dostępny do dodania

---

## ListView dashletu

**ZAWSZE:**
- Na widoku listy dashletu domyślnie ustawione są najważniejsze kolumny (z punktu biznesowego)
- **Etykiety** kolumn — brak nazw technicznych
- Sortowanie po kolumnach działa
- Czynności dostępne: edycja, podgląd, przewijanie, relacje

---

## Edycja / Konfiguracja dashletu

**ZAWSZE:**
- W kolumnach do wyboru są nowe/usunięte pola (zgodnie z US)
- **Etykiety** pól — brak nazw technicznych
- Filtrowanie wyników działa
- Domyślne kolumny ustawione poprawnie
- Pola pogrupowane logicznie
- Czynności dashletu: odświeżanie, ustawienie liczby wierszy — działają

**JEŚLI US WYMAGA:**
- Konkretne kolumny domyślne zdefiniowane w US
- Konkretne filtry domyślne zdefiniowane w US
