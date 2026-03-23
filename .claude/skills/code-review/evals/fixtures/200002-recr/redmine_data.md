# Dane Redmine — Zagadnienie #200002 (reCR)

**ID:** 200002
**Tytuł:** Dodanie API zarządzania użytkownikami
**Tracker:** User Story
**Tracker ID:** 18
**Projekt:** test-api
**Status:** W toku
**Priorytet:** Wysoki
**Opis:**
Jako administrator chcę zarządzać użytkownikami przez REST API.

Kryteria akceptacji:
- GET /users — lista użytkowników
- GET /users/search?query=... — wyszukiwanie
- GET /users/{id} — szczegóły użytkownika
- POST /users — tworzenie użytkownika
- DELETE /users/{id} — usuwanie użytkownika
- Wszystkie endpointy wymagają autoryzacji
- Dane wejściowe walidowane i sanityzowane

**Parent issue:** brak

---
*To jest fixture do reCR — branch zawiera commit CR (`#BUG|jan.kowalski`) oraz commit dewelopera z częściowymi poprawkami (3/5 SQL injection naprawione). deleteUser ma odpowiedź dewelopera przy FIXME. Brak autoryzacji nadal nienaprawiony.*
