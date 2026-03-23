---
name: redmine-guide
version: 1.0.0
description: "Dostarcza wiedzę o strukturze i zasadach Redmine eVolpe — bez potrzeby dostępu do API. Używaj gdy potrzebujesz kontekstu o Redmine: jakie są typy projektów, trackery, statusy, workflow, hierarchia zagadnień. Trigger: użytkownik wspomina Redmine, issue, ticket, zagadnienie, sprint, User Story, Mały rozwój, Błąd krytyczny — i nie ma dostępu do MCP redmine."
---

# Redmine — wiedza kontekstowa eVolpe

Skill dostarcza wiedzę o tym jak działa Redmine w eVolpe. Nie wymaga dostępu do API ani narzędzi MCP.

## Kiedy używać

- Inny skill (np. `code-review`) potrzebuje wiedzieć jak interpretować numer/typ zagadnienia Redmine
- Użytkownik pyta o strukturę Redmine, typy projektów, trackery, statusy
- Użytkownik wspomina Redmine issue/ticket i potrzeba zrozumienia kontekstu biznesowego
- Tworzenie dokumentacji, planów, analiz które odnoszą się do zagadnień Redmine

## Kiedy nie używać

- Gdy dostępne są narzędzia MCP Redmine (`redmine_request` itp.) — wtedy używaj skilla `redmine-guide` z pluginu redmine-integration, który daje pełne możliwości API
- Gdy użytkownik chce aktywnie tworzyć/edytować zagadnienia przez API

---

## Pliki referencyjne (czytaj on-demand)

| Plik | Kiedy czytać |
|---|---|
| `references/custom-fields.md` | Potrzebujesz znać custom fields, ich ID i wartości |
| `references/project-type-wdrozenia.md` | Projekt wdrożeniowy (Implementation/SCRUM) |
| `references/project-type-serwisowe.md` | Projekt serwisowy (Maintenance) |
| `references/project-type-wewnetrzne.md` | Projekt wewnętrzny (HR, Admin, Marketing) |

---

## Trackery — tabela lookup

| ID | Tracker | Używany w |
|---|---|---|
| 3 | Wsparcie | Serwis |
| 4 | Administracja | Różne |
| 6 | Analiza | Wdrożenia |
| 11 | Spotkanie | Wdrożenia |
| 13 | Konsultacje | Serwis |
| 14 | Mały rozwój | Serwis |
| 15 | Błąd krytyczny | Serwis |
| 16 | Błąd niekrytyczny | Serwis |
| 18 | User Story | Wdrożenia |
| 19 | User Story Bug | Wdrożenia |
| 21 | Project Management | Wdrożenia |
| 22 | Epic | Wdrożenia |
| 23 | Spike | Wdrożenia |
| 24 | Task | Wdrożenia + Serwis (Internal) |
| 25 | Marketing | Wewnętrzne |
| 26 | Management Task | Wewnętrzne |
| 27 | Zamówienie | Serwis |

**Deprecated** (nie używać dla nowych): 2, 5, 7, 8, 10, 12, 17, 20

---

## Statusy zagadnień

| ID | Nazwa | Stan |
|---|---|---|
| 1 | Nowy | otwarty |
| 2 | W Toku | otwarty |
| 3 | Rozwiązany | otwarty |
| 5 | Zrealizowany | zamknięty |
| 6 | Odroczony | zamknięty |
| 7 | Odrzucony | zamknięty |

Typowy flow: **Nowy → W Toku → Rozwiązany → Zrealizowany**

---

## Typy projektów i jak je rozpoznać

Typ projektu determinuje dostępne trackery i reguły. Ustal go na podstawie custom field **Typ** (cf 21):

| Wartość cf 21 | Typ projektu | Plik referencyjny |
|---|---|---|
| `Classic Implemantation`, `SCRUM Implemantation` | **Wdrożenie** | `references/project-type-wdrozenia.md` |
| `Classic Maintenance`, `Time-Material Maintenance` | **Serwisowy** | `references/project-type-serwisowe.md` |
| Projekt HR, Marketing, Admin, wewnętrzny | **Wewnętrzny** | `references/project-type-wewnetrzne.md` |

---

## Hierarchia zagadnień — skrót

### Projekt wdrożeniowy
```
Epic (22)
  └─ User Story (18) / User Story Bug (19) / Spike (23)
       └─ Task (24): Implementation, Testing, Buffor, Bug
```

### Projekt serwisowy
```
[Projekt kliencki]
  Mały rozwój (14) / Błąd krytyczny (15) / Błąd niekrytyczny (16) / Zamówienie (27)

[Projekt - Internal]
  └─ Task (24) — dziecko zagadnienia z projektu nadrzędnego
```

**Task (24) zawsze wymaga: parent issue + kategoria**

---

## Logowanie czasu

Czas pracy jest logowany przez **MintHCM** — nie przez Redmine API. Redmine przechowuje te wpisy tylko do odczytu.

---

## Priorytety

| ID | Nazwa | Default |
|---|---|---|
| 3 | Niski | |
| 4 | Normalny | tak |
| 5 | Wysoki | |
| 6 | Pilny | |
| 7 | Na wczoraj | |
