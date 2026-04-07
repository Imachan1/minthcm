# Integracja z Redmine -- Merge to Release

## Wymagane narzedzie

Wszystkie wywolania Redmine wykonuj przez narzedzie **Redmine MCP** (`redmine_request`).
Bazowy URL: `https://redmine.evolpe.net`

**Jesli `redmine_request` nie jest dostepne** -- uzyj sekcji "Tryb manualny" w kazdym kroku ponizej.

---

## Pobieranie zagadnienia (Step 2)

```
redmine_request: GET /issues/{ISSUE_ID}.json?include=children
```

Wyciagnij i zapamietaj:

- **subject** -- tytul zagadnienia
- **tracker.id** i **tracker.name**
- **project.id** i **project.identifier**
- **status.name**
- **children** -- lista podzagnien (id, subject, tracker, status)

Jesli zagadnienie nie istnieje (404) -- zatrzymaj sie: "Zagadnienie #{ISSUE_ID} nie znalezione w Redmine."

**Walidacja trackera** -- dozwolone:

| Tracker | ID |
|---|---|
| User Story | 18 |
| User Story Bug | 19 |
| Epic | 22 |
| Spike | 23 |

Jesli tracker to Task (24) lub inny -- zatrzymaj sie:
```
Zagadnienie #{ISSUE_ID} ma tracker "{tracker_name}" -- merge do release powinien
byc wykonywany na poziomie User Story, Epic lub Spike, nie Task.
Podaj numer wlasciwego US/Epic.
```

### Tryb manualny -- Step 2

Jesli nie masz `redmine_request`, wyswietl:

```
Nie mam dostepu do Redmine MCP. Prosze wklej dane zagadnienia #{ISSUE_ID}
(https://redmine.evolpe.net/issues/{ISSUE_ID}):

- Tytul (subject):
- Tracker (np. User Story, Epic, Spike):
- Projekt (identifier z URL):
- Status:
- Lista podzagnien (numery i tytuly):
```

---

## Mapowanie release_id (Step 8a)

Pole "Release" na formularzu Redmine pochodzi z pluginu Redmine Releases.
W API ustawia sie je jako `release_id` w body PUT/POST.

**Znane mapowanie release_id:**

| Wersja | release_id |
|---|---|
| 4.4.0 | 252 |
| 4.3.1 | 264 |
| 4.3.0 | 156 |
| 4.2.2 | 231 |
| 4.2.1 | 228 |
| 4.2.0 | 225 |
| 4.1.4 | 197 |
| 4.1.3 | 160 |
| 4.1.2 | 152 |
| 4.1.1 | 130 |
| 4.1.0 | 92 |

Jesli `RELEASE_VERSION` nie ma w tabeli -- zapytaj usera o `release_id`:
```
Nie znam release_id dla wersji "{RELEASE_VERSION}".
Podaj wartosc pola Release z formularza Redmine (liczba z atrybutu value w <option>).
```

---

## Aktualizacja zagadnienia glownego (Step 8a)

```
redmine_request: PUT /issues/{ISSUE_ID}.json
```

```json
{
  "issue": {
    "status_id": 3,
    "release_id": {RELEASE_ID}
  }
}
```

- `status_id: 3` -- Rozwiazane
- `release_id` -- ID z tabeli mapowania powyzej

Jesli PUT zwroci blad -- wyswietl szczegoly i poinformuj usera.

Po pomyslnej aktualizacji:
```
Zaktualizowano #{ISSUE_ID}: Status=Rozwiazane, Release={RELEASE_VERSION}
```

### Tryb manualny -- aktualizacja zagadnienia

```
Nie mam dostepu do Redmine MCP. Zaktualizuj reczne zagadnienie #{ISSUE_ID}:

- Status: Rozwiazane
- Release: {RELEASE_VERSION}
```

---

## Aktualizacja podzagnien (Step 8b)

Dla kazdego podzagadnienia z listy children:

```
redmine_request: PUT /issues/{CHILD_ID}.json
```

```json
{
  "issue": {
    "status_id": 5
  }
}
```

- `status_id: 5` -- Zrealizowane

Po kazdej aktualizacji wyswietl:
```
#{CHILD_ID}: Status=Zrealizowane ✓
```

Jesli PUT dla podzagadnienia zwroci blad -- wyswietl blad i kontynuuj z nastepnym.

### Tryb manualny -- podzagadnienia

```
Nie mam dostepu do Redmine MCP. Ustaw status "Zrealizowane" dla podzagnien:

{lista CHILD_ID z tytulem}
```
