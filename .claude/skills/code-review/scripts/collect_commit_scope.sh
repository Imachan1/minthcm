#!/usr/bin/env bash
# collect_commit_scope.sh <ISSUE_ID>
#
# Uruchamiaj gdy jesteś na feature branchu (po Step 5).
# Wydziela commity feature vs CR, wykrywa re-review, ustala DIFF_BASE/DIFF_HEAD.
# Output: JSON na stdout

ISSUE_ID="${1}"
if [ -z "$ISSUE_ID" ]; then
  printf '{"error":"missing_issue_id","message":"Podaj numer zagadnienia jako argument."}\n'
  exit 1
fi

# Wszystkie commity odwołujące się do ISSUE_ID (bez merge commitów)
ALL_COMMITS=$(git log --oneline --no-merges --grep="ref #${ISSUE_ID}" 2>/dev/null)

if [ -z "$ALL_COMMITS" ]; then
  printf '{"error":"no_commits","message":"Nie znaleziono commitów odwołujących się do #%s. Upewnij się, że commity zawierają '\''ref #%s'\'' w wiadomości."}\n' \
    "$ISSUE_ID" "$ISSUE_ID"
  exit 0
fi

# Commity feature (bez #BUG|) i CR (z #BUG|)
FEATURE_COMMITS=$(printf '%s\n' "$ALL_COMMITS" | grep -v "#BUG|" || true)
CR_COMMITS=$(printf '%s\n' "$ALL_COMMITS" | grep "#BUG|" || true)

IS_RE_REVIEW="false"

if [ -n "$CR_COMMITS" ]; then
  IS_RE_REVIEW="true"
  # Re-review: tylko commity feature NOWSZE niż ostatni commit CR
  LAST_CR_HASH=$(printf '%s\n' "$CR_COMMITS" | head -1 | awk '{print $1}')
  FEATURE_COMMITS=$(git log "${LAST_CR_HASH}..HEAD" --oneline --no-merges --grep="ref #${ISSUE_ID}" 2>/dev/null | grep -v "#BUG|" || true)
fi

if [ -z "$FEATURE_COMMITS" ]; then
  if [ "$IS_RE_REVIEW" = "true" ]; then
    printf '{"error":"no_new_commits","message":"Brak nowych zmian do przeglądu od ostatniego CR."}\n'
  else
    printf '{"error":"only_cr_commits","message":"Znaleziono tylko commity CR (#BUG|) — brak commitów feature do przeglądu."}\n'
  fi
  exit 0
fi

# DIFF_BASE / DIFF_HEAD — git log: najnowszy na górze, najstarszy na dole
NEWEST_HASH=$(printf '%s\n' "$FEATURE_COMMITS" | head -1 | awk '{print $1}')
OLDEST_HASH=$(printf '%s\n' "$FEATURE_COMMITS" | tail -1 | awk '{print $1}')
DIFF_BASE="${OLDEST_HASH}~1"
DIFF_HEAD="${NEWEST_HASH}"

FEATURE_COUNT=$(printf '%s\n' "$FEATURE_COMMITS" | wc -l | tr -d ' ')
CR_COUNT=0
if [ -n "$CR_COMMITS" ]; then
  CR_COUNT=$(printf '%s\n' "$CR_COMMITS" | wc -l | tr -d ' ')
fi

# Pomocnik: buduje JSON array z linii "hash message"
build_commits_json() {
  local input="$1"
  local out="["
  local first=true
  while IFS= read -r line; do
    [ -z "$line" ] && continue
    local hash msg
    hash=$(printf '%s' "$line" | awk '{print $1}')
    msg=$(printf '%s' "$line" | cut -d' ' -f2- | sed 's/\\/\\\\/g' | sed 's/"/\\"/g')
    if [ "$first" = true ]; then
      first=false
    else
      out="${out},"
    fi
    out="${out}{\"hash\":\"${hash}\",\"message\":\"${msg}\"}"
  done <<< "$input"
  out="${out}]"
  printf '%s' "$out"
}

FEATURE_JSON=$(build_commits_json "$FEATURE_COMMITS")
CR_JSON=$([ -n "$CR_COMMITS" ] && build_commits_json "$CR_COMMITS" || printf '[]')

printf '{"is_re_review":%s,"feature_count":%s,"cr_count":%s,"diff_base":"%s","diff_head":"%s","last_cr_hash":"%s","feature_commits":%s,"cr_commits":%s}\n' \
  "$IS_RE_REVIEW" \
  "$FEATURE_COUNT" \
  "$CR_COUNT" \
  "$DIFF_BASE" \
  "$DIFF_HEAD" \
  "${LAST_CR_HASH:-}" \
  "$FEATURE_JSON" \
  "$CR_JSON"
