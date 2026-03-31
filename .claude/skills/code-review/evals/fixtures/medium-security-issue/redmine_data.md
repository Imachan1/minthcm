# Dane Redmine — zagadnienie #200002

Używaj tych danych gdy skill pyta o dane zagadnienia (tryb manualny):

- **Tytuł (subject):** Dodanie API zarządzania użytkownikami
- **Tracker:** User Story
- **Tracker ID:** 18
- **Projekt:** test-api
- **Status:** W toku
- **Priorytet:** Wysoki
- **Priorytet ID:** 3

**Opis / kryteria akceptacji:**
```
Jako administrator chcę zarządzać użytkownikami przez REST API.

Kryteria akceptacji:
- Endpoint GET /users zwraca listę użytkowników
- Endpoint GET /users/search?query=... umożliwia wyszukiwanie po imieniu i emailu
- Endpoint GET /users/{id} zwraca dane pojedynczego użytkownika
- Endpoint POST /users tworzy nowego użytkownika (wymagane pola: name, email)
- Endpoint DELETE /users/{id} usuwa użytkownika
- Wszystkie endpointy wymagają autoryzacji
- Dane wejściowe są walidowane i sanityzowane
```

- **ID zagadnienia nadrzędnego (parent):** brak
