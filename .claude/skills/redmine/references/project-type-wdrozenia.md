# Typ projektu: Wdrożenia (Implementation)

## Jak rozpoznać
- Custom field **Typ** (cf 21) = `SCRUM Implemantation` lub `Classic Implemantation`
- Lub: projekt jest podprojektem projektu typu SCRUM / Implementation

## Dostępne trackery

| ID | Tracker | Rola |
|---|---|---|
| 22 | **Epic** | Grupuje większe tematy obejmujące wiele sprintów i wiele User Stories — np. "Integracja z Systemem X" |
| 18 | **User Story** | Główny tracker do definiowania zakresu i wymagań. Najczęściej używany tracker we wdrożeniach |
| 19 | **User Story Bug** | Błędy związane z User Story z **zamkniętego** sprintu. Błędy znalezione w bieżącym sprincie wobec otwartego US zgłaszaj jako Task (dziecko tego US) |
| 23 | **Spike** | Zagadnienia niekoniecznie związane z dostawą kodu — badania, prototypowanie. Używany też do rekurencyjnych zagadnień per-sprint jak "Instalacja i Konfiguracja" |
| 24 | **Task** | Jednostka pracy w ramach Spike, User Story lub User Story Bug. **Zawsze wymaga parent issue. Zawsze wymaga kategorii.** |
| 6 | **Analiza** | Jedno per sprint — Analityk loguje czas na analizę |
| 21 | **Project Management** | Jedno per sprint — Scrum Master / PM loguje czas na zarządzanie |
| 11 | **Spotkanie** | Jedno per sprint — reszta zespołu loguje czas na spotkania |

## Hierarchia zagadnień

```
Epic (22)
  └─ User Story (18)
       └─ Task (24): Implementation, Testing, Buffor, Bug (w trakcie testów)
  └─ User Story Bug (19)
       └─ Task (24): główny Task typu Bug
  └─ Spike (23)
       └─ Task (24): Implementation, Research, Administration
```

## Typowe Taski w User Story
- **Implementation** — praca deweloperska
- **Testing** — testy
- **Buffor** — bufor czasowy
- **Bug** — błędy znalezione podczas testowania (w bieżącym sprincie)

## Sprint
- Sprint = **fixed_version_id** na zagadnieniu
- Wersje/sprinty per projekt: `GET /projects/{identifier}/versions.json`

## Wymagane custom fields
- **Task**: brak specyficznych CF, ale **zawsze** wymaga `parent_issue_id` i `category_id`
- **Kategorie**: nazwy kategorii są takie same w każdym projekcie, ale **ID są różne per projekt**
- Pełna lista wartości CF → `custom-fields.md`
