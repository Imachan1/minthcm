---
name: planowanie-us
version: 1.0.0
description: Skill do planowania realizacji User Story z Redmine. Używaj gdy user prosi o  "zaplanowanie US", "plan implementacji", "przygotuj plan", "stwórz branch do US", lub podaje numer US do zaplanowania. Pobiera US z Redmine, analizuje codebase, tworzy feature branch i zapisuje plan do .ai/tasks/{ISSUE_ID}/plan.md.
argument-hint: <numer_lub_url_zagadnienia> (np. 184819 lub https://redmine.evolpe.net/issues/184819)
---

# Planowanie realizacji US

Skill przygotowuje grunt pod implementację — analizuje wymagania, eksploruje codebase
i tworzy plan w `.ai/tasks/{ISSUE_ID}/plan.md` służący jako przewodnik przez całą implementację.

---

## Kiedy używać

- User prosi o "zaplanowanie US", "plan do #XXXX", "przygotuj implementację"
- User mówi "zacznijmy od US #184819" lub "co trzeba zrobić do tego US"
- User chce feature branch bez jeszcze pisania kodu

## Kiedy nie używać

- **Implementacja już trwa** — plan powinien już istnieć, nie twórz go w trakcie
- Zagadnienia inne niż US/Epic/Spike — np. Task, Bug (nie planujemy osobno)
- User pyta tylko o treść US bez chęci tworzenia planu i brancha

---

## Wymagania

- Narzędzie **Redmine MCP** (`redmine_request`) — zalecane; bez niego tryb manualny
- Repozytorium **git** (sprawdź `git status`)

### Tryb manualny (bez Redmine MCP)

Jeśli MCP niedostępne:
- **Step 2** — poproś usera o wklejenie treści US z Redmine
- **Step 8** — pomiń commit planu do Redmine, wyświetl plan gotowy do skopiowania

---

## Przebieg (8 kroków)

### Step 1 — Parsuj numer zagadnienia

Wyciągnij `ISSUE_ID` z `$ARGUMENTS` (slash cmd) lub z kontekstu rozmowy.
- Liczba (np. `184819`) → użyj bezpośrednio
- URL (np. `https://redmine.evolpe.net/issues/184819`) → wyciągnij numer z path
- Brak → zapytaj: „Podaj numer User Story do zaplanowania."

### Step 2 — Pobierz US z Redmine

Pobierz:
- Tytuł, opis, kryteria akceptacji (jako fragment opisu)
- Tracker (waliduj: tylko 18=US, 19=US Bug, 22=Epic, 23=Spike)
- Sprint (`fixed_version_id`), projekt, assignee
- Istniejące Taski (dzieci zagadnienia)

**Tryb manualny:** poproś usera o wklejenie treści US.

### Step 3 — Walidacja trackera

Jeśli tracker to Task (24) lub inny nieplanistyczny — zatrzymaj się:
> „Zagadnienie #{ISSUE_ID} to {tracker}, nie US. Planowanie dotyczy US/Epic/Spike."

Dozwolone tracker ID: 18, 19, 22, 23.

### Step 4 — Zbadaj stan repozytorium

```bash
git fetch --all
git status
git branch -a | grep "{ISSUE_ID}"
```

Sprawdź czy feature branch już istnieje. Jeśli tak — poinformuj usera i zapytaj
czy kontynuować (może to re-planowanie po zmianie zakresu).

### Step 5 — Eksploruj codebase pod kątem US

Na podstawie treści US zidentyfikuj obszary kodu do zmiany:
- Jakie moduły/pliki są prawdopodobnie dotknięte?
- Czy istnieją podobne implementacje do wzorowania?
- Jakie zależności mogą być relevantne?

Używaj `Glob`, `Grep`, `Read` — nie modyfikuj nic na tym etapie.

### Step 6 — Stwórz feature branch

```bash
git checkout master          # bazowy branch wg CLAUDE.md → sekcja "Git Workflow"
git pull
git checkout -b feature/{ISSUE_ID}
```

Jeśli branch już istnieje → przełącz się na niego: `git checkout feature/{ISSUE_ID}`

### Step 7 — Napisz plan

Utwórz katalog i plik planu:

```bash
mkdir -p .ai/tasks/{ISSUE_ID}
```

Ścieżka pliku: `.ai/tasks/{ISSUE_ID}/plan.md`

```markdown
# Plan: #{ISSUE_ID} — {tytuł US}

**Redmine:** https://redmine.evolpe.net/issues/{ISSUE_ID}
**Sprint:** {sprint}
**Data planu:** {YYYY-MM-DD}

## Zrozumienie wymagań

{Parafraza tego co US wymaga — własnymi słowami, nie kopiuj opisu 1:1.
 Pokaż że rozumiesz intencję, nie tylko literę wymagań.}

## Kryteria akceptacji

{Lista z US — przepisz lub uzupełnij jeśli niejasne}

## Podejście implementacyjne

{Opis techniczny jak zrealizować wymagania — architektura, wzorce, narzędzia.
 Odwołaj się do konkretnych miejsc w kodzie znalezionych w Step 5.}

## Pliki do modyfikacji / stworzenia

| Plik | Akcja | Opis zmiany |
|------|-------|-------------|
| ... | CREATE/MODIFY/DELETE | ... |

## Plan implementacji

- [ ] Krok 1: ...
- [ ] Krok 2: ...
- [ ] Krok 3: ...

## Ryzyka i uwagi

{Potencjalne problemy, zależności od innych US, skutki uboczne zmian.
 Pomiń sekcję jeśli brak ryzyk.}

## Otwarte pytania

{Pytania wymagające wyjaśnienia przed lub podczas implementacji.
 Pomiń sekcję jeśli wszystko jasne.}
```

### Step 8 — Podsumowanie i commit planu

Wyświetl podsumowanie:

```
Plan dla #{ISSUE_ID} gotowy.

Branch:  feature/{ISSUE_ID}
Plan:    .ai/tasks/{ISSUE_ID}/plan.md
Kroków:  {N}
Ryzyka:  {lista jeśli są, "brak" jeśli nie ma}

Czy mam zacommitować plan?
```

Po potwierdzeniu usera:

```bash
git add .ai/tasks/{ISSUE_ID}/plan.md
git commit -m "plan - ref #{ISSUE_ID}"
```

Wyświetl hash commita. Nie pushuj bez pytania.

---

## Routing do references

| Kiedy | Czytaj |
|---|---|
| Steps 2, 3 — Redmine, trackery, statusy, custom fields | skill `redmine-guide` |
| Step 5 — eksploracja kodu MintHCM (projekt kliencki) | skill `minthcm-project` |
| Step 5 — eksploracja kodu MintHCM (core/framework) | skill `minthcm-core` |

---

## Oczekiwany sposób pracy

- **Eksploruj zanim napiszesz plan** — nie zgaduj struktury kodu, użyj Step 5
- **Parafrazuj wymagania** — nie kopiuj opisu US, pokaż że rozumiesz intencję
- **Pytaj o niejasności** — otwarte pytania w planie lepsze niż milczące założenia
- **Nie commituj bez zgody** — zawsze czekaj na potwierdzenie przed Step 8 commitem
- **Jeden commit** — tylko `plan.md`, bez żadnego kodu
- Po zakończeniu user przechodzi do implementacji korzystając z `plan.md` jako checklisty
