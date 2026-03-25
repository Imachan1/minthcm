# Plan: #300002 — Implementacja UserService

## Kroki implementacji

| # | Krok | Plik | Status |
|---|------|------|--------|
| 1 | Dodaj metodę getUsers() zwracającą wszystkich użytkowników | src/UserService.php | |
| 2 | Dodaj metodę getUserById($id) zwracającą jednego użytkownika | src/UserService.php | |
| 3 | Dodaj metodę createUser($name, $email) | src/UserService.php | |
| 4 | Dodaj metodę deleteUser($id) | src/UserService.php | |

## Kryteria akceptacji

- Wszystkie metody używają prepared statements (PDO)
- Wejście użytkownika zawsze walidowane i sanityzowane
- Brak SELECT * — pobieramy tylko potrzebne kolumny
