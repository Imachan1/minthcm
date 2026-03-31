#!/usr/bin/env bash
# check-updates.sh — sprawdza czy dostępne są aktualizacje skilli
#
# Użycie:
#   check-updates.sh           # Interaktywny raport
#   check-updates.sh --ci      # Tryb CI: exit 1 gdy są aktualizacje
#
# Wymagania: git, bash (Git Bash na Windows)

set -euo pipefail

# ── Kolory ────────────────────────────────────────────────────────────────────

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
BOLD='\033[1m'
RESET='\033[0m'

err()  { echo -e "${RED}Błąd: $*${RESET}" >&2; }
info() { echo -e "${CYAN}$*${RESET}"; }
ok()   { echo -e "${GREEN}  $*${RESET}"; }
warn() { echo -e "${YELLOW}  $*${RESET}"; }

# ── Argumenty ─────────────────────────────────────────────────────────────────

CI_MODE=false

while [[ $# -gt 0 ]]; do
  case "$1" in
    --ci)     CI_MODE=true; shift ;;
    -h|--help) echo "Użycie: $0 [--ci]"; exit 0 ;;
    *) err "Nieznany parametr: $1"; exit 1 ;;
  esac
done

# ── Walidacja środowiska ───────────────────────────────────────────────────────

if ! git rev-parse --git-dir > /dev/null 2>&1; then
  err "Skrypt musi być uruchomiony z poziomu repozytorium git."
  exit 1
fi

PROJECT_ROOT=$(git rev-parse --show-toplevel)
MANIFEST="$PROJECT_ROOT/.claude/skills-manifest.yaml"

if [[ ! -f "$MANIFEST" ]]; then
  err "Nie znaleziono .claude/skills-manifest.yaml w $PROJECT_ROOT"
  exit 1
fi

# ── Parsowanie manifestu ───────────────────────────────────────────────────────

SKILLS_REPO=$(grep 'repo:' "$MANIFEST" | sed 's/.*repo: *//')
SKILLS_REF=$(grep '  ref:' "$MANIFEST" | sed 's/.*ref: *//' | tr -d '"' | sed 's/ *#.*//')
LOCAL_COMMIT=$(grep '  ref:' "$MANIFEST" | grep -o '# commit: [a-f0-9]*' | sed 's/# commit: //' || true)

if [[ -z "$SKILLS_REPO" || -z "$SKILLS_REF" ]]; then
  err "Nie udało się odczytać source.repo lub source.ref z manifestu."
  exit 1
fi

if [[ "$CI_MODE" == false ]]; then
  info "Sprawdzam aktualizacje skilli..."
  echo -e "  repo:   ${BOLD}$SKILLS_REPO${RESET}"
  echo -e "  ref:    ${BOLD}$SKILLS_REF${RESET}"
  echo -e "  commit: ${BOLD}${LOCAL_COMMIT:-nieznany}${RESET}"
  echo ""
fi

# ── Klonowanie upstream ────────────────────────────────────────────────────────

TEMP_DIR=$(mktemp -d)
trap 'rm -rf "$TEMP_DIR"' EXIT

if ! git clone --depth 1 --branch "$SKILLS_REF" "$SKILLS_REPO" "$TEMP_DIR/repo" --quiet 2>/dev/null; then
  err "Nie udało się połączyć z repozytorium skilli."
  exit 1
fi

UPSTREAM_COMMIT=$(git -C "$TEMP_DIR/repo" rev-parse --short HEAD)

# ── Porównanie commitów ────────────────────────────────────────────────────────

if [[ "$LOCAL_COMMIT" == "$UPSTREAM_COMMIT" ]]; then
  [[ "$CI_MODE" == false ]] && ok "✓ Skille są aktualne (commit: $UPSTREAM_COMMIT)"
  exit 0
fi

# ── Są aktualizacje — raport ──────────────────────────────────────────────────

if [[ "$CI_MODE" == true ]]; then
  echo -e "${YELLOW}[CI] Skille nieaktualne: ${LOCAL_COMMIT:-?} → $UPSTREAM_COMMIT. Uruchom sync.sh --pull.${RESET}"
  exit 1
fi

echo -e "${YELLOW}${BOLD}Dostępne aktualizacje!${RESET}"
echo -e "  Lokalny commit:  ${BOLD}${LOCAL_COMMIT:-nieznany}${RESET}"
echo -e "  Upstream commit: ${BOLD}$UPSTREAM_COMMIT${RESET}"
echo ""

# ── Porównanie wersji skilli ───────────────────────────────────────────────────

echo -e "${BOLD}Wersje skilli:${RESET}"

mapfile -t SKILL_NAMES  < <(grep '  - name:'      "$MANIFEST" | sed 's/.*- name: *//')
mapfile -t SOURCE_PATHS < <(grep '    source_path:' "$MANIFEST" | sed 's/.*source_path: *//')

skill_local_only() {
  local skill_name="$1"
  awk -v name="$skill_name" '
    /^  - name:/ { current = $NF }
    current == name && /^    local_only:/ { print $NF; exit }
  ' "$MANIFEST"
}

HAS_CHANGES=false

for i in "${!SKILL_NAMES[@]}"; do
  name="${SKILL_NAMES[$i]}"
  src_path="${SOURCE_PATHS[$i]}"

  if [[ "$(skill_local_only "$name")" == "true" ]]; then
    continue
  fi

  LOCAL_SKILL="$PROJECT_ROOT/.claude/skills/$name/SKILL.md"
  UPSTREAM_SKILL="$TEMP_DIR/repo/$src_path/SKILL.md"

  LOCAL_VER=""
  UPSTREAM_VER=""

  [[ -f "$LOCAL_SKILL"    ]] && LOCAL_VER=$(grep    '^version:' "$LOCAL_SKILL"    | sed 's/version: *//' | tr -d '"' || true)
  [[ -f "$UPSTREAM_SKILL" ]] && UPSTREAM_VER=$(grep '^version:' "$UPSTREAM_SKILL" | sed 's/version: *//' | tr -d '"' || true)

  if [[ "$LOCAL_VER" != "$UPSTREAM_VER" ]]; then
    echo -e "  ${YELLOW}~ $name${RESET}  ${LOCAL_VER:-?} → ${UPSTREAM_VER:-?}"
    HAS_CHANGES=true
  else
    echo -e "  ${GREEN}= $name${RESET}  ${LOCAL_VER:-?}"
  fi
done

echo ""

echo -e "Aby zaktualizować: ${CYAN}.claude/skills/skills-sync/scripts/sync.sh --pull${RESET}"
