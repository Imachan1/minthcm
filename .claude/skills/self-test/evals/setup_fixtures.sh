#!/bin/bash
# setup_fixtures.sh — tworzy testowe repozytoria git dla ewaluacji skilla self-test
# Uruchom: bash evals/setup_fixtures.sh (z katalogu skills/common/self-test)
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
# Fixture 400001: Backend model — dodanie metody getActiveByAccount
# Zmiana czysto backendowa (PHP), brak plików frontend.
# Oczekiwane: happy path, edge cases, regresja; brak Chrome snippetów.
# ============================================================
create_fixture_400001() {
    echo ""
    echo "--- Fixture 400001: backend model change ---"
    local REPO="$FIXTURES_BASE/400001"

    init_repo 400001

    git checkout -b develop -q
    mkdir -p src
    cp "$FIXTURES_DIR/backend-model-change/src/ContactModel.php" src/ContactModel.php
    git add . && git commit -m "Add ContactModel stub" -q
    git push -u origin develop -q

    git checkout -b feature/400001 -q
    cp "$FIXTURES_DIR/backend-model-change/src/ContactModel_after.php" src/ContactModel.php
    git add . && git commit -m "ref #400001 Add getActiveByAccount to ContactModel" -q
    git push -u origin feature/400001 -q

    cd "$FIXTURES_BASE/400001-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 400002: Frontend — nowe pole telefon w formularzu + walidacja JS
# Zmiana dotyczy frontendu (PHP view + JS). Jest plan z kryteriami akceptacji.
# Oczekiwane: ścieżki z kryteriów akceptacji, Chrome snippety JS.
# ============================================================
create_fixture_400002() {
    echo ""
    echo "--- Fixture 400002: frontend form + plan ---"
    local REPO="$FIXTURES_BASE/400002"

    init_repo 400002

    git checkout -b develop -q
    mkdir -p modules/Contacts/views include/js
    cp "$FIXTURES_DIR/frontend-form-with-plan/modules/Contacts/views/EditView.php" modules/Contacts/views/EditView.php
    printf '// contacts.js — placeholder\n' > include/js/contacts.js
    git add . && git commit -m "Add Contacts EditView and JS placeholder" -q
    git push -u origin develop -q

    git checkout -b feature/400002 -q
    mkdir -p modules/Contacts/views include/js .ai/tasks/400002
    cp "$FIXTURES_DIR/frontend-form-with-plan/modules/Contacts/views/EditView_after.php" modules/Contacts/views/EditView.php
    cp "$FIXTURES_DIR/frontend-form-with-plan/include/js/contacts_after.js" include/js/contacts.js
    cp "$FIXTURES_DIR/frontend-form-with-plan/.ai/tasks/400002/plan.md" .ai/tasks/400002/plan.md
    git add . && git commit -m "ref #400002 Add phone field with JS validation to Contacts EditView" -q
    git push -u origin feature/400002 -q

    cd "$FIXTURES_BASE/400002-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

# ============================================================
# Fixture 400003: Bugfix — poprawka parsowania daty w ReportController
# Zmiana backendowa (PHP), naprawia format daty z Y-m-d na d.m.Y.
# Dodano też walidację kolejności dat (from <= to).
# Oczekiwane: test happy path (poprawny format), edge cases (błędny format,
# data_od > data_do), regresja (inne raporty używające kontrolera).
# ============================================================
create_fixture_400003() {
    echo ""
    echo "--- Fixture 400003: bugfix date validation ---"
    local REPO="$FIXTURES_BASE/400003"

    init_repo 400003

    git checkout -b develop -q
    mkdir -p modules/Reports
    cp "$FIXTURES_DIR/bugfix-date-validation/modules/Reports/ReportController.php" modules/Reports/ReportController.php
    git add . && git commit -m "Add ReportController" -q
    git push -u origin develop -q

    git checkout -b feature/400003 -q
    cp "$FIXTURES_DIR/bugfix-date-validation/modules/Reports/ReportController_after.php" modules/Reports/ReportController.php
    git add . && git commit -m "ref #400003 Fix date parsing format in generateSalesReport (Y-m-d -> d.m.Y)" -q
    git push -u origin feature/400003 -q

    cd "$FIXTURES_BASE/400003-origin.git"
    git symbolic-ref HEAD refs/heads/develop

    echo "    OK: $REPO"
}

create_fixture_400001
create_fixture_400002
create_fixture_400003

echo ""
echo "==> Wszystkie fixtures utworzone w $FIXTURES_BASE"
