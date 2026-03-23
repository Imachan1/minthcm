#!/usr/bin/env bash
# sync.sh — synchronizacja skilli między głównym repo a projektem
#
# Użycie:
#   sync.sh --pull              # Pobierz aktualizacje skilli z upstream
#   sync.sh --pull --force      # Pobierz, nadpisując też skille z local_changes: true
#   sync.sh --push <skill>      # Pokaż diff lokalnego skilla względem upstream
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
ok()   { echo -e "${GREEN}  + $*${RESET}"; }
warn() { echo -e "${YELLOW}  ! $*${RESET}"; }

# ── Argumenty ─────────────────────────────────────────────────────────────────

MODE=""
PUSH_SKILL=""
FORCE=false

usage() {
  echo "Użycie: $0 --pull [--force]"
  echo "        $0 --push <nazwa-skilla>"
  exit 1
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --pull)  MODE="pull"; shift ;;
    --push)  MODE="push"; PUSH_SKILL="${2:-}"; shift; [[ -n "$PUSH_SKILL" ]] && shift || true ;;
    --force) FORCE=true; shift ;;
    -h|--help) usage ;;
    *) err "Nieznany parametr: $1"; usage ;;
  esac
done

if [[ -z "$MODE" ]]; then
  err "Podaj tryb: --pull lub --push <skill>."
  usage
fi

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

PROJECT_SYSTEM=$(grep 'system:' "$MANIFEST" | head -1 | sed 's/.*system: *//')
PROJECT_VERSION=$(grep 'system_version:' "$MANIFEST" | sed 's/.*system_version: *//' | tr -d '"')
PROJECT_CLIENT=$(grep 'client:' "$MANIFEST" | sed 's/.*client: *//' | tr -d '"')

mapfile -t SKILL_NAMES    < <(grep '  - name:'       "$MANIFEST" | sed 's/.*- name: *//')
mapfile -t SOURCE_PATHS   < <(grep '    source_path:' "$MANIFEST" | sed 's/.*source_path: *//')
mapfile -t LOCAL_CHANGES  < <(grep '    local_changes:' "$MANIFEST" | sed 's/.*local_changes: *//')

if [[ -z "$SKILLS_REPO" || -z "$SKILLS_REF" ]]; then
  err "Nie udało się odczytać source.repo lub source.ref z manifestu."
  exit 1
fi

# ── Klonowanie upstream ────────────────────────────────────────────────────────

TEMP_DIR=$(mktemp -d)
trap 'rm -rf "$TEMP_DIR"' EXIT

info "Klonuję repo skilli (ref: $SKILLS_REF)..."
CLONE_DEPTH=$([[ "$MODE" == "push" ]] && echo "" || echo "--depth 1")
if ! git clone $CLONE_DEPTH --branch "$SKILLS_REF" "$SKILLS_REPO" "$TEMP_DIR/repo" --quiet; then
  err "Nie udało się sklonować $SKILLS_REPO"
  exit 1
fi

UPSTREAM_COMMIT=$(git -C "$TEMP_DIR/repo" rev-parse --short HEAD)

# ══════════════════════════════════════════════════════════════════════════════
# TRYB: --pull
# ══════════════════════════════════════════════════════════════════════════════

if [[ "$MODE" == "pull" ]]; then

  if [[ "$LOCAL_COMMIT" == "$UPSTREAM_COMMIT" ]]; then
    ok "Skille są aktualne (commit: $UPSTREAM_COMMIT)"
    exit 0
  fi

  echo -e "  Lokalny commit:  ${BOLD}${LOCAL_COMMIT:-nieznany}${RESET}"
  echo -e "  Upstream commit: ${BOLD}$UPSTREAM_COMMIT${RESET}"
  echo ""
  info "Aktualizuję skille..."

  UPDATED=()
  SKIPPED=()

  for i in "${!SKILL_NAMES[@]}"; do
    name="${SKILL_NAMES[$i]}"
    src_path="${SOURCE_PATHS[$i]}"
    local_changes="${LOCAL_CHANGES[$i]}"

    src="$TEMP_DIR/repo/$src_path"
    dst="$PROJECT_ROOT/.claude/skills/$name"

    if [[ ! -d "$src" ]]; then
      warn "$name — pominięto (nie znaleziono w upstream: $src_path)"
      SKIPPED+=("$name")
      continue
    fi

    if [[ "$local_changes" == "true" && "$FORCE" == false ]]; then
      warn "$name — pominięto (local_changes: true). Użyj --force aby nadpisać."
      SKIPPED+=("$name")
      continue
    fi

    rm -rf "$dst"
    cp -r "$src" "$dst"
    UPDATED+=("$name")
    ok "$name"
  done

  # Zaktualizuj commit hash w skills-manifest.yaml
  sed -i "s|# commit: [a-f0-9]*|# commit: $UPSTREAM_COMMIT|" "$MANIFEST"

  echo ""
  echo -e "${GREEN}${BOLD}Gotowe.${RESET}"
  echo ""
  echo -e "${BOLD}Zaktualizowane (${#UPDATED[@]}):${RESET}"
  for s in "${UPDATED[@]}"; do ok "$s"; done

  if [[ ${#SKIPPED[@]} -gt 0 ]]; then
    echo ""
    echo -e "${YELLOW}${BOLD}Pominięte (${#SKIPPED[@]}):${RESET}"
    for s in "${SKIPPED[@]}"; do warn "$s"; done
  fi

  echo ""
  echo -e "${BOLD}Następny krok — zacommituj zmiany:${RESET}"
  echo -e "  ${CYAN}git add .claude/${RESET}"
  echo -e "  ${CYAN}git commit -m \"Update Claude Code skills to $UPSTREAM_COMMIT\"${RESET}"

fi

# ══════════════════════════════════════════════════════════════════════════════
# TRYB: --push
# ══════════════════════════════════════════════════════════════════════════════

if [[ "$MODE" == "push" ]]; then

  # Bez nazwy skilla — wylistuj dostępne i zakończ
  if [[ -z "$PUSH_SKILL" ]]; then
    err "Nie podano nazwy skilla."
    echo ""
    echo -e "${BOLD}Dostępne skille:${RESET}"
    for s in "${SKILL_NAMES[@]}"; do echo "  - $s"; done
    echo ""
    echo -e "Użycie: ${CYAN}sync.sh --push <nazwa-skilla>${RESET}"
    exit 1
  fi

  # Znajdź source_path dla podanego skilla
  SKILL_SRC_PATH=""
  for i in "${!SKILL_NAMES[@]}"; do
    if [[ "${SKILL_NAMES[$i]}" == "$PUSH_SKILL" ]]; then
      SKILL_SRC_PATH="${SOURCE_PATHS[$i]}"
      break
    fi
  done

  if [[ -z "$SKILL_SRC_PATH" ]]; then
    err "Skill '$PUSH_SKILL' nie istnieje w skills-manifest.yaml."
    echo ""
    echo -e "${BOLD}Dostępne skille:${RESET}"
    for s in "${SKILL_NAMES[@]}"; do echo "  - $s"; done
    exit 1
  fi

  LOCAL_SKILL="$PROJECT_ROOT/.claude/skills/$PUSH_SKILL"
  UPSTREAM_SKILL="$TEMP_DIR/repo/$SKILL_SRC_PATH"

  if [[ ! -d "$LOCAL_SKILL" ]]; then
    err "Lokalny skill '$PUSH_SKILL' nie istnieje w .claude/skills/"
    exit 1
  fi

  # ── Sprawdź czy upstream nie poszedł do przodu ─────────────────────────────

  if [[ -n "$LOCAL_COMMIT" && "$LOCAL_COMMIT" != "$UPSTREAM_COMMIT" ]]; then
    err "Upstream ma nowsze commity od ostatniego sync ($LOCAL_COMMIT → $UPSTREAM_COMMIT)."
    echo -e "  Najpierw zaktualizuj skille: ${CYAN}.claude/skills/skills-sync/scripts/sync.sh --pull${RESET}"
    exit 1
  fi

  # ── Sprawdź czy są różnice ─────────────────────────────────────────────────

  if diff -rq "$UPSTREAM_SKILL" "$LOCAL_SKILL" > /dev/null 2>&1; then
    ok "Brak różnic — skill jest identyczny z upstream."
    exit 0
  fi

  echo ""
  info "Diff skilla '$PUSH_SKILL':"
  echo ""

  diff -r --unified=3 "$UPSTREAM_SKILL" "$LOCAL_SKILL" 2>/dev/null | while IFS= read -r line; do
    case "$line" in
      diff\ *)  ;;
      ---\ *)   echo -e "${BOLD}$(echo "$line" | sed "s|--- $TEMP_DIR/repo/$SKILL_SRC_PATH/\{0,1\}||" | sed 's/\t.*//')${RESET}" ;;
      +++\ *)   ;;
      @@*)      echo -e "\033[2m$line${RESET}" ;;
      -*)       echo -e "  ${RED}$line${RESET}" ;;
      +*)       echo -e "  ${GREEN}$line${RESET}" ;;
      *)        echo "  $line" ;;
    esac
  done || true

  echo ""

  # ── Stwórz branch, zacommituj i wypushuj ──────────────────────────────────

  BRANCH_NAME="update/$PUSH_SKILL-$(date +%s)"

  git -C "$TEMP_DIR/repo" checkout -b "$BRANCH_NAME" --quiet

  rm -rf "$TEMP_DIR/repo/$SKILL_SRC_PATH"
  cp -r "$LOCAL_SKILL" "$TEMP_DIR/repo/$SKILL_SRC_PATH"

  git -C "$TEMP_DIR/repo" add "$SKILL_SRC_PATH"
  git -C "$TEMP_DIR/repo" commit -m "Update skill: $PUSH_SKILL

Project: $PROJECT_CLIENT ($PROJECT_SYSTEM $PROJECT_VERSION)" --quiet

  info "Wypycham branch '$BRANCH_NAME'..."
  PUSH_OUTPUT=$(git -C "$TEMP_DIR/repo" push -u origin "$BRANCH_NAME" 2>&1) || {
    err "Push nie powiódł się."
    echo "$PUSH_OUTPUT"
    exit 1
  }
  MR_URL=$(echo "$PUSH_OUTPUT" | grep 'https://' | grep 'merge_requests' | sed 's/.*\(https:\/\/[^ ]*\).*/\1/' | tr -d ' \t' || true)
  TARGET_BRANCH_ENC=$(echo "$SKILLS_REF" | sed 's|/|%2F|g')
  [[ -n "$MR_URL" ]] && MR_URL="${MR_URL}&merge_request%5Btarget_branch%5D=${TARGET_BRANCH_ENC}"

  echo ""
  echo -e "${GREEN}${BOLD}Gotowe.${RESET}"
  echo -e "Branch ${BOLD}$BRANCH_NAME${RESET} wypushowany."
  echo ""
  if [[ -n "$MR_URL" ]]; then
    echo -e "Następny krok — utwórz Merge Request w GitLab: ${CYAN}$MR_URL${RESET}"
  else
    echo -e "Następny krok — utwórz Merge Request w GitLab: ${BOLD}$SKILLS_REPO${RESET}"
  fi

fi
