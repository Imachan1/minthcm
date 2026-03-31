# Code Review: #200002 — Dodanie API zarządzania użytkownikami

**Data:** 2026-03-20
**Branch:** feature/200002
**Zakres:** 2 commit(ów): HEAD~2..HEAD
**Reviewer:** AI (Claude Code)
**Redmine:** https://redmine.evolpe.net/issues/200002

## Kontekst zagadnienia

REST API do zarządzania użytkownikami. Kryteria: GET /users, GET /users/search?query=..., GET /users/{id}, POST /users, DELETE /users/{id}. Wszystkie endpointy wymagają autoryzacji. Dane wejściowe walidowane i sanityzowane.

## Zmienione pliki

- api/users.php (+45/-2)

## Znalezione problemy

### CRITICAL

- [ ] `api/users.php:19` — **[Bezpieczeństwo] SQL Injection w searchUsers** — zapytanie budowane przez konkatenację z `$request['query']` → użyj prepared statements z parametrami LIKE [confidence: 99]
- [ ] `api/users.php:26` — **[Bezpieczeństwo] SQL Injection w getUserById** — `$id` z requestu wstrzykiwane bezpośrednio do zapytania → użyj prepared statement z parametrem [confidence: 99]
- [ ] `api/users.php:32` — **[Bezpieczeństwo] SQL Injection w createUser** — `$name` i `$email` z requestu konkatenowane do INSERT → użyj prepared statement [confidence: 99]
- [ ] `api/users.php:39` — **[Bezpieczeństwo] SQL Injection w deleteUser** — `$id` wstrzykiwany do DELETE bez sanityzacji → użyj prepared statement [confidence: 99]
- [ ] Brak autoryzacji — żaden z endpointów nie weryfikuje tożsamości wywołującego → dodaj middleware autoryzacji lub sprawdzenie sesji przed każdą operacją [confidence: 95]

### WARNING

(brak)

### INFO

(brak)

## Podsumowanie

Poważne błędy bezpieczeństwa — wszystkie 4 metody modyfikujące/odczytujące dane są podatne na SQL injection. Brak jakiegokolwiek mechanizmu autoryzacji. Kod nie nadaje się do merge w obecnym stanie.

**Werdykt:** CHANGES REQUESTED
