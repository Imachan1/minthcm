# Tryb TEST — Naprawa bugów po testach

Używaj tego pliku gdy **TRYB = `test`** — naprawiasz bugi zgłoszone przez testera po testach manualnych lub automatycznych.

Bug opisany jest w subtasku Redmine o nazwie zaczynającej się od `BUG:` — jako dziecko zagadnienia `{ISSUE_ID}`.

---

## Spis treści

- [Step T1 — Pobierz opis buga z Redmine](#step-t1)
- [Step T2 — Przygotuj środowisko (git)](#step-t2)
- [Step T3 — Napraw każdy bug](#step-t3)
- [Step T4 — Commit](#step-t4)
- [Step T5 — Dodaj notatkę do Redmine](#step-t5)
- [Zakończenie](#zakończenie)
- [Tryb manualny (bez Redmine MCP)](#tryb-manualny)

---

## Step T1 — Pobierz opis buga z Redmine

### T1a. Pobierz zagadnienie główne

Wywołaj `redmine_request`:
```
redmine_request: GET /issues/{ISSUE_ID}.json?include=children
```

Jeśli `redmine_request` nie jest dostępne lub zwraca błąd, użyj curl z konfiguracją z `~/.copilot/mcp-config.json`:
```bash
# Odczytaj klucz i URL z konfiguracji MCP
REDMINE_URL=$(python3 -c "import json; c=json.load(open('/root/.copilot/mcp-config.json')); print(c['mcpServers']['redmine']['env']['REDMINE_URL'])")
REDMINE_API_KEY=$(python3 -c "import json; c=json.load(open('/root/.copilot/mcp-config.json')); print(c['mcpServers']['redmine']['env']['REDMINE_API_KEY'])")

curl -s -H "X-Redmine-API-Key: $REDMINE_API_KEY" \
  "$REDMINE_URL/issues/{ISSUE_ID}.json?include=children"
```

Jeśli ani MCP ani curl nie działają — przejdź do sekcji [Tryb manualny](#tryb-manualny).

Zapamiętaj:
- **ISSUE_SUBJECT** — tytuł zagadnienia (do commita)
- **project.identifier** — identyfikator projektu
- **children** — lista subtasków

Jeśli zagadnienie nie istnieje (404) → zatrzymaj się: „Zagadnienie #{ISSUE_ID} nie znalezione w Redmine."

### T1b. Znajdź subtask BUG

Z listy `children` znajdź zagadnienie o nazwie zaczynającej się od **`BUG:`**.

**Może być kilka subtasków BUG** — wypisz je i zapytaj usera który naprawiać:
```
Znaleziono kilka subtasków BUG dla #{ISSUE_ID}:
  #185001 — BUG: Błąd przy zapisie rekordu z pustą datą
  #185002 — BUG: Niepoprawna hierarchia uczestników spotkania

Który subtask mam naprawić? (podaj numer lub "wszystkie")
```

**Jeśli brak subtaska BUG** → poinformuj usera:
```
Nie znaleziono subtaska "BUG:" dla zagadnienia #{ISSUE_ID}.
Czy chcesz:
1. Podać numer subtaska ręcznie
2. Opisać buga bezpośrednio w tej rozmowie
```

Zapamiętaj `BUG_ISSUE_ID` (ID subtaska buga).

### T1c. Pobierz szczegóły subtaska BUG

Użyj tej samej metody co w T1a:

**MCP:**
```
redmine_request: GET /issues/{BUG_ISSUE_ID}.json?include=journals
```

**curl (fallback):**
```bash
curl -s -H "X-Redmine-API-Key: $REDMINE_API_KEY" \
  "$REDMINE_URL/issues/{BUG_ISSUE_ID}.json?include=journals"
```

Zapamiętaj:
- **BUG_SUBJECT** — tytuł subtaska
- **description** — opis buga (kroki, oczekiwane/aktualne zachowanie)
- **journals** — historia notatek (może zawierać dodatkowy kontekst)

### T1d. Sprawdź czy opis jest wystarczający

**Zawsze sprawdź opis subtaska BUG.** Jeśli `description` jest puste, bardzo ogólne (np. „bug w tym module"), lub zawiera tylko metadata bez opisu zachowania — zapytaj usera, **nawet jeśli zagadnienie nadrzędne ma szczegółowy opis**.

Dlaczego to ważne: subtask BUG reprezentuje konkretną regresję z bieżącej rundy testów. Mogą być wskazane tylko wybrane bugi z szerszego opisu nadrzędnego, a user zna kontekst. Nie zakładaj że masz naprawić wszystko z opisu nadrzędnego.

```
Subtask #{BUG_ISSUE_ID} nie ma wystarczającego opisu buga.

Opisz buga który mam naprawić:
1. Kroki do reprodukcji
2. Oczekiwane zachowanie
3. Aktualne (błędne) zachowanie
4. Plik/funkcja gdzie jest problem (jeśli wiesz)
```

Poczekaj na odpowiedź zanim przejdziesz dalej.

---

## Step T2 — Przygotuj środowisko (git)

### T2a. Sprawdź repozytorium

```bash
git rev-parse --is-inside-work-tree
```

Jeśli błąd → zatrzymaj się: „Nie jestem wewnątrz repozytorium git."

### T2b. Sprawdź working tree

```bash
git status --porcelain
```

Jeśli są niezacommitowane zmiany → poinformuj usera i poczekaj na potwierdzenie.

### T2c. Checkout branch

```bash
git branch -a | grep "feature/{ISSUE_ID}"
```

Jeśli branch istnieje lokalnie → `git checkout feature/{ISSUE_ID}`
Jeśli tylko zdalnie → `git checkout -b feature/{ISSUE_ID} origin/feature/{ISSUE_ID}`
Jeśli brak → zatrzymaj się: „Nie znaleziono brancha feature/{ISSUE_ID}."

---

## Step T3 — Napraw każdy bug

Jeśli subtask opisuje kilka bugów — naprawiaj je kolejno. Numeruj dla czytelności.

### T3a. Dla każdego buga:

**1. Zrozum problem**

Przeanalizuj opis buga, kroki reprodukcji, oczekiwane vs. aktualne zachowanie. Przeszukaj kod żeby znaleźć miejsce problemu.

Jeśli problem nie jest jasny → **zapytaj usera**:
```
Szukam miejsca buga "{opis}".
Przeszukałem kod i znalazłem {X} potencjalnych miejsc:
  - {plik1}: {opis dlaczego to może być miejsce problemu}
  - {plik2}: {opis}

W którym miejscu jest problem?
```

**2. Oceń podejście do naprawy**

Jeśli rozwiązanie jest jednoznaczne → implementuj bezpośrednio.

Jeśli naprawa wymaga decyzji projektowej, ma skutki uboczne lub jest kilka podejść → **zapytaj usera przed implementacją**:
```
Bug: {opis}
Miejsce: {plik:linia}

Mam dwa podejścia do naprawy:
A) {opis podejścia A} — {zalety/wady}
B) {opis podejścia B} — {zalety/wady}

Które preferujesz?
```

**3. Zastosuj odpowiedni skill**

Jeśli w Kroku 1 (SKILL.md) wybrałeś `minthcm-project` lub inny skill — stosuj jego zasady podczas tej naprawy.

**4. Zaimplementuj naprawę**

Edytuj pliki. Upewnij się że naprawa nie wprowadza nowych problemów.

**5. Poinformuj o postępie**

```
✅ Bug naprawiony: {opis buga} [{plik:linia}]
```

### T3b. Uwagi

- **Minimalne zmiany** — naprawiaj konkretny bug, nie przeprowadzaj generalnego refaktoringu
- **Pytaj gdy wątpliwości** — lepiej zapytać raz niż naprawić źle
- **Wiele plików** — jeśli naprawa wymaga zmian w kilku miejscach (np. PHP + Vue), zmień wszystkie

---

## Step T4 — Commit

### T4a. Podsumowanie i pytanie o commit

```
Naprawa bugów zakończona dla #{ISSUE_ID} (subtask #{BUG_ISSUE_ID}).

Naprawiono ({N} bugów):
  ✅ {opis buga 1} [{plik1}]
  ✅ {opis buga 2} [{plik2}]

Pliki zmienione:
  - {plik1}
  - {plik2}

Czy mogę zacommitować te zmiany?
```

### T4b. Wykonaj commit

```bash
git add {zmienione_pliki}
git commit -m "ref #{ISSUE_ID} {ISSUE_SUBJECT}"
```

Wyświetl hash commita. Zapytaj czy chce push:

```bash
git push origin feature/{ISSUE_ID}
```

---

## Step T5 — Dodaj notatkę do Redmine

Po commicie dodaj notatkę do **subtaska BUG** (`{BUG_ISSUE_ID}`) z podsumowaniem naprawionych rzeczy.

**Zawsze zapytaj usera o zgodę:**
```
Czy mam dodać notatkę podsumowującą naprawę do zagadnienia #{BUG_ISSUE_ID}?
```

Po potwierdzeniu użyj MCP lub curl:

**MCP:**
```
redmine_request: PUT /issues/{BUG_ISSUE_ID}.json
body:
{
  "issue": {
    "notes": "Naprawiono:\n\n{lista naprawionych bugów z opisem i plikami}\n\nCommit: {commit_hash}\nBranch: feature/{ISSUE_ID}"
  }
}
```

**curl (fallback):**
```bash
curl -s -X PUT \
  -H "X-Redmine-API-Key: $REDMINE_API_KEY" \
  -H "Content-Type: application/json" \
  -d "{\"issue\":{\"notes\":\"Naprawiono:\n\n{lista}\n\nCommit: {commit_hash}\nBranch: feature/{ISSUE_ID}\"}}" \
  "$REDMINE_URL/issues/{BUG_ISSUE_ID}.json"
```

Format notatki:
```
Naprawiono:

* {opis buga 1}
  Plik: {plik1}
  Zmiana: {krótki opis co zostało zmienione}

* {opis buga 2}
  Plik: {plik2}
  Zmiana: {krótki opis}

Commit: {commit_hash}
Branch: feature/{ISSUE_ID}
```

Po dodaniu notatki wyświetl potwierdzenie:
```
Notatka dodana do zagadnienia #{BUG_ISSUE_ID}.
https://redmine.evolpe.net/issues/{BUG_ISSUE_ID}
```

---

## Zakończenie

```
✅ Bug-fix TEST zakończony dla #{ISSUE_ID}.

Subtask BUG: #{BUG_ISSUE_ID} — {BUG_SUBJECT}
Naprawiono: {N} bugów
Commit: {commit_hash}
Notatka: dodana do #{BUG_ISSUE_ID}

Kolejny krok: zgłoś do testera że naprawa jest gotowa do weryfikacji.
```

---

## Tryb manualny (bez Redmine MCP i bez zmiennych env)

Użyj tego trybu **tylko jeśli** ani `redmine_request` MCP ani `REDMINE_URL`/`REDMINE_API_KEY` zmienne środowiskowe nie są dostępne.

### T1a — manualny
Wyświetl:
```
Nie mam dostępu do Redmine MCP.
Proszę podaj:
1. Numer subtaska BUG (zagadnienie potomne #{ISSUE_ID} zaczynające się od "BUG:")
2. Opis buga: kroki reprodukcji, oczekiwane i aktualne zachowanie
3. Tytuł zagadnienia #{ISSUE_ID} (do wiadomości commita)
```

### T5 — manualny (notatka)
Jeśli po naprawieniu nie masz ani MCP ani env vars → wyświetl gotowy tekst notatki:
```
Nie mam dostępu do Redmine MCP. Dodaj ręcznie notatkę do zagadnienia #{BUG_ISSUE_ID}:

---
{treść notatki}
---
```
