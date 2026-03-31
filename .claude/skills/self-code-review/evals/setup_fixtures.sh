#!/bin/bash
# setup_fixtures.sh — tworzy testowe repozytoria git dla ewaluacji skilla self-code-review
# Uruchom: bash evals/setup_fixtures.sh (z katalogu skills/common/self-code-review)
#
# Dane fixture (kod źródłowy) są commitowane w evals/fixtures/.
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
# Fixture 300001: Debug code + błędy logiczne
# var_dump zostawiony w kodzie, off-by-one, assignment zamiast comparison
# ============================================================
create_fixture_300001() {
    echo ""
    echo "--- Fixture 300001: debug code + błędy logiczne ---"
    local REPO="$FIXTURES_BASE/300001"

    init_repo 300001

    git checkout -b develop -q
    mkdir -p src
    printf '<?php\nfunction calculateTotal($items) { return 0; }\nfunction applyDiscount($total, $percent) { return $total; }\n' > src/cart.php
    git add . && git commit -m "Initial cart implementation" -q
    git push -u origin develop -q

    git checkout -b feature/300001 -q
    mkdir -p src
    cp "$FIXTURES_DIR/debug-code-and-logic-errors/src/cart.php" src/cart.php
    git add . && git commit -m "ref #300001 Refactor calculateTotal to index-based loop" -q
    git push -u origin feature/300001 -q

    cd "$FIXTURES_BASE/300001-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 300002: SQL injection + niezrealizowany krok planu
# getUserById/createUser przez konkatenację stringa, brak deleteUser
# ============================================================
create_fixture_300002() {
    echo ""
    echo "--- Fixture 300002: SQL injection + plan completeness ---"
    local REPO="$FIXTURES_BASE/300002"

    init_repo 300002

    git checkout -b develop -q
    mkdir -p src
    printf '<?php\nclass UserService {\n    private $db;\n    public function __construct($db) { $this->db = $db; }\n    // TODO: implement\n}\n' > src/UserService.php
    git add . && git commit -m "Add UserService stub" -q
    git push -u origin develop -q

    git checkout -b feature/300002 -q
    mkdir -p src .ai/tasks/300002
    cp "$FIXTURES_DIR/security-issue-with-plan/src/UserService.php" src/UserService.php
    cp "$FIXTURES_DIR/security-issue-with-plan/.ai/tasks/300002/plan.md" .ai/tasks/300002/plan.md
    git add . && git commit -m "ref #300002 Implement UserService methods" -q
    git push -u origin feature/300002 -q

    cd "$FIXTURES_BASE/300002-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 300003: Czysty kod — READY FOR CR
# Poprawna implementacja PriceHelper bez żadnych problemów
# ============================================================
create_fixture_300003() {
    echo ""
    echo "--- Fixture 300003: czysty kod, READY FOR CR ---"
    local REPO="$FIXTURES_BASE/300003"

    init_repo 300003

    git checkout -b develop -q
    mkdir -p src
    printf '<?php\n// Price utility stubs — to be implemented\n' > src/PriceHelper.php
    git add . && git commit -m "Add PriceHelper stub" -q
    git push -u origin develop -q

    git checkout -b feature/300003 -q
    mkdir -p src
    cp "$FIXTURES_DIR/clean-code-ready-for-cr/src/PriceHelper.php" src/PriceHelper.php
    git add . && git commit -m "ref #300003 Implement PriceHelper formatPrice and parsePrice" -q
    git push -u origin feature/300003 -q

    cd "$FIXTURES_BASE/300003-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 300004: Performance — N+1 queries + brak LIMIT
# Zapytania DB w pętli foreach, SELECT *, brak LIMIT na dużym zbiorze
# ============================================================
create_fixture_300004() {
    echo ""
    echo "--- Fixture 300004: performance N+1 + brak LIMIT ---"
    local REPO="$FIXTURES_BASE/300004"

    init_repo 300004

    git checkout -b develop -q
    mkdir -p src
    printf '<?php\nclass OrderReport {\n    private $db;\n    public function __construct(PDO $db) { $this->db = $db; }\n}\n' > src/OrderReport.php
    git add . && git commit -m "Add OrderReport stub" -q
    git push -u origin develop -q

    git checkout -b feature/300004 -q
    mkdir -p src
    cp "$FIXTURES_DIR/performance-n-plus-one/src/OrderReport.php" src/OrderReport.php
    git add . && git commit -m "ref #300004 Implement OrderReport generateReport and getTopProducts" -q
    git push -u origin feature/300004 -q

    cd "$FIXTURES_BASE/300004-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 300005: Multi-file diff — problemy w 3 plikach
# Controller (brak walidacji), Model (empty catch, SQL injection, TODO),
# PriceFormatter (debug code)
# ============================================================
create_fixture_300005() {
    echo ""
    echo "--- Fixture 300005: multi-file, różne problemy w 3 plikach ---"
    local REPO="$FIXTURES_BASE/300005"

    init_repo 300005

    git checkout -b develop -q
    mkdir -p src
    printf '<?php\nclass ProductController {}\n' > src/ProductController.php
    printf '<?php\nclass ProductModel {}\n' > src/ProductModel.php
    printf '<?php\nclass PriceFormatter {}\n' > src/PriceFormatter.php
    git add . && git commit -m "Add product module stubs" -q
    git push -u origin develop -q

    git checkout -b feature/300005 -q
    mkdir -p src
    cp "$FIXTURES_DIR/multi-file-mixed-issues/src/ProductController.php" src/ProductController.php
    cp "$FIXTURES_DIR/multi-file-mixed-issues/src/ProductModel.php" src/ProductModel.php
    cp "$FIXTURES_DIR/multi-file-mixed-issues/src/PriceFormatter.php" src/PriceFormatter.php
    git add . && git commit -m "ref #300005 Implement product module (controller, model, formatter)" -q
    git push -u origin feature/300005 -q

    cd "$FIXTURES_BASE/300005-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 300006: Security — XSS + hardcoded credentials
# Niesanityzowany output HTML, hasło DB w kodzie źródłowym
# ============================================================
create_fixture_300006() {
    echo ""
    echo "--- Fixture 300006: XSS + hardcoded credentials ---"
    local REPO="$FIXTURES_BASE/300006"

    init_repo 300006

    git checkout -b develop -q
    mkdir -p src
    printf '<?php\nclass ProfilePage {\n    private $db;\n    public function __construct(PDO $db) { $this->db = $db; }\n}\n' > src/ProfilePage.php
    git add . && git commit -m "Add ProfilePage stub" -q
    git push -u origin develop -q

    git checkout -b feature/300006 -q
    mkdir -p src
    cp "$FIXTURES_DIR/xss-and-hardcoded-credentials/src/ProfilePage.php" src/ProfilePage.php
    git add . && git commit -m "ref #300006 Implement ProfilePage render and updateBio" -q
    git push -u origin feature/300006 -q

    cd "$FIXTURES_BASE/300006-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

create_fixture_300001
create_fixture_300002
create_fixture_300003
create_fixture_300004
create_fixture_300005
create_fixture_300006

echo ""
echo "==> Wszystkie fixtures utworzone w $FIXTURES_BASE"
