#!/usr/bin/env bash
# collect_branch_info.sh <ISSUE_ID>
#
# Uruchamiaj po `git fetch --all` (Step 4).
# Zbiera dane o gałęziach i stanie working tree — zastępuje ~6 osobnych komend.
# Output: JSON na stdout

ISSUE_ID="${1}"
if [ -z "$ISSUE_ID" ]; then
  printf '{"error":"missing_issue_id","message":"Podaj numer zagadnienia jako argument."}\n'
  exit 1
fi

# DEFAULT_BRANCH — dwa sposoby wykrycia
DEFAULT_BRANCH=$(git symbolic-ref refs/remotes/origin/HEAD 2>/dev/null | sed 's|refs/remotes/origin/||')
if [ -z "$DEFAULT_BRANCH" ]; then
  DEFAULT_BRANCH=$(git branch -r 2>/dev/null | grep -E 'origin/(main|develop|master)$' | head -1 | sed 's|.*origin/||')
fi

# Working tree — czystość
DIRTY_OUTPUT=$(git status --porcelain 2>/dev/null)
if [ -z "$DIRTY_OUTPUT" ]; then
  IS_CLEAN="true"
  DIRTY_COUNT=0
else
  IS_CLEAN="false"
  DIRTY_COUNT=$(printf '%s\n' "$DIRTY_OUTPUT" | wc -l | tr -d ' ')
fi

# Aktualny branch
CURRENT_BRANCH=$(git branch --show-current 2>/dev/null)

# Feature branch — szukaj dokładnego dopasowania (lokalny i zdalny)
FEATURE_BRANCH=""
if git branch -a 2>/dev/null | grep -qE "(^|\s)\*?\s*(remotes/origin/)?feature/${ISSUE_ID}$"; then
  FEATURE_BRANCH="feature/${ISSUE_ID}"
fi

# Alternatywne branche jeśli brak dokładnego dopasowania
ALTERNATES_JSON="[]"
if [ -z "$FEATURE_BRANCH" ]; then
  ALTS=$(git branch -a 2>/dev/null \
    | sed 's|^[* ]*||' \
    | sed 's|remotes/origin/||' \
    | grep "${ISSUE_ID}" \
    | sort -u \
    | grep -v "^HEAD")
  if [ -n "$ALTS" ]; then
    ALTERNATES_JSON="["
    FIRST=true
    while IFS= read -r branch; do
      [ -z "$branch" ] && continue
      branch_esc=$(printf '%s' "$branch" | sed 's/"/\\"/g')
      if [ "$FIRST" = true ]; then
        FIRST=false
      else
        ALTERNATES_JSON="${ALTERNATES_JSON},"
      fi
      ALTERNATES_JSON="${ALTERNATES_JSON}\"${branch_esc}\""
    done <<< "$ALTS"
    ALTERNATES_JSON="${ALTERNATES_JSON}]"
  fi
fi

# Staleness — ile commitów za origin/DEFAULT_BRANCH
# Jeśli branch istnieje tylko zdalnie (brak lokalnego refa), użyj origin/
BEHIND_COUNT=0
if [ -n "$DEFAULT_BRANCH" ] && [ -n "$FEATURE_BRANCH" ]; then
  if git rev-parse --verify "${FEATURE_BRANCH}" >/dev/null 2>&1; then
    BEHIND_COUNT=$(git rev-list --count "${FEATURE_BRANCH}..origin/${DEFAULT_BRANCH}" 2>/dev/null || echo 0)
  else
    BEHIND_COUNT=$(git rev-list --count "origin/${FEATURE_BRANCH}..origin/${DEFAULT_BRANCH}" 2>/dev/null || echo 0)
  fi
fi

# Ile commitów remote feature ma więcej niż lokalny branch (czy trzeba git pull --rebase)
# Jeśli branch istnieje tylko zdalnie — nie ma czego pobierać, remote_ahead_count=0
REMOTE_AHEAD_COUNT=0
if [ -n "$FEATURE_BRANCH" ]; then
  if git rev-parse --verify "${FEATURE_BRANCH}" >/dev/null 2>&1; then
    REMOTE_AHEAD_COUNT=$(git rev-list --count "${FEATURE_BRANCH}..origin/${FEATURE_BRANCH}" 2>/dev/null || echo 0)
  fi
fi

printf '{"default_branch":"%s","current_branch":"%s","feature_branch":"%s","alternate_branches":%s,"is_clean":%s,"dirty_count":%s,"behind_count":%s,"remote_ahead_count":%s}\n' \
  "${DEFAULT_BRANCH}" \
  "${CURRENT_BRANCH}" \
  "${FEATURE_BRANCH}" \
  "${ALTERNATES_JSON}" \
  "${IS_CLEAN}" \
  "${DIRTY_COUNT}" \
  "${BEHIND_COUNT}" \
  "${REMOTE_AHEAD_COUNT}"
