#!/bin/bash
# setup_fixtures.sh — tworzy testowe repozytoria git dla ewaluacji skilla code-review
# Uruchom: bash evals/setup_fixtures.sh (z katalogu skills/common/code-review)

set -e

SKILL_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
FIXTURES_BASE="$SKILL_ROOT/evals/fixture-repos"

echo "==> Czyszczenie i tworzenie fixtures w $FIXTURES_BASE..."
rm -rf "$FIXTURES_BASE"
mkdir -p "$FIXTURES_BASE"

# ============================================================
# Pomocnicze: inicjalizacja bare remote + working repo
# ============================================================
init_repo() {
    local ISSUE_ID="$1"
    local REMOTE="$FIXTURES_BASE/${ISSUE_ID}-origin.git"
    local REPO="$FIXTURES_BASE/${ISSUE_ID}"

    # Bare remote (symuluje serwer git)
    git init --bare "$REMOTE" -q

    mkdir -p "$REPO"
    cd "$REPO"
    git init -q
    git config user.email "ci@evolpe.pl"
    git config user.name "CI Bot"
    git remote add origin "$REMOTE"
}

# ============================================================
# Fixture 200001: Mały diff (<20 linii) — ścieżka sekwencyjna
# Off-by-one bug w kalkulatorze cen koszyka
# ============================================================
create_fixture_200001() {
    echo ""
    echo "--- Fixture 200001: mały diff, ścieżka sekwencyjna ---"
    local REPO="$FIXTURES_BASE/200001"

    init_repo 200001

    git checkout -b develop -q
    mkdir -p src

    cat > src/cart.php << 'EOF'
<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    return 0;
}
EOF

    git add .
    git commit -m "Initial project setup" -q

    git push -u origin develop -q

    git checkout -b feature/200001 -q

    cat > src/cart.php << 'EOF'
<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    $total = 0;
    for ($i = 0; $i <= count($items); $i++) {
        $total += $items[$i]['price'];
    }
    return $total;
}

function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}
EOF

    git add .
    git commit -m "ref #200001 Dodanie kalkulatora cen dla koszyka zakupowego" -q

    git push -u origin feature/200001 -q

    # Ustaw origin/HEAD na develop
    cd "$FIXTURES_BASE/200001-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200002: Średni diff (>=20 linii) — ścieżka równoległa
# SQL injection + brak autoryzacji w User API
# ============================================================
create_fixture_200002() {
    echo ""
    echo "--- Fixture 200002: średni diff, SQL injection ---"
    local REPO="$FIXTURES_BASE/200002"

    init_repo 200002

    git checkout -b develop -q
    mkdir -p api

    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
}
EOF

    git add .
    git commit -m "Initial project setup" -q

    git push -u origin develop -q

    git checkout -b feature/200002 -q

    # Commit 1: Basic user listing (czysty kod)
    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
EOF

    git add .
    git commit -m "ref #200002 Dodanie endpointu listy uzytkownikow" -q

    # Commit 2: Wyszukiwanie i zarządzanie — SQL injection!
    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($request) {
        $search = $request['query'];
        $query = "SELECT * FROM users WHERE name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = " . $id;
        $result = $this->db->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($request) {
        $name = $request['name'];
        $email = $request['email'];
        $query = "INSERT INTO users (name, email) VALUES ('" . $name . "', '" . $email . "')";
        $this->db->query($query);
        return ['status' => 'created'];
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = " . $id);
        return ['status' => 'deleted'];
    }
}
EOF

    git add .
    git commit -m "ref #200002 Dodanie wyszukiwania i zarzadzania uzytkownikami" -q

    git push -u origin feature/200002 -q

    cd "$FIXTURES_BASE/200002-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200003: Walidacja trackera — Task (niedozwolony)
# Skill powinien odmówić wykonania CR
# ============================================================
create_fixture_200003() {
    echo ""
    echo "--- Fixture 200003: walidacja trackera Task ---"
    local REPO="$FIXTURES_BASE/200003"

    init_repo 200003

    git checkout -b develop -q
    mkdir -p src

    cat > src/tasks.php << 'EOF'
<?php

class TaskManager {
    public function getTasks() {
        return [];
    }
}
EOF

    git add .
    git commit -m "Initial project setup" -q

    git push -u origin develop -q

    git checkout -b feature/200003 -q

    cat > src/tasks.php << 'EOF'
<?php

class TaskManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTasks($userId) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask($title, $userId) {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, user_id) VALUES (?, ?)");
        $stmt->execute([$title, $userId]);
    }
}
EOF

    git add .
    git commit -m "ref #200003 Implementacja zarzadzania zadaniami" -q

    git push -u origin feature/200003 -q

    cd "$FIXTURES_BASE/200003-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200004: Czysty kod → APPROVED
# Nowa utility function formatPrice — brak bugów
# ============================================================
create_fixture_200004() {
    echo ""
    echo "--- Fixture 200004: czysty kod, APPROVED ---"
    local REPO="$FIXTURES_BASE/200004"

    init_repo 200004

    git checkout -b develop -q
    mkdir -p src

    cat > src/helpers.php << 'EOF'
<?php

/**
 * Helper utilities
 */
EOF

    git add .
    git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200004 -q

    cat > src/helpers.php << 'EOF'
<?php

/**
 * Helper utilities
 */

/**
 * Format price for display.
 *
 * @param float $amount
 * @param string $currency
 * @return string
 */
function formatPrice(float $amount, string $currency = 'PLN'): string {
    if ($amount < 0) {
        return '-' . number_format(abs($amount), 2, ',', ' ') . ' ' . $currency;
    }
    return number_format($amount, 2, ',', ' ') . ' ' . $currency;
}

/**
 * Parse price string to float.
 *
 * @param string $price e.g. "1 234,56 PLN"
 * @return float
 */
function parsePrice(string $price): float {
    $clean = preg_replace('/[^\d,]/', '', $price);
    return (float) str_replace(',', '.', $clean);
}
EOF

    git add .
    git commit -m "ref #200004 Dodano helpery formatowania i parsowania cen" -q
    git push -u origin feature/200004 -q

    cd "$FIXTURES_BASE/200004-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200006: Zły format commitów — brak ref #ISSUE_ID
# Skill powinien zatrzymać się w Step 7a
# ============================================================
create_fixture_200006() {
    echo ""
    echo "--- Fixture 200006: brak ref #ISSUE_ID w commitach ---"
    local REPO="$FIXTURES_BASE/200006"

    init_repo 200006

    git checkout -b develop -q
    mkdir -p config

    cat > config/app.php << 'EOF'
<?php

return [
    'debug' => false,
    'env' => 'production',
    'timezone' => 'Europe/Warsaw',
];
EOF

    git add .
    git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200006 -q

    cat > config/app.php << 'EOF'
<?php

return [
    'debug' => false,
    'env' => 'production',
    'timezone' => 'Europe/Warsaw',
    'locale' => 'pl_PL',
    'currency' => 'PLN',
];
EOF

    git add .
    # Celowo brak "ref #200006" w wiadomości commita
    git commit -m "Poprawka konfiguracji aplikacji — dodano locale i walutę" -q
    git push -u origin feature/200006 -q

    cd "$FIXTURES_BASE/200006-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200001-recr: reCR dla issue #200001
# Dev naprawił off-by-one — APPROVED
# Stan: cr.md z poprzedniego CR + commit CR + fix dewelopera
# ============================================================
create_fixture_200001_recr() {
    echo ""
    echo "--- Fixture 200001-recr: reCR, dev naprawił off-by-one ---"
    local REPO="$FIXTURES_BASE/200001-recr"

    init_repo "200001-recr"

    git checkout -b develop -q
    mkdir -p src

    cat > src/cart.php << 'EOF'
<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    return 0;
}
EOF

    git add .
    git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200001 -q

    # Commit 1: buggy code (off-by-one)
    cat > src/cart.php << 'EOF'
<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    $total = 0;
    for ($i = 0; $i <= count($items); $i++) {
        $total += $items[$i]['price'];
    }
    return $total;
}

function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}
EOF

    git add .
    git commit -m "ref #200001 Dodanie kalkulatora cen dla koszyka zakupowego" -q

    # Commit 2: CR — cr.md + FIXME w cart.php (commit w formacie CR)
    mkdir -p .ai/tasks/200001
    cat > .ai/tasks/200001/cr.md << 'EOF'
# Code Review: #200001 — Dodanie kalkulatora cen dla koszyka zakupowego

**Data:** 2026-03-20
**Branch:** feature/200001
**Zakres:** 1 commit(ów): HEAD~1..HEAD
**Reviewer:** AI (Claude Code)
**Redmine:** https://redmine.evolpe.net/issues/200001

## Kontekst zagadnienia

Dodanie kalkulatora cen dla koszyka zakupowego. Kryteria: calculateTotal oblicza sumę cen wszystkich elementów koszyka, formatPrice formatuje kwotę z 2 miejscami po przecinku.

## Zmienione pliki

- src/cart.php (+12/-1)

## Znalezione problemy

### CRITICAL

(brak)

### WARNING

- [ ] `src/cart.php:8` — **[Błędy logiczne] Off-by-one w pętli** — warunek `$i <= count($items)` powoduje dostęp do indeksu poza tablicą przy ostatniej iteracji (PHP zwróci null, suma będzie błędna lub notice) → zmień na `$i < count($items)`

### INFO

(brak)

## Podsumowanie

Kod implementuje kalkulator cen, ale zawiera błąd off-by-one w pętli for. Funkcja formatPrice działa poprawnie. Wymagana poprawka przed merge.

**Werdykt:** CHANGES REQUESTED
EOF

    # Dodaj FIXME do cart.php
    cat > src/cart.php << 'EOF'
<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    $total = 0;
    // FIXME [CR #200001] Off-by-one: $i <= count($items) powinno być $i < count($items)
    for ($i = 0; $i <= count($items); $i++) {
        $total += $items[$i]['price'];
    }
    return $total;
}

function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}
EOF

    git add .
    git commit -m "ref #200001 Dodanie kalkulatora cen dla koszyka zakupowego #BUG|jan.kowalski" -q

    # Commit 3: dev naprawił off-by-one, usunął FIXME
    cat > src/cart.php << 'EOF'
<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    $total = 0;
    for ($i = 0; $i < count($items); $i++) {
        $total += $items[$i]['price'];
    }
    return $total;
}

function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}
EOF

    git add .
    git commit -m "ref #200001 Poprawka off-by-one w calculateTotal" -q

    git push -u origin feature/200001 -q

    cd "$FIXTURES_BASE/200001-recr-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200002-recr: reCR dla issue #200002
# Dev naprawił 3/5 SQL injections, 1 UNFIXED, 1 z odpowiedzią dev
# Stan: cr.md z poprzedniego CR + commit CR + fix dewelopera
# ============================================================
create_fixture_200002_recr() {
    echo ""
    echo "--- Fixture 200002-recr: reCR, częściowe poprawki + dev response ---"
    local REPO="$FIXTURES_BASE/200002-recr"

    init_repo "200002-recr"

    git checkout -b develop -q
    mkdir -p api

    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
}
EOF

    git add .
    git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200002 -q

    # Commit 1: czysty getUsers
    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
EOF

    git add .
    git commit -m "ref #200002 Dodanie endpointu listy uzytkownikow" -q

    # Commit 2: SQL injection w 4 metodach + brak auth
    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($request) {
        $search = $request['query'];
        $query = "SELECT * FROM users WHERE name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = " . $id;
        $result = $this->db->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($request) {
        $name = $request['name'];
        $email = $request['email'];
        $query = "INSERT INTO users (name, email) VALUES ('" . $name . "', '" . $email . "')";
        $this->db->query($query);
        return ['status' => 'created'];
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = " . $id);
        return ['status' => 'deleted'];
    }
}
EOF

    git add .
    git commit -m "ref #200002 Dodanie wyszukiwania i zarzadzania uzytkownikami" -q

    # Commit 3: CR — cr.md + FIXME w users.php
    mkdir -p .ai/tasks/200002
    cat > .ai/tasks/200002/cr.md << 'EOF'
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
EOF

    # Dodaj FIXME do wszystkich podatnych metod
    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($request) {
        // FIXME [CR #200002] SQL Injection: użyj prepared statement zamiast konkatenacji
        $search = $request['query'];
        $query = "SELECT * FROM users WHERE name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        // FIXME [CR #200002] SQL Injection: użyj prepared statement zamiast konkatenacji
        $query = "SELECT * FROM users WHERE id = " . $id;
        $result = $this->db->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($request) {
        // FIXME [CR #200002] SQL Injection: użyj prepared statement zamiast konkatenacji
        $name = $request['name'];
        $email = $request['email'];
        $query = "INSERT INTO users (name, email) VALUES ('" . $name . "', '" . $email . "')";
        $this->db->query($query);
        return ['status' => 'created'];
    }

    public function deleteUser($id) {
        // FIXME [CR #200002] SQL Injection: użyj prepared statement zamiast konkatenacji
        $this->db->query("DELETE FROM users WHERE id = " . $id);
        return ['status' => 'deleted'];
    }

    // FIXME [CR #200002] Brak autoryzacji: dodaj sprawdzenie sesji/tokenu przed każdą metodą
}
EOF

    git add .
    git commit -m "ref #200002 Dodanie API uzytkownikow #BUG|jan.kowalski" -q

    # Commit 4: dev naprawił searchUsers, getUserById, createUser — zostawił deleteUser i brak auth
    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($request) {
        $search = '%' . $request['query'] . '%';
        $stmt = $this->db->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
        $stmt->execute([$search, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($request) {
        $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$request['name'], $request['email']]);
        return ['status' => 'created'];
    }

    public function deleteUser($id) {
        // FIXME [CR #200002] SQL Injection: użyj prepared statement zamiast konkatenacji
        // To jest wewnętrzna funkcja, wywoływana tylko przez admina z panelu — nie wymaga sanityzacji
        $this->db->query("DELETE FROM users WHERE id = " . $id);
        return ['status' => 'deleted'];
    }

    // FIXME [CR #200002] Brak autoryzacji: dodaj sprawdzenie sesji/tokenu przed każdą metodą
}
EOF

    git add .
    git commit -m "ref #200002 Poprawka SQL injection w searchUsers, getUserById, createUser" -q

    git push -u origin feature/200002 -q

    cd "$FIXTURES_BASE/200002-recr-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

create_fixture_200001
create_fixture_200002
create_fixture_200003
create_fixture_200004
create_fixture_200006
create_fixture_200001_recr
create_fixture_200002_recr

echo ""
echo "==> Wszystkie fixtures gotowe:"
ls -la "$FIXTURES_BASE"
echo ""
echo "Możesz teraz uruchomić ewaluacje."
