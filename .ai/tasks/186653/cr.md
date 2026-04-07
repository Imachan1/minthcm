# Code Review: #186653 — [SEC] SQL Injection w Schedulers

**Data:** 2026-04-02
**Branch:** feature/186653
**Zakres:** 1 commit: 8c8eb930ed~1..8c8eb930ed
**Reviewer:** AI (Claude Code)
**Redmine:** https://redmine.evolpe.net/issues/186653

## Kontekst zagadnienia

Naprawa podatności SQL Injection w module Schedulers. Zastąpienie niebezpiecznego `implode("','", ...)` na `$db->implodeQuoted()` z DBManagerFactory.

## Zmienione pliki

- `api/app/Repositories/SchedulerRepository.php` — +13 / -3

## Znalezione problemy

### CRITICAL

- [x] `api/app/Repositories/SchedulerRepository.php:135` — **[Architektura] Użycie legacy DBManagerFactory w warstwie API** — `DBManagerFactory::getInstance()` sięga do legacy SuiteCRM, łamiąc separację warstw. Repozytorium już ma wstrzyknięte `$this->connection` (Doctrine DBAL `Connection`), które oferuje `quote()`. Ten sam problem na liniach 178 i 223. → Zamień `$db->implodeQuoted($users)` na `implode(',', array_map(fn($id) => $this->connection->quote($id), $users))` i usuń `use DBManagerFactory`.

### WARNING

- [x] `api/app/Repositories/SchedulerRepository.php:133` — **[Logika] Zmiana semantyki dla pustej tablicy uczestników** — Dodanie `if (!empty($users))` zmienia zachowanie: gdy `$participants` jest tablicą, ale po filtracji `$users` jest pusta, stary kod generował `AND users.id IN ('')` (zwracał 0 rekordów), nowy kod zostawia `$idsSQL = ''` (brak filtra = zwraca WSZYSTKICH). Scenariusz: scheduler z uczestnikami typu Candidates — metoda `getUsersQuery` zwróci wszystkich pracowników zamiast żadnego. Ten sam problem dotyczy `getCandidatesQuery` (linia 176) i `getResourcesQuery` (linia 220). → Dodaj obsługę pustej tablicy, np. `$idsSQL = "AND 1=0"` gdy tablica jest pusta po filtracji, aby zachować oryginalne zachowanie (brak wyników).

### INFO

*(brak)*

## Podsumowanie

Fix SQL Injection jest poprawny merytorycznie — escapowanie wartości eliminuje podatność. Dwa problemy do naprawy: (1) dodanie `!empty()` guard zmienia zachowanie w edge-case'ie pustej tablicy (zwraca wszystkich zamiast nikogo), (2) użycie legacy `DBManagerFactory` w warstwie API łamie separację warstw — repozytorium ma już wstrzyknięte Doctrine DBAL `Connection` z metodą `quote()`.

**Werdykt:** CHANGES REQUESTED
