# Typ projektu: Serwisowy (Service / Maintenance)

## Jak rozpoznać
- Custom field **Typ** (cf 21) = `Classic Maintenance` lub `Time-Material Maintenance`
- Lub: projekt jest podprojektem projektu typu Serwis / Maintenance

## Dostępne trackery

| ID | Tracker | Rola |
|---|---|---|
| 14 | **Mały rozwój** | Odpowiednik User Story — opisuje drobną customizację systemu |
| 27 | **Zamówienie** | Tworzony gdy zakres customizacji jest duży. Odpowiednik Epica |
| 15 | **Błąd krytyczny** | Błąd zgłoszony przez klienta, który **blokuje użytkowanie** systemu |
| 16 | **Błąd niekrytyczny** | Błąd zgłoszony przez klienta, który **nie blokuje** użytkowania systemu |
| 13 | **Konsultacje** | Pytania, analiza dyskusji o nowych potrzebach. Często poprzedza Mały rozwój lub Zamówienie |
| 3 | **Wsparcie** | Prośba klienta wymagająca działania w systemie — konfiguracja, dodanie użytkowników, nowe raporty |

## Podprojekt wewnętrzny ("- Internal")

Każdy projekt serwisowy ma podprojekt dziecko o nazwie **"[nazwa projektu] - Internal"**. Główny tracker w Internal: **Task (24)**.

### Dla Mały rozwój, Błąd krytyczny, Błąd niekrytyczny

Taski tworzone w podprojekcie Internal jako dzieci zagadnienia z projektu nadrzędnego.

### Dla Zamówienie (duży zakres)

Podprojekt Internal używa pełnej struktury wdrożeniowej:
- User Story (18), User Story Bug (19), Spike (23), Task (24)
- Spotkanie (11), Analiza (6), Project Management (21)

## Hierarchia zagadnień

### Standardowa (Mały rozwój / Błędy)

```
[Projekt serwisowy]
  Mały rozwój (14) / Błąd krytyczny (15) / Błąd niekrytyczny (16)

[Projekt - Internal]
  └─ Task (24) — dziecko zagadnienia z projektu nadrzędnego
       Typowe Taski: Implementation, Testing, Buffor, Bug
```

### Duży zakres (Zamówienie)

```
[Projekt serwisowy]
  Zamówienie (27)

[Projekt - Internal]  ← pełna struktura wdrożeniowa
  └─ User Story (18)
       └─ Task (24): Implementation, Testing, Buffor, Bug (w trakcie testów)
  └─ User Story Bug (19)
       └─ Task (24): główny Task typu Bug
  └─ Spike (23)
       └─ Task (24): Implementation, Research, Administration
```

## Zasady trackera Task (24)

Task to jednostka pracy — **zawsze** wymaga:
- **`parent_issue_id`** — Task nie istnieje samodzielnie
- **`category_id`** — kategoria jest obowiązkowa

Task tworzy się **w podprojekcie Internal**, nie w projekcie serwisowym.

### Typowe Taski

- **Implementation** — praca deweloperska
- **Testing** — testy
- **Buffor** — bufor czasowy
- **Bug** — błędy znalezione podczas testowania
- **Research** — badania (w Spike)
- **Administration** — prace administracyjne (w Spike)

### Uwaga o błędach w bieżącym sprincie

Błędy znalezione **w trakcie testów** wobec otwartego zagadnienia zgłaszaj jako Task (dziecko tego zagadnienia), **nie** jako osobny Błąd krytyczny/niekrytyczny. Trackery Błąd krytyczny (15) i Błąd niekrytyczny (16) są dla błędów zgłaszanych przez **klienta**.

## Wymagane custom fields

- **Mały rozwój**: cf 15 (Stan wyceny)
- **Zamówienie**: cf 15 (Stan wyceny)
- **Konsultacje**: cf 15 (Stan wyceny)
- **Błąd krytyczny, Błąd niekrytyczny**: brak specyficznych CF
- **Wsparcie**: brak specyficznych CF
- **Task**: brak specyficznych CF, ale wymaga `parent_issue_id` i `category_id`
- **Kategorie**: nazwy kategorii są takie same w każdym projekcie, ale **ID są różne per projekt**
- Pełna lista wartości CF → `custom-fields.md`
