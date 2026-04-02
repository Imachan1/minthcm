#!/bin/bash
# setup_fixtures.sh — tworzy testowe repozytoria git dla ewaluacji skilla code-review
# Uruchom: bash evals/setup_fixtures.sh (z katalogu skills/common/code-review)
#
# Dane fixture (kod źródłowy, redmine_data.md) są commitowane w evals/fixtures/.
# Ten skrypt tworzy na ich podstawie repozytoria git w evals/fixture-repos/.

set -e

SKILL_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
FIXTURES_DIR="$SKILL_ROOT/evals/fixtures"
FIXTURES_BASE="$SKILL_ROOT/evals/fixture-repos"

echo "==> Czyszczenie i tworzenie fixture-repos w $FIXTURES_BASE..."
rm -rf "$FIXTURES_BASE"
mkdir -p "$FIXTURES_BASE"

# ============================================================
# Pomocnicze: inicjalizacja bare remote + working repo
# ============================================================
init_repo() {
    local ISSUE_ID="$1"
    local REMOTE="$FIXTURES_BASE/${ISSUE_ID}-origin.git"
    local REPO="$FIXTURES_BASE/${ISSUE_ID}"

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
    printf '<?php\nfunction calculateTotal($items) { return 0; }\n' > src/cart.php
    git add . && git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200001 -q
    mkdir -p src
    cp "$FIXTURES_DIR/basic-small-diff/src/cart.php" src/cart.php
    git add . && git commit -m "ref #200001 Dodanie kalkulatora cen dla koszyka zakupowego" -q
    git push -u origin feature/200001 -q

    cd "$FIXTURES_BASE/200001-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200002: Średni diff (>=80 linii) — ścieżka równoległa
# SQL injection + brak autoryzacji w User API
# ============================================================
create_fixture_200002() {
    echo ""
    echo "--- Fixture 200002: średni diff, SQL injection ---"
    local REPO="$FIXTURES_BASE/200002"

    init_repo 200002

    git checkout -b develop -q
    mkdir -p api
    printf '<?php\nclass UserApiController {\n    private $db;\n    public function __construct($db) { $this->db = $db; }\n}\n' > api/users.php
    git add . && git commit -m "Initial project setup" -q
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
    git add . && git commit -m "ref #200002 Dodanie endpointu listy uzytkownikow" -q

    # Commit 2: SQL injection w 4 metodach + brak auth (z fixture)
    cp "$FIXTURES_DIR/medium-security-issue/api/users.php" api/users.php
    git add . && git commit -m "ref #200002 Dodanie wyszukiwania i zarzadzania uzytkownikami" -q
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
    printf '<?php\nclass TaskManager {\n    public function getTasks() { return []; }\n}\n' > src/tasks.php
    git add . && git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200003 -q
    mkdir -p src
    cp "$FIXTURES_DIR/tracker-validation/src/tasks.php" src/tasks.php
    git add . && git commit -m "ref #200003 Implementacja zarzadzania zadaniami" -q
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
    printf '<?php\n// Helper utilities\n' > src/helpers.php
    git add . && git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200004 -q
    mkdir -p src
    cp "$FIXTURES_DIR/approved-clean-code/src/helpers.php" src/helpers.php
    git add . && git commit -m "ref #200004 Dodano helpery formatowania i parsowania cen" -q
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
    printf "<?php\nreturn [\n    'debug' => false,\n    'env' => 'production',\n];\n" > config/app.php
    git add . && git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200006 -q
    mkdir -p config
    cp "$FIXTURES_DIR/invalid-commit-format/config/app.php" config/app.php
    # Celowo brak "ref #200006" w wiadomości commita
    git add . && git commit -m "Poprawka konfiguracji aplikacji — dodano locale i walutę" -q
    git push -u origin feature/200006 -q

    cd "$FIXTURES_BASE/200006-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200001-recr: reCR dla issue #200001
# Dev naprawił off-by-one — APPROVED
# ============================================================
create_fixture_200001_recr() {
    echo ""
    echo "--- Fixture 200001-recr: reCR, dev naprawił off-by-one ---"
    local REPO="$FIXTURES_BASE/200001-recr"

    init_repo "200001-recr"

    git checkout -b develop -q
    mkdir -p src
    printf '<?php\nfunction calculateTotal($items) { return 0; }\n' > src/cart.php
    git add . && git commit -m "Initial project setup" -q
    git push -u origin develop -q

    git checkout -b feature/200001 -q

    # Commit 1: buggy code (off-by-one) — stan przed CR
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
    git add . && git commit -m "ref #200001 Dodanie kalkulatora cen dla koszyka zakupowego" -q

    # Commit 2: CR — cr.md + FIXME w cart.php
    mkdir -p .ai/tasks/200001
    cp "$FIXTURES_DIR/recr-fixes-approved/.ai/tasks/200001/cr.md" .ai/tasks/200001/cr.md
    cat > src/cart.php << 'EOF'
<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    $total = 0;
    // FIXME - AI CR - Off-by-one: $i <= count($items) powinno być $i < count($items)
    for ($i = 0; $i <= count($items); $i++) {
        $total += $items[$i]['price'];
    }
    return $total;
}

function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}
EOF
    git add . && git commit -m "ref #200001 Dodanie kalkulatora cen dla koszyka zakupowego #BUG|jan.kowalski" -q

    # Commit 3: dev naprawił off-by-one (z fixture)
    cp "$FIXTURES_DIR/recr-fixes-approved/src/cart.php" src/cart.php
    git add . && git commit -m "ref #200001 Poprawka off-by-one w calculateTotal" -q
    git push -u origin feature/200001 -q

    cd "$FIXTURES_BASE/200001-recr-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 200002-recr: reCR dla issue #200002
# Dev naprawił 3/5 SQL injections, 1 UNFIXED, 1 z odpowiedzią dev
# ============================================================
create_fixture_200002_recr() {
    echo ""
    echo "--- Fixture 200002-recr: reCR, częściowe poprawki + dev response ---"
    local REPO="$FIXTURES_BASE/200002-recr"

    init_repo "200002-recr"

    git checkout -b develop -q
    mkdir -p api
    printf '<?php\nclass UserApiController {\n    private $db;\n    public function __construct($db) { $this->db = $db; }\n}\n' > api/users.php
    git add . && git commit -m "Initial project setup" -q
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
    git add . && git commit -m "ref #200002 Dodanie endpointu listy uzytkownikow" -q

    # Commit 2: SQL injection w 4 metodach + brak auth
    cp "$FIXTURES_DIR/medium-security-issue/api/users.php" api/users.php
    git add . && git commit -m "ref #200002 Dodanie wyszukiwania i zarzadzania uzytkownikami" -q

    # Commit 3: CR — cr.md + FIXME w users.php
    mkdir -p .ai/tasks/200002
    cp "$FIXTURES_DIR/recr-unfixed-bugs/.ai/tasks/200002/cr.md" .ai/tasks/200002/cr.md
    cat > api/users.php << 'EOF'
<?php

/**
 * User API Controller
 * Handles CRUD operations for the users resource
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($request) {
        // FIXME - AI CR - SQL Injection: użyj prepared statement zamiast konkatenacji
        $search = $request['query'];
        $query = "SELECT * FROM users WHERE name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        // FIXME - AI CR - SQL Injection: użyj prepared statement zamiast konkatenacji
        $query = "SELECT * FROM users WHERE id = " . $id;
        $result = $this->db->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($request) {
        // FIXME - AI CR - SQL Injection: użyj prepared statement zamiast konkatenacji
        $name = $request['name'];
        $email = $request['email'];
        $query = "INSERT INTO users (name, email) VALUES ('" . $name . "', '" . $email . "')";
        $this->db->query($query);
        return ['status' => 'created'];
    }

    public function updateUserEmail($id, $email) {
        $stmt = $this->db->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->execute([$email, $id]);
        return ['status' => 'updated'];
    }

    public function deleteUser($id) {
        // FIXME - AI CR - SQL Injection: użyj prepared statement zamiast konkatenacji
        $this->db->query("DELETE FROM users WHERE id = " . $id);
        return ['status' => 'deleted'];
    }

    public function countUsers() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsersByIds(array $ids) {
        if (empty($ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deactivateUser($id) {
        $stmt = $this->db->prepare("UPDATE users SET active = 0, deactivated_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
        return ['status' => 'deactivated'];
    }

    public function getActiveUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE active = 1 ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // FIXME - AI CR - Brak autoryzacji: dodaj sprawdzenie sesji/tokenu przed każdą metodą
}
EOF
    git add . && git commit -m "ref #200002 Dodanie API uzytkownikow #BUG|jan.kowalski" -q

    # Commit 4: dev naprawił searchUsers, getUserById, createUser (z fixture)
    cp "$FIXTURES_DIR/recr-unfixed-bugs/api/users.php" api/users.php
    git add . && git commit -m "ref #200002 Poprawka SQL injection w searchUsers, getUserById, createUser" -q
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
