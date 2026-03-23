---
name: bug-fix
version: 1.1.0
description: >
  Skill do naprawy bugów w kodzie na feature branchu. Obsługuje dwa tryby:
  "cr" — naprawa bugów znalezionych przez skill code-review (FIXME komentarze
  i niezaznaczone checkboxy w raporcie cr.md) oraz "test" — naprawa bugów
  zgłoszonych po testach (subtask "BUG:" w Redmine). Używaj zawsze gdy user
  mówi "napraw buga", "fix bugi z CR", "popraw błędy po testach",
  "zrób bug-fix", "popraw FIXME", "popraw co wyszło z CR",
  "napraw co tester zgłosił", lub podaje numer zagadnienia Redmine z prośbą
  o naprawę czegoś wynikłego z code review lub testów — nawet jeśli nie
  padło słowo "bug-fix". Numer issue + wzmianka o CR, FIXME lub testach
  wystarczą jako trigger.
argument-hint: <numer_zagadnienia> <tryb: cr|test>
---

# Bug Fix

Skill do naprawy bugów w kodzie — po code review lub po testach manualnych.

---

## Kiedy używać

- User prosi o naprawę bugów z code review: „napraw bugi z CR #184819", „fix FIXME", „popraw błędy z review"
- User prosi o naprawę bugów z testów: „napraw bugi z testów #184529", „fix test bug #185000"
- User podaje numer zagadnienia i mówi „napraw", „popraw", „fix" w kontekście CR lub testów
- Kolejne rundy naprawy po ponownym re-CR (CHANGES REQUESTED po re-review)

## Kiedy nie używać

- Implementacja nowych funkcji → użyj skilla `implementation`
- Przeprowadzanie code review → użyj skilla `code-review`
- Planowanie US → użyj skilla `plan-us`
- Naprawa buga niezwiązana z zagadnieniem Redmine ani z feature branchem

---

## Wymagania

- Repozytorium **git** z feature branchem `feature/{ISSUE_ID}`
- **Tryb `cr`**: plik `.ai/tasks/{ISSUE_ID}/cr.md` z raportem code review (wygenerowany przez skill `code-review`)
- **Tryb `test`**: narzędzie **Redmine MCP** (`redmine_request`) — preferowane; jeśli niedostępne, wystarczą zmienne środowiskowe `REDMINE_URL` i `REDMINE_API_KEY` (curl fallback); bez żadnego z nich — tryb manualny
- Skille systemowe (opcjonalne): `minthcm-project`, `coding-standards` — czytaj ich SKILL.md jeśli są dostępne w kontekście

---

## Krok 0 — Parsuj argumenty

Z `$ARGUMENTS` (slash command) lub z kontekstu rozmowy wyciągnij:

1. **ISSUE_ID** — numer zagadnienia (np. `184819`)
2. **TRYB** — `cr` lub `test`

Kolejność szukania w `$ARGUMENTS`: `<liczba> <cr|test>` lub `<cr|test> <liczba>`.

**Jeśli brakuje ISSUE_ID** → zapytaj: „Podaj numer zagadnienia Redmine."

**Jeśli brakuje TRYB** → zapytaj:
```
Który tryb naprawy?
- cr  — naprawa bugów znalezionych przez code review (raport cr.md + FIXME komentarze)
- test — naprawa bugów zgłoszonych po testach (subtask BUG: w Redmine)
```

---

## Krok 1 — Skille systemowe

Przed rozpoczęciem naprawy przejrzyj skille dostępne w kontekście rozmowy. Zdecyduj które są trafne dla projektu i rodzaju zmian:

| Skill | Kiedy użyć |
|---|---|
| `minthcm-project` | Projekt MintHCM — customizacje PHP/Vue/API w `custom/` |
| `coding-standards` | Bugi dotyczące nazewnictwa, struktury, stylu kodu |

Jeśli wybrałeś skill — **odczytaj jego `SKILL.md`** narzędziem Read i stosuj jego zasady podczas wszystkich napraw w tej sesji. Wypisz podsumowanie:

```
📋 Użyte skille do naprawy:
| Skill             | Powód wyboru                     |
|-------------------|----------------------------------|
| minthcm-project   | Projekt MintHCM, customizacje PHP |
```

---

## Routing

| Tryb     | Plik referencyjny               |
|----------|---------------------------------|
| `cr`     | `references/tryb-cr.md`         |
| `test`   | `references/tryb-test.md`       |

Po ustaleniu trybu — **wczytaj odpowiedni plik referencyjny i postępuj zgodnie z opisanym tam procesem**.

---

## Zasady ogólne (obowiązują w obu trybach)

- **Pytaj gdy nie jesteś pewien** — jeśli poprawka wymaga decyzji projektowej lub logika buga jest niejasna, zatrzymaj się i zapytaj usera przed wprowadzeniem zmiany
- **Jeden bug na raz** — naprawiaj, weryfikuj, zaznaczaj jako zrobione, dopiero potem kolejny
- **Nie commituj bez zgody** — zawsze czekaj na potwierdzenie usera przed każdym commitem
- **Nie twórz nowych zagadnień w Redmine** — to robi skill `code-review` podczas re-CR; Twój zakres to tylko naprawa kodu i (w trybie `test`) notatka do subtaska
- **Commit nie zawiera `#BUG|`** — ten sufiks jest zarezerwowany dla commitów CR skilla `code-review`; Twój commit jest czystym commitem feature
