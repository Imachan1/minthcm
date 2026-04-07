#!/usr/bin/env bash
# get_author_info.sh <ISSUE_ID> <DIFF_BASE> <DIFF_HEAD>
#
# Uruchamiaj po Step 7a (collect_commit_scope.sh) — wymaga DIFF_BASE i DIFF_HEAD.
# Identyfikuje autora kodu spośród commitów z zakresu DIFF_BASE..DIFF_HEAD.
# Output: JSON na stdout

ISSUE_ID="${1}"
DIFF_BASE="${2}"
DIFF_HEAD="${3}"

if [ -z "$ISSUE_ID" ]; then
  printf '{"error":"missing_issue_id","message":"Podaj numer zagadnienia jako argument."}\n'
  exit 1
fi

if [ -z "$DIFF_BASE" ] || [ -z "$DIFF_HEAD" ]; then
  printf '{"error":"missing_range","message":"Podaj DIFF_BASE i DIFF_HEAD (wynik collect_commit_scope.sh)."}\n'
  exit 1
fi

# Autor ostatniego commita w zakresie feature (DIFF_BASE..DIFF_HEAD)
LAST_COMMIT_LINE=$(git log "${DIFF_BASE}..${DIFF_HEAD}" --no-merges -1 --format="%ae %an" 2>/dev/null)

if [ -z "$LAST_COMMIT_LINE" ]; then
  printf '{"error":"no_feature_commits","message":"Nie znaleziono commitów w zakresie %s..%s."}\n' "$DIFF_BASE" "$DIFF_HEAD"
  exit 0
fi

AUTHOR_EMAIL=$(printf '%s' "$LAST_COMMIT_LINE" | awk '{print $1}')
AUTHOR_NAME=$(printf '%s' "$LAST_COMMIT_LINE" | cut -d' ' -f2-)
AUTHOR_LOGIN=$(printf '%s' "$AUTHOR_EMAIL" | cut -d'@' -f1)

# Sprawdź czy w zakresie commitowało więcej niż jedna osoba
ALL_AUTHORS_JSON="[]"
IS_MULTIPLE_AUTHORS="false"

ALL_EMAILS=$(git log "${DIFF_BASE}..${DIFF_HEAD}" --no-merges --format="%ae" 2>/dev/null | sort -u)

if [ -n "$ALL_EMAILS" ]; then
  EMAIL_COUNT=$(printf '%s\n' "$ALL_EMAILS" | wc -l | tr -d ' ')

  if [ "$EMAIL_COUNT" -gt 1 ]; then
    IS_MULTIPLE_AUTHORS="true"
    ALL_AUTHORS_JSON="["
    FIRST=true
    while IFS= read -r email; do
      [ -z "$email" ] && continue
      name=$(git log "${DIFF_BASE}..${DIFF_HEAD}" --no-merges --format="%ae %an" 2>/dev/null \
        | grep -F "${email} " | head -1 | cut -d' ' -f2-)
      email_esc=$(printf '%s' "$email" | sed 's/"/\\"/g')
      name_esc=$(printf '%s' "$name" | sed 's/"/\\"/g')
      login_esc=$(printf '%s' "$email" | cut -d'@' -f1 | sed 's/"/\\"/g')
      if [ "$FIRST" = true ]; then
        FIRST=false
      else
        ALL_AUTHORS_JSON="${ALL_AUTHORS_JSON},"
      fi
      ALL_AUTHORS_JSON="${ALL_AUTHORS_JSON}{\"email\":\"${email_esc}\",\"name\":\"${name_esc}\",\"login\":\"${login_esc}\"}"
    done <<< "$ALL_EMAILS"
    ALL_AUTHORS_JSON="${ALL_AUTHORS_JSON}]"
  fi
fi

AUTHOR_EMAIL_ESC=$(printf '%s' "$AUTHOR_EMAIL" | sed 's/"/\\"/g')
AUTHOR_NAME_ESC=$(printf '%s' "$AUTHOR_NAME" | sed 's/"/\\"/g')
AUTHOR_LOGIN_ESC=$(printf '%s' "$AUTHOR_LOGIN" | sed 's/"/\\"/g')

printf '{"author_email":"%s","author_name":"%s","author_login":"%s","is_multiple_authors":%s,"all_authors":%s}\n' \
  "$AUTHOR_EMAIL_ESC" \
  "$AUTHOR_NAME_ESC" \
  "$AUTHOR_LOGIN_ESC" \
  "$IS_MULTIPLE_AUTHORS" \
  "$ALL_AUTHORS_JSON"
