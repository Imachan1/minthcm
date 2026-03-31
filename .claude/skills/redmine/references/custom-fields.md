# Custom Fields — Redmine eVolpe

## Custom Fields — Issues

### Universal (wiele trackerów)

| ID | Name | Format | Required | Dotyczy trackerów |
|---|---|---|---|---|
| 6 | **Data oddania** | date | no | Błąd, Implementacja, Wsparcie, Administracja, Dokumentacja, Analiza, Research, Testowanie, Mały rozwój, User Story, User Story Bug, Spike, Task, Zamówienie |
| 20 | **Release notes** | list | no | Błąd, Implementacja, Wsparcie, Administracja, Mały rozwój, Błąd krytyczny, Błąd niekrytyczny, User Story, User Story Bug, Spike |
| 22 | **Spółka NASK** | list | project-dependent | Wybrane projekty |

**Wartości Release notes**: `Niepotrzebne`, `Potrzebne`, `Zrobione`
**Wartości Spółka NASK**: `PIB`, `S.A.`

### Konsultacje (13) / Mały rozwój (14) / Zamówienie (27)

| ID | Name | Format | Wartości |
|---|---|---|---|
| 15 | **Stan wyceny** | list | `Zgłoszono / Reported`, `Oszacowano / Estimated`, `Akceptacja wyceny / Estimation accepted` |

### Agile Trackers: User Story (18), US Bug (19), Mały rozwój (14), Błąd krytyczny (15), Błąd niekrytyczny (16), Spike (23)

| ID | Name | Format | Wartości |
|---|---|---|---|
| 24 | **Wymaga interakcji klienta** | list | `Nie`, `Tak` |

### Podsumowanie: wymagane CF przy tworzeniu

| Tracker | Wymagane / zalecane custom fields |
|---|---|
| User Story (18) | — |
| User Story Bug (19) | — |
| Spike (23) | — |
| Mały rozwój (14) | cf 15 (Stan wyceny) |
| Błąd krytyczny (15) | — |
| Błąd niekrytyczny (16) | — |
| Konsultacje (13) | cf 15 (Stan wyceny) |
| Zamówienie (27) | cf 15 (Stan wyceny) |
| Task (24) | — (brak specyficznych, ale zawsze wymaga parent issue i kategorii) |

---

## Custom Fields — Projects

| ID | Name | Format | Required | Wartości |
|---|---|---|---|---|
| 1 | **Data rozpoczęcia** | date | no | — |
| 3 | **Termin zakończenia** | date | no | — |
| 18 | **Wersja** | string | no | — |
| 19 | **Edycja** | list | no | `Community`, `Komercyjna` |
| 21 | **Typ** | list | **yes** | `Classic Implemantation`, `Classic Maintenance`, `SCRUM Implemantation`, `Time-Material Maintenance`, `Other` |
