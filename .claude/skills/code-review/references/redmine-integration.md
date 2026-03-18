# Integracja z Redmine — Code Review

## Wymagane narzędzie

Wszystkie wywołania Redmine wykonuj przez narzędzie **Redmine MCP** (`redmine_request`).
Bazowy URL: `https://redmine.evolpe.net`

**Jeśli `redmine_request` nie jest dostępne** — użyj sekcji "Tryb manualny" w każdym kroku poniżej.

---

## Pobieranie zagadnienia (Step 2)

```
redmine_request: GET /issues/{ISSUE_ID}.json
```

Wyciągnij i zapamiętaj:

- **subject** — tytuł zagadnienia
- **description** — wymagania, kryteria akceptacji
- **tracker.id** i **tracker.name**
- **project.id** i **project.identifier** (slug, np. `evolpe-ai-contrain-voicebot-ai`)
- **status.name**
- **parent.id** — jeśli istnieje

Jeśli zagadnienie nie istnieje (404) — zatrzymaj się: „Zagadnienie #{ISSUE_ID} nie znalezione w Redmine."

Wyświetl podsumowanie:
```
Zagadnienie #{ISSUE_ID}: {subject}
Projekt: {project} | Tracker: {tracker} | Status: {status}
```

### Tryb manualny — Step 2

Jeśli nie masz `redmine_request`, wyświetl:

```
Nie mam dostępu do Redmine MCP. Proszę wklej następujące dane zagadnienia #{ISSUE_ID}
(znajdziesz je na stronie https://redmine.evolpe.net/issues/{ISSUE_ID}):

- Tytuł (subject):
- Tracker (np. User Story, Epic, Spike, Task):
- Projekt (identifier/slug z URL, np. evolpe-ai-voicebot):
- Status:
- Priorytet (np. Normalny, Wysoki, Pilny):
- Opis / kryteria akceptacji (opcjonalnie, ale pomoże w review):
- ID zagadnienia nadrzędnego (parent issue), jeśli istnieje:
```

Użyj danych podanych przez usera zamiast wywołania API. Kontynuuj Step 3 (walidacja trackera) na podstawie podanego trackera.

---

## Walidacja trackera (Step 3)

Code review dotyczy zagadnień nadrzędnych, **nie** tasków.

**Dozwolone trackery:**

| Tracker | ID |
|---|---|
| User Story | 18 |
| User Story Bug | 19 |
| Epic | 22 |
| Spike | 23 |

**Niedozwolony:**

| Tracker | ID |
|---|---|
| Task | 24 |

Jeśli tracker to Task lub inny spoza listy — zatrzymaj się:
```
Zagadnienie #{ISSUE_ID} ma tracker "{tracker_name}" — code review powinno być
wykonywane na poziomie User Story, Epic lub Spike, nie na poziomie Task.

Sprawdź parent tego zagadnienia lub podaj numer właściwego US/Epic.
```

Jeśli zagadnienie ma `parent.id` — podpowiedz: „Parent tego taska: #{parent_id} — czy chcesz przeprowadzić CR dla niego?"

---

## Lookup użytkownika w Redmine (Step 6 — część Redmine)

Po ustaleniu `AUTHOR_LOGIN` z git log, wyszukaj użytkownika w Redmine:

```
redmine_request: GET /users.json?name={AUTHOR_LOGIN}
```

Zapamiętaj:
- **ASSIGNEE_ID** — Redmine user id
- **ASSIGNEE_NAME** — pełne imię i nazwisko z Redmine

**Jeśli wyszukiwanie nie zwróciło wyników lub lista jest pusta** — zapytaj usera:
```
Nie znalazłem użytkownika "{AUTHOR_LOGIN}" w Redmine.
Podaj Redmine ID osoby, której robię CR (możesz sprawdzić na stronie profilu użytkownika: https://redmine.evolpe.net/users/{ID}).
```
Czekaj na podanie `ASSIGNEE_ID` i opcjonalnie `ASSIGNEE_NAME` przez usera.

Jeśli user poda inną osobę do przypisania CR — wyszukaj ją w Redmine tą samą metodą.

### Tryb manualny — Step 6

Jeśli nie masz `redmine_request`, wyświetl:

```
Nie mam dostępu do Redmine MCP. Proszę potwierdź kto ma być przypisany do zagadnienia CR:

Autor z gita: {AUTHOR_NAME} ({AUTHOR_EMAIL})

Czy CR przypisać do tej osoby? Jeśli tak — podaj jej Redmine ID (liczba z URL profilu: https://redmine.evolpe.net/users/{ID}).
Jeśli inna osoba — podaj imię i nazwisko oraz Redmine ID.
```

Użyj podanego przez usera `ASSIGNEE_ID` i `ASSIGNEE_NAME`.

---

## Tworzenie zagadnienia CR (Step 13)

Twórz zagadnienie **tylko jeśli znaleziono przynajmniej jedno CRITICAL lub WARNING**.
Jeśli brak — wyświetl: „Brak krytycznych ani ostrzegawczych problemów — nie utworzono zagadnienia Task/Bug."

**Zanim utworzysz zagadnienie — zawsze zapytaj usera o zgodę:**
```
Czy mam utworzyć zagadnienie CR BUG w Redmine dla #{ISSUE_ID}?

  Tytuł:       CR BUG: {ISSUE_SUBJECT}
  Projekt:     {project_identifier}
  Przypisano:  {ASSIGNEE_NAME}
  Znaleziono:  {X} CRITICAL, {Y} WARNING
```
Czekaj na potwierdzenie. Jeśli user odmówi — zakończ bez tworzenia zagadnienia.

### Tworzenie zagadnienia Re-CR (Step R6c z re-review.md)

Przy werdykcie CHANGES REQUESTED w re-review twórz zagadnienie z opisem zawierającym zarówno UNFIXED stare bugi jak i nowe.

**Zapytaj usera o zgodę:**
```
Czy mam utworzyć zagadnienie Re-CR BUG w Redmine dla #{ISSUE_ID}?

  Tytuł:       CR BUG: {ISSUE_SUBJECT}
  Projekt:     {project_identifier}
  Przypisano:  {ASSIGNEE_NAME}
  UNFIXED z poprzedniego CR: {M}
  Nowe problemy: {X} CRITICAL, {Y} WARNING
```
Czekaj na potwierdzenie. Jeśli user odmówi — zakończ bez tworzenia zagadnienia.

Przy tworzeniu zagadnienia Re-CR użyj tych samych kroków 13a–13b co przy zwykłym CR, z następującymi różnicami:
- `subject`: `"CR BUG: {ISSUE_SUBJECT}"`
- `description` zawiera dwie sekcje:

```
Błędy znalezione podczas Re-CR #{RECR_ROUND} zagadnienia #{ISSUE_ID}: {ISSUE_SUBJECT}.

### Nienaprawione problemy z poprzedniego CR

{lista UNFIXED findings z poprzedniego CR z plikiem, linią i opisem}

### Nowe problemy znalezione w Re-CR #{RECR_ROUND}

Znaleziono: {X} CRITICAL, {Y} WARNING, {Z} INFO

{lista nowych findings z plikiem, linią i opisem}

Raport CR (w repozytorium projektu): .ai/tasks/{ISSUE_ID}/cr.md
Commit Re-CR: {commit_hash}
```

### 13a. Pobierz ID kategorii "Bug"

```
redmine_request: GET /projects/{project_identifier}/issue_categories.json
```

Znajdź kategorię o nazwie **"Bug"** (lub najbliższej: "Bugs", "Błąd"). Zapamiętaj `CATEGORY_ID`.
Jeśli kategoria nie istnieje — pomiń pole `category_id` i dodaj notatkę w opisie.

### 13b. Utwórz zagadnienie

Najpierw użyj skilla `redmine-guide` jeśli go posiadasz.

```
redmine_request: POST /issues.json
```

```json
{
  "issue": {
    "project_id": "{project_identifier}",
    "tracker_id": 24,
    "parent_issue_id": {ISSUE_ID},
    "subject": "CR BUG: {ISSUE_SUBJECT}",
    "description": "Błędy znalezione podczas code review zagadnienia #{ISSUE_ID}: {ISSUE_SUBJECT}.\n\nZnaleziono: {X} CRITICAL, {Y} WARNING, {Z} INFO\n\nRaport CR (w repozytorium projektu): .ai/tasks/{ISSUE_ID}/cr.md\nCommit CR: {commit_hash}\n\n### Lista FIXME\n\n{lista findings CRITICAL, WARNING i INFO z plikiem i opisem}",
    "status_id": 1,
    "priority_id": {PARENT_PRIORITY_ID},
    "estimated_hours": 0,
    "assigned_to_id": {ASSIGNEE_ID},
    "category_id": {CATEGORY_ID}
  }
}
```

Pola:
- `project_identifier` — z Step 2
- `tracker_id: 24` — Task (wymagany dla podzagadnień User Story)
- `priority_id` — **przejmij z zagadnienia nadrzędnego** (`priority.id` z Step 2), nie ustawiaj na stałe
- `estimated_hours: 0` — zawsze ustawiaj na 0 (pole wymagane, nie zostawiaj pustego)
- `parent_issue_id` — numer zagadnienia z którego robiono CR
- `assigned_to_id` — ASSIGNEE_ID (autor kodu)
- `category_id` — z Step 13a

Jeśli POST zwróci błąd (422 lub inny) — wyświetl szczegóły odpowiedzi i poinformuj usera:
```
Nie udało się utworzyć zagadnienia w Redmine (błąd {status_code}).
Możesz je założyć ręcznie — poniżej dane do wypełnienia formularza.
```
Następnie wyświetl dane jak w "Tryb manualny — Step 13".

Po pomyślnym utworzeniu wyświetl:
```
Utworzono zagadnienie #{new_issue_id}: CR BUG: {ISSUE_SUBJECT}
https://redmine.evolpe.net/issues/{new_issue_id}
Przypisano do: {ASSIGNEE_NAME}
```

### Tryb manualny — Step 13

Jeśli nie masz `redmine_request`, wyświetl gotowe dane do ręcznego założenia zagadnienia:

```
Nie mam dostępu do Redmine MCP. Utwórz zagadnienie ręcznie w Redmine
(https://redmine.evolpe.net/projects/{project_identifier}/issues/new):

  Tracker:          Task
  Projekt:          {project_identifier}
  Zagadnienie nadrzędne (parent): #{ISSUE_ID}
  Tytuł:            CR BUG: {ISSUE_SUBJECT}
  Status:           Nowy
  Priorytet:        {priorytet z zagadnienia nadrzędnego z Step 2}
  Przypisane do:    {ASSIGNEE_NAME}
  Kategoria:        Bug

  Opis:
  ---
  Błędy znalezione podczas code review zagadnienia #{ISSUE_ID}.

  Znaleziono: {X} CRITICAL, {Y} WARNING, {Z} INFO

  Raport: .ai/tasks/{ISSUE_ID}/cr.md
  Commit: {commit_hash}

  ### Lista FIXME

  {lista findings CRITICAL, WARNING i INFO z plikiem i opisem}
  ---
```
